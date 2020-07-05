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
 * @template U of T
 * @implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Split extends AbstractOperation implements Operation
{
    /**
     * @param callable(T, TKey):(U|bool) ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, callable(T, TKey):(U|bool)>): Generator<int, array<int, T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param array<int, callable(T, TKey):(U|bool)> $callbacks
             *
             * @return Generator<int, array<int, T>>
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
