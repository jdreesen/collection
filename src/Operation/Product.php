<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

use function count;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U of T
 * @template-implements Operation<TKey, T, Generator<int, array<int, T>>>
 */
final class Product extends AbstractOperation implements Operation
{
    /**
     * Product constructor.
     *
     * @param iterable<mixed> ...$iterables
     * @psalm-param iterable<TKey, T> ...$iterables
     */
    public function __construct(iterable ...$iterables)
    {
        $this->storage = [
            'iterables' => $iterables,
            'cartesian' =>
                /**
                 * @psalm-return \Generator<int, array<int, T>>
                 */
                function (array $input): Generator {
                    return $this->cartesian($input);
                },
        ];
    }

    // phpcs:disable
    /**
     * @psalm-return Closure(iterable<TKey, T>, array<int, iterable<TKey, T>>, callable(array<int, iterable<TKey, T>>): (array<int, T>)): Generator<int, array<int, T>>
     */
    // phpcs:enable
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param array<int, iterable<TKey, T>> $iterables
             * @psalm-param callable(array<int, iterable<TKey, T>>): (array<int, T>) $cartesian
             *
             * @param array<int, iterable> $iterables
             *
             * @psalm-return \Generator<mixed, mixed, mixed, void>
             */
            static function (iterable $collection, array $iterables, callable $cartesian): Generator {
                $its = [$collection];

                foreach ($iterables as $iterable) {
                    $its[] = new IterableIterator($iterable);
                }

                yield from $cartesian($its);
            };
    }

    /**
     * @psalm-param array<int, iterable<TKey, T>> $iterators
     *
     * @param array<iterable> $iterators
     *
     * @psalm-return Generator<int, array<int, T>>
     */
    private function cartesian(array $iterators): Generator
    {
        $iterator = array_pop($iterators);

        if (null === $iterator) {
            return yield [];
        }

        foreach ($this->cartesian($iterators) as $item) {
            foreach ($iterator as $value) {
                yield $item + [count($item) => $value];
            }
        }
    }
}
