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
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, \Generator<TKey, list<T>>>
 */
final class Window extends AbstractOperation implements Operation
{
    public function __construct(int ...$length)
    {
        $this->storage['length'] = $length;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, array<int, int>): Generator<int, list<T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, int> $length
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<int, array<int, T>>
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
