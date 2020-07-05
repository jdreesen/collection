<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use CallbackFilterIterator;
use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Filter extends AbstractOperation implements Operation
{
    /**
     * @param callable(T, TKey, \Iterator): bool ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @return Closure(iterable<TKey, T>, list<callable(T, TKey, \Iterator):(bool)>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param list<callable(T, TKey, \Iterator): bool> $callbacks
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection, array $callbacks): Generator {
                $iterator = new IterableIterator($collection);

                foreach ($callbacks as $callback) {
                    $iterator = new CallbackFilterIterator($iterator, $callback);
                }

                yield from $iterator;
            };
    }
}
