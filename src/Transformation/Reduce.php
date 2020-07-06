<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @phpstan-template V
 * @template-implements Transformation<TKey, T, U|null|V>
 */
final class Reduce implements Transformation
{
    /**
     * @var callable
     * @psalm-var callable(U|V|null, T, TKey): V $callback
     */
    private $callback;

    /**
     * @var mixed
     * @psalm-var U|null $initial
     */
    private $initial;

    /**
     * Reduce constructor.
     *
     * @param mixed|null $initial
     *
     * @psalm-param callable(U|V|null, T, TKey): V $callback
     * @psalm-param U|null $initial
     */
    public function __construct(callable $callback, $initial = null)
    {
        $this->callback = $callback;
        $this->initial = $initial;
    }

    /**
     * @psalm-param iterable<TKey, T> $collection
     *
     * @psalm-return U|null|V
     */
    public function __invoke(iterable $collection)
    {
        $callback = $this->callback;
        $initial = $this->initial;

        foreach ($collection as $key => $value) {
            $initial = $callback($initial, $value, $key);
        }

        return $initial;
    }
}
