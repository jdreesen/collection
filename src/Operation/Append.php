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
final class Append extends AbstractOperation implements Operation
{
    /**
     * Append constructor.
     *
     * @param U ...$items
     */
    public function __construct(...$items)
    {
        $this->storage['items'] = $items;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, U>):Generator<TKey|int, T|U>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param array<int, U> $items
             *
             * @return Generator<int|TKey, T|U>
             */
            static function (iterable $collection, array $items): Generator {
                foreach ($collection as $key => $value) {
                    yield $key => $value;
                }

                foreach ($items as $key => $item) {
                    yield $key => $item;
                }
            };
    }
}
