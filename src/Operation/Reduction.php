<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @template V
 * @implements Operation<TKey, T, Generator<int, V>>
 */
final class Reduction extends AbstractOperation implements Operation
{
    /**
     * Reduction constructor.
     *
     * @param callable(U|V, T, TKey): V $callback
     * @param U|null $initial
     */
    public function __construct(callable $callback, $initial = null)
    {
        $this->storage = [
            'callback' => $callback,
            'initial' => $initial,
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, callable(U|V, T, TKey): V, U): \Generator<int, V>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param U|null $initial
             *
             * @return \Generator<int, V, mixed, void>
             */
            static function (iterable $collection, callable $callback, $initial): Generator {
                foreach ($collection as $key => $value) {
                    yield $initial = $callback($initial, $value, $key);
                }
            };
    }
}
