<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @phpstan-template V
 * @phpstan-template W
 * @template-implements Operation<TKey, T, Generator<TKey, W>>
 */
final class Iterate extends AbstractOperation implements Operation
{
    /**
     * Iterate constructor.
     *
     * @psalm-param callable(V|W): W $callback
     * @psalm-param list<V> $parameters
     *
     * @param array<mixed> $parameters
     */
    public function __construct(callable $callback, array $parameters = [])
    {
        $this->storage = [
            'callback' => $callback,
            'parameters' => $parameters,
        ];
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, callable(V|W):W, list<V>): Generator<int, W>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @param array<mixed, mixed> $parameters
             *
             * @psalm-return \Generator<int, W>
             */
            static function (iterable $collection, callable $callback, array $parameters): Generator {
                while (true) {
                    yield $parameters = $callback(...array_values((array) $parameters));
                }
            };
    }
}
