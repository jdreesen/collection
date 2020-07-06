<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use CallbackFilterIterator;
use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Filter extends AbstractOperation implements Operation
{
    /**
     * @psalm-param callable(T, TKey, \Iterator): bool ...$callbacks
     *
     * @param callable ...$callbacks
     */
    public function __construct(callable ...$callbacks)
    {
        $this->storage['callbacks'] = $callbacks;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, list<callable(T, TKey, \Iterator):(bool)>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @param array<int, callable> $callbacks
             * @psalm-param list<callable(T, TKey, \Iterator): bool> $callbacks
             *
             * @psalm-return \Generator<TKey, T>
             */
            static function (iterable $collection, array $callbacks): Generator {
                $iterator = new IterableIterator($collection);

                foreach ($callbacks as $callback) {
                    $iterator = new CallbackFilterIterator($iterator, $callback);
                }

                yield from $iterator;
            };
    }
}
