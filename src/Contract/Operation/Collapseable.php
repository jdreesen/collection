<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use loophp\collection\Contract\Collection;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 */
interface Collapseable
{
    /**
     * Collapse a collection of items into a simple flat collection.
     *
     * @return Collection<TKey, T>
     */
    public function collapse(): Collection;
}
