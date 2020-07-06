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
 * @template-implements Operation<TKey, T, Generator<string, T>>
 */
final class Flip extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): \Generator<string, TKey>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<string, TKey>
             */
            static function (iterable $collection): Generator {
                foreach ($collection as $key => $value) {
                    yield (string) $value => $key;
                }
            };
    }
}
