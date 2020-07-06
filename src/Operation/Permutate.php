<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Transformation\All;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @template-implements Operation<TKey, T, Generator<int, list<T>>>
 */
final class Permutate extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<int, array<int, T>>
     */
    public function __invoke(): Closure
    {
        $getPermutations =
            /**
             * @psalm-param array<TKey, T> $dataset
             *
             * @psalm-return \Generator<int, array<int, T>>
             */
            function (array $dataset): Generator {
                return $this->getPermutations($dataset);
            };

        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @return Generator
             *
             * @psalm-return \Generator<int, array<int, T>>
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
     * @psalm-return Generator<int, array<int, T>>
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
