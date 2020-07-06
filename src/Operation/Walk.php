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
 * @template-implements Operation<TKey, T, \Generator<TKey, T|U>>
 */
final class Walk extends AbstractOperation implements Operation
{
    /**
     * @param callable ...$callbacks
     * @psalm-param callable(T, TKey): (U) ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, array<int, callable(T|U, TKey): (U)>): Generator<TKey, T|U>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, callable> $callbacks
             *
             * @psalm-return \Generator<TKey, T|U>
             */
            static function (iterable $collection, array $callbacks): Generator {
                foreach ($collection as $key => $value) {
                    // Custom array_reduce function with the key passed in argument.
                    foreach ($callbacks as $callback) {
                        $value = $callback($value, $key);
                    }

                    yield $key => $value;
                }
            };
    }
}
