<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Transformation\All;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @implements Operation<TKey, T, Generator<int, list<T>>>
 */
final class Permutate extends AbstractOperation implements Operation
{
    /**
     * @return Closure(iterable<TKey, T>): Generator<int, array<int, T>>
     */
    public function __invoke(): Closure
    {
        $getPermutations =
            /**
             * @param array<TKey, T> $dataset
             *
             * @return Generator<int, array<int, T>>
             */
            function (array $dataset): Generator {
                return $this->getPermutations($dataset);
            };

        return
            /**
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<int, array<int, T>>
             */
            static function (iterable $collection) use ($getPermutations): Generator {
                /** @psalm-var array<TKey, T> $all */
                $all = (new All())($collection);

                yield from $getPermutations($all);
            };
    }

    /**
     * @param array<mixed> $dataset
     *
     * @return Generator<int, array<int, T>>
     */
    private function getPermutations(array $dataset): Generator
    {
        foreach ($dataset as $key => $firstItem) {
            $remaining = $dataset;

            array_splice($remaining, $key, 1);

            if ([] === $remaining) {
                yield [$firstItem];

                continue;
            }

            foreach ($this->getPermutations($remaining) as $permutation) {
                array_unshift($permutation, $firstItem);

                yield $permutation;
            }
        }
    }
}
