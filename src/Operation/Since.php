<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Since extends AbstractOperation implements Operation
{
    /**
     * Since constructor.
     *
     * @param callable(T, TKey): (bool) ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, callable(T, TKey): (int)>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param array<int, callable(T, TKey): (int)> $callbacks
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection, array $callbacks): Generator {
                $iterator = new IterableIterator($collection);

                while ($iterator->valid()) {
                    $result = array_reduce(
                        $callbacks,
                        /**
                         * @param callable(T, TKey): (int) $callable
                         */
                        static function (int $carry, callable $callable) use ($iterator): int {
                            return $carry & $callable($iterator->current(), $iterator->key());
                        },
                        1
                    );

                    if (1 === $result) {
                        break;
                    }

                    $iterator->next();
                }

                for (; $iterator->valid(); $iterator->next()) {
                    yield $iterator->key() => $iterator->current();
                }
            };
    }
}
