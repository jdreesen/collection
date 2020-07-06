<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use loophp\collection\Contract\Collection;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
interface Collapseable
{
    /**
     * Collapse a collection of items into a simple flat collection.
     *
     * @psalm-return Collection<TKey, T>
     */
    public function collapse(): Collection;
}
