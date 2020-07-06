<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Operation;
use loophp\collection\Contract\Transformation;
use loophp\collection\Iterator\ClosureIterator;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @template V
 * @implements Transformation<TKey, T, ClosureIterator>
 */
final class Run implements Transformation
{
    /**
     * @var array<int, Operation>
     */
    private $operations;

    public function __construct(Operation ...$operations)
    {
        $this->operations = $operations;
    }

    /**
     * @param iterable<TKey, T> $collection
     *
     * @return ClosureIterator|iterable<TKey, T>|U|V
     */
    public function __invoke(iterable $collection): ClosureIterator
    {
        return (
        new FoldLeft(
            /**
             * @param iterable<TKey, T> $collection
             */
            static function (iterable $collection, Operation $operation): ClosureIterator {
                return new ClosureIterator($operation(), $collection, ...array_values($operation->getArguments()));
            },
            $collection
        )
        )($this->operations);
    }
}
