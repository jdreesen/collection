<?php

declare(strict_types=1);

namespace loophp\collection\Contract;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 */
interface Transformation
{
    /**
     * @param iterable<TKey, T> $collection
     *
     * @return U
     */
    public function __invoke(iterable $collection);
}
