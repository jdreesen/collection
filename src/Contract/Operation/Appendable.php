<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use loophp\collection\Contract\Collection;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
interface Appendable
{
    /**
     * Add one or more items to a collection.
     *
     * @param mixed ...$items
     * @psalm-param T ...$items
     *
     * @psalm-return Collection<TKey, T>
     */
    public function append(...$items): Collection;
}
