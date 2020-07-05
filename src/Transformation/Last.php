<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Transformation<TKey, T, T>
 */
final class Last implements Transformation
{
    /**
     * @param iterable<TKey, T> $collection
     *
     * @return T
     */
    public function __invoke(iterable $collection)
    {
        return (new FoldLeft(
            /**
             * @param null $carry
             * @param T $item
             *
             * @return T
             */
            static function ($carry, $item) {
                return $item;
            },
            null
        ))($collection);
    }
}
