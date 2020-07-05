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
 * @implements Operation<TKey, T, Generator<int, T>>
 */
final class Normalize extends AbstractOperation implements Operation
{
    /**
     * @return Closure(iterable<TKey, T>): Generator<int, T>
     */
    public function __invoke(): Closure
    {
        return /**
         * @return Generator<int, T>
         */
        static function (iterable $collection): Generator {
            foreach ($collection as $value) {
                yield $value;
            }
        };
    }
}
