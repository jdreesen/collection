<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @template-implements Operation<TKey, T, array<int, callable(T, TKey): (U|bool)>>
 */
final class Apply extends AbstractOperation implements Operation
{
    /**
     * Apply constructor.
     *
     * @param callable ...$callbacks
     * @psalm-param callable(T, TKey): (U|bool) ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @psalm-return \Closure(iterable<TKey, T>, array<int, callable(T, TKey):(U|bool)>): \Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @param array<int, callable(T, TKey): (U|bool)> $callbacks
             *
             * @psalm-return \Generator<TKey, T>
             */
            static function (iterable $collection, array $callbacks): Generator {
                foreach ($collection as $key => $value) {
                    foreach ($callbacks as $callback) {
                        if (true === $callback($value, $key)) {
                            continue;
                        }

                        break;
                    }

                    yield $key => $value;
                }
            };
    }
}
