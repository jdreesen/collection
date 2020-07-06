<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use loophp\collection\Contract\Collection;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
interface Applyable
{
    /**
     * Execute a callback for each element of the collection.
     *
     * @psalm-param callable(T, TKey): bool ...$callables
     */
    public function apply(callable ...$callables): Collection;
}
