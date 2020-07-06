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
 * @implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Until extends AbstractOperation implements Operation
{
    /**
     * Until constructor.
     *
     * @param callable(T, TKey): (bool) ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, callable(T, TKey): (bool)>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, callable(T, TKey): (int)> $callbacks
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection, array $callbacks): Generator {
                foreach ($collection as $key => $value) {
                    yield $key => $value;

                    $result = array_reduce(
                        $callbacks,
                        /**
                         * @param callable(T, TKey): (int) $callable
                         */
                        static function (int $carry, callable $callable) use ($key, $value): int {
                            return $carry & $callable($value, $key);
                        },
                        1
                    );

                    if (1 === $result) {
                        break;
                    }
                }
            };
    }
}
