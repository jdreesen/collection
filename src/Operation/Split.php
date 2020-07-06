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
 * @phpstan-template U of T
 * @template-implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Split extends AbstractOperation implements Operation
{
    /**
     * @psalm-param callable(T, TKey):(U|bool) ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, array<int, callable(T, TKey):(U|bool)>): Generator<int, array<int, T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, callable> $callbacks
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param array<int, callable(T, TKey):(U|bool)> $callbacks
             *
             * @psalm-return \Generator<int, array<int, T>>
             */
            static function (iterable $collection, array $callbacks): Generator {
                $carry = [];

                foreach ($collection as $key => $value) {
                    $carry[] = $value;

                    foreach ($callbacks as $callback) {
                        if (true !== $callback($value, $key)) {
                            continue;
                        }

                        yield $carry;

                        $carry = [];
                    }
                }

                if ([] !== $carry) {
                    yield $carry;
                }
            };
    }
}
