<?php

declare(strict_types=1);

namespace loophp\collection\Contract;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 */
interface Transformation
{
    /**
     * @psalm-param iterable<TKey, T> $collection
     *
     * @psalm-return U
     */
    public function __invoke(iterable $collection);
}
