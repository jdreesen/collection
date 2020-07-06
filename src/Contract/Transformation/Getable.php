<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Transformation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 */
interface Getable
{
    /**
     * Get an item by key.
     *
     * @param int|string $key
     * @param mixed $default
     * @psalm-param int|string|T $key
     * @psalm-param U $default
     *
     * @return mixed
     * @psalm-return U|T
     */
    public function get($key, $default = null);
}
