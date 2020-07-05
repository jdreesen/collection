<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Transformation;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 */
interface Getable
{
    /**
     * Get an item by key.
     *
     * @param int|string|T $key
     * @param U $default
     *
     * @return T|U
     */
    public function get($key, $default = null);
}
