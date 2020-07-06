<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Transformation<TKey, T, T>
 */
final class Last implements Transformation
{
    /**
     * @psalm-param iterable<TKey, T> $collection
     * @psalm-return T
     */
    public function __invoke(iterable $collection)
    {
        return (new FoldLeft(
            /**
             * @psalm-param null $carry
             * @psalm-param T $item
             *
             * @psalm-return T
             *
             * @param mixed $carry
             * @param mixed $item
             */
            static function ($carry, $item) {
                return $item;
            },
            null
        ))($collection);
    }
}
