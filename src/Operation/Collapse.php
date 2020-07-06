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
 * @template-implements Operation<TKey, T, \Generator<TKey, T|list<T>>>
 */
final class Collapse extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<int, T|list<T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<int, T|list<T>>
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
