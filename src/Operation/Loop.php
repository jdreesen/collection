<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use InfiniteIterator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Loop extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<TKey, T>
             */
            static function (iterable $collection): Generator {
                /**
                 * @psalm-var TKey $key
                 * @psalm-var T $value
                 */
                foreach (new InfiniteIterator(new IterableIterator($collection)) as $key => $value) {
                    yield $key => $value;
                }
            };
    }
}
