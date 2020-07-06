<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @template-implements Transformation<TKey, T, T|U>
 */
final class Get implements Transformation
{
    /**
     * @var mixed
     * @psalm-var U
     */
    private $default;

    /**
     * @var int|string
     */
    private $key;

    /**
     * Get constructor.
     *
     * @param int|string $key
     * @param mixed $default
     * @psalm-param TKey|int|string $key
     * @psalm-param U $default
     */
    public function __construct($key, $default)
    {
        $this->key = $key;
        $this->default = $default;
    }

    /**
     * @psalm-param iterable<TKey, T> $collection
     *
     * @psalm-return T|U
     *
     * @return mixed
     */
    public function __invoke(iterable $collection)
    {
        $keyToGet = $this->key;
        $default = $this->default;

        foreach ($collection as $key => $value) {
            if ($key === $keyToGet) {
                return $value;
            }
        }

        return $default;
    }
}
