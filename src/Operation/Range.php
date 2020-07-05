<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

use const INF;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, Generator<int, float>>
 */
final class Range extends AbstractOperation implements Operation
{
    public function __construct(float $start = 0.0, float $end = INF, float $step = 1.0)
    {
        $this->storage = [
            'start' => $start,
            'end' => $end,
            'step' => $step,
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, float, float, float):Generator<int, float>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<int, float>
             */
            static function (iterable $collection, float $start, float $end, float $step): Generator {
                for ($current = $start; $current < $end; $current += $step) {
                    yield $current;
                }
            };
    }
}
