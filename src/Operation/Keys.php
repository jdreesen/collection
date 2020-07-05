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
 * @implements Operation<TKey, T, Generator<int, TKey>>
 */
final class Keys extends AbstractOperation implements Operation
{
    /**
     * @return Closure(iterable<TKey, T>): Generator<int, TKey>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<int, TKey>
             */
            static function (iterable $collection): Generator {
                foreach ($collection as $key => $value) {
                    yield $key;
                }
            };
    }
}
