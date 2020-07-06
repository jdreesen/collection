<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;
use loophp\collection\Operation\Reverse;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @phpstan-template V
 * @template-implements Transformation<TKey, T, V|U|null>
 */
final class FoldRight implements Transformation
{
    /**
     * @var callable
     * @psalm-var callable(U|V|null, T, TKey): V
     */
    private $callback;

    /**
     * @var mixed
     * @psalm-var U|null
     */
    private $initial;

    /**
     * FoldRight constructor.
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
     * @return mixed|null
     * @psalm-return V|U|null
     */
    public function __invoke(iterable $collection)
    {
        $callback = $this->callback;
        $initial = $this->initial;

        /**
         * @psalm-var TKey $key
         * @psalm-var T $value
         */
        foreach ((new Run(new Reverse()))($collection) as $key => $value) {
            $initial = $callback($initial, $value, $key);
        }

        return $initial;
    }
}
