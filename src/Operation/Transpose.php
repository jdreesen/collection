<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;
use MultipleIterator;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, Generator<TKey, array<int, T>>>
 */
final class Transpose extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<TKey, array<int, T>>
     */
    public function __invoke(): Closure
    {
        return /**
         * @psalm-return \Generator<TKey, array<int, T>>
         */
        static function (iterable $collection): Generator {
            $mit = new MultipleIterator(MultipleIterator::MIT_NEED_ANY);

            foreach ($collection as $collectionItem) {
                $mit->attachIterator(new IterableIterator($collectionItem));
            }

            foreach ($mit as $key => $value) {
                yield current($key) => $value;
            }
        };
    }
}
