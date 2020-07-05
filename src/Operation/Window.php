<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;
use loophp\collection\Transformation\Run;
use Traversable;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, \Generator<TKey, list<T>>>
 */
final class Window extends AbstractOperation implements Operation
{
    public function __construct(int ...$length)
    {
        $this->storage['length'] = $length;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, int>): Generator<int, list<T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, int> $length
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<int, array<int, T>>
             */
            static function (iterable $collection, array $length): Generator {
                $i = 0;

                $length = new IterableIterator((new Run(new Loop()))($length));

                // Todo: Find a way to get rid of unused variable $value.
                foreach ($collection as $value) {
                    /** @var Traversable $traversable */
                    $traversable = (new Run(new Slice($i++, $length->current())))($collection);

                    yield iterator_to_array($traversable);

                    $length->next();
                }
            };
    }
}
