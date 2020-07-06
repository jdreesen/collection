<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use loophp\collection\Contract\Collection;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
interface Chunkable
{
    /**
     * Chunk the collection into chunks of the given size.
     *
     * @param int ...$size
     * @psalm-return Collection<TKey, T>
     */
    public function chunk(int ...$size): Collection;
}
