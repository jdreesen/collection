<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\SortableIterableIterator;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Sort extends AbstractOperation implements Operation
{
    public function __construct(?callable $callback = null)
    {
        $this->storage['callback'] = $callback ?? Closure::fromCallable([$this, 'compare']);
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, callable): Generator<TKey, \ArrayIterator>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-return \Generator<TKey, \ArrayIterator>
             */
            static function (iterable $collection, callable $callback): Generator {
                yield from new SortableIterableIterator($collection, $callback);
            };
    }

    /**
     * @param mixed $left
     * @param mixed $right
     */
    private function compare($left, $right): int
    {
        return $left <=> $right;
    }
}
