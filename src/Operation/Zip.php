<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;
use MultipleIterator;

/**
 * @phpstan-template TKey
 * @phpstan-template UKey
 * @psalm-template TKey of array-key
 * @psalm-template UKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @template-implements Operation<TKey, T, \Generator<int, array<T, U>|false>>
 */
final class Zip extends AbstractOperation implements Operation
{
    /**
     * Zip constructor.
     *
     * @param iterable<mixed> ...$iterables
     */
    public function __construct(iterable ...$iterables)
    {
        $this->storage['iterables'] = $iterables;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, array<int, iterable<UKey, U>>): (false|Generator<int, array<T, U>>)
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, iterable> $iterables
             *
             * @psalm-return Generator<int, array<T, U>>
             */
            static function (iterable $collection, array $iterables): Generator {
                $mit = new MultipleIterator(MultipleIterator::MIT_NEED_ANY);
                $mit->attachIterator(new IterableIterator($collection));

                foreach ($iterables as $iterator) {
                    $mit->attachIterator(new IterableIterator($iterator));
                }

                /**
                 * @psalm-var T|U values
                 */
                foreach ($mit as $values) {
                    yield $values;
                }
            };
    }
}
