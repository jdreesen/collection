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
 * @implements Operation<TKey, T, \Generator<TKey, T|list<T>>>
 */
final class Collapse extends AbstractOperation implements Operation
{
    /**
     * @return Closure(iterable<TKey, T>): Generator<int, T|list<T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<int, list<T>|T>
             */
            static function (iterable $collection): Generator {
                foreach ($collection as $value) {
                    if (true !== is_iterable($value)) {
                        continue;
                    }

                    /**
                     * @psalm-var T $subValue
                     */
                    foreach ($value as $subValue) {
                        yield $subValue;
                    }
                }
            };
    }
}
