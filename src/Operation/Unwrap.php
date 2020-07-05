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
 * @implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Unwrap extends AbstractOperation implements Operation
{
    /**
     * @return Closure(iterable<int, array<TKey, T>>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return static function (iterable $collection): Generator {
            foreach ($collection as $key => $value) {
                foreach ((array) $value as $k => $v) {
                    yield $k => $v;
                }
            }
        };
    }
}
