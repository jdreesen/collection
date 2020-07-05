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
 * @template W
 * @implements Operation<TKey, T, Generator<TKey, W>>
 */
final class Iterate extends AbstractOperation implements Operation
{
    /**
     * Iterate constructor.
     *
     * @param callable(V|W): W $callback
     * @param list<V> $parameters
     */
    public function __construct(callable $callback, array $parameters = [])
    {
        $this->storage = [
            'callback' => $callback,
            'parameters' => $parameters,
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, callable(V|W):W, list<V>): Generator<int, W>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param array<mixed, mixed> $parameters
             *
             * @return Generator<int, W>
             */
            static function (iterable $collection, callable $callback, array $parameters): Generator {
                while (true) {
                    yield $parameters = $callback(...array_values((array) $parameters));
                }
            };
    }
}
