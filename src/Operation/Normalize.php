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
 * @template-implements Operation<TKey, T, Generator<int, T>>
 */
final class Normalize extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<int, T>
     */
    public function __invoke(): Closure
    {
        return /**
         * @psalm-return \Generator<int, T>
         */
        static function (iterable $collection): Generator {
            foreach ($collection as $value) {
                yield $value;
            }
        };
    }
}
