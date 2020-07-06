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
 * @template-implements Operation<TKey, T, Generator<int, TKey>>
 */
final class Keys extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<int, TKey>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<int, TKey>
             */
            static function (iterable $collection): Generator {
                foreach ($collection as $key => $value) {
                    yield $key;
                }
            };
    }
}
