<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @implements Operation<TKey, T, \Generator<TKey, T|U>>
 */
final class Walk extends AbstractOperation implements Operation
{
    /**
     * @param callable(T, TKey): (U) ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, callable(T|U, TKey): (U)>): Generator<TKey, T|U>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, callable> $callbacks
             *
             * @return Generator<TKey, T|U>
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
