<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use loophp\collection\Contract\Collection;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template UKey
 * @psalm-template UKey of array-key
 * @psalm-template T
 */
interface Flipable
{
    /**
     * Flip keys and items in a collection.
     */
    public function flip(): Collection;
}
