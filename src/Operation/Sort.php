<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use ArrayIterator;
use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\SortableIterableIterator;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Sort extends AbstractOperation implements Operation
{
    public function __construct(?callable $callback = null)
    {
        $this->storage['callback'] = $callback ?? Closure::fromCallable([$this, 'compare']);
    }

    /**
     * @return Closure(iterable<TKey, T>, callable): Generator<TKey, \ArrayIterator>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @return Generator<TKey, ArrayIterator>
             */
            static function (iterable $collection, callable $callback): Generator {
                yield from new SortableIterableIterator($collection, $callback);
            };
    }

    /**
     * @param T $left
     * @param T $right
     */
    private function compare($left, $right): int
    {
        return $left <=> $right;
    }
}
