<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Transformation;

use loophp\collection\Contract\Collection;
use loophp\collection\Contract\Operation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 */
interface Runable
{
    /**
     * @param Operation ...$operations
     * @psalm-param Operation ...$operations
     *
     * @psalm-return Collection<TKey, T>
     */
    public function run(Operation ...$operations): Collection;
}
