<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template V
 * @template-implements Transformation<TKey, T, T|V|null>
 */
final class First implements Transformation
{
    /**
     * @var callable
     * @psalm-var callable(T, TKey): bool | callable(): bool
     */
    private $callback;

    /**
     * @var mixed|null
     * @psalm-var V|null
     */
    private $default;

    /**
     * First constructor.
     *
     * @param mixed|null $default
     *
     * @psalm-param null|callable(T, TKey): bool $callback
     * @psalm-param V|null $default
     */
    public function __construct(?callable $callback = null, $default = null)
    {
        $this->callback = $callback ??
            /**
             * @return true
             */
            static function (): bool {
                return true;
            };
        $this->default = $default;
    }

    /**
     * @psalm-param iterable<TKey, T> $collection
     *
     * @psalm-return T|V|null
     */
    public function __invoke(iterable $collection)
    {
        $callback = $this->callback;
        $default = $this->default;

        foreach ($collection as $key => $value) {
            if (true === $callback($value, $key)) {
                return $value;
            }
        }

        return $default;
    }
}
