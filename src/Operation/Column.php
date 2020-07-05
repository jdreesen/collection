<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Transformation\Run;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @template V
 * @implements Operation<TKey, T, \Generator<int, V>>
 */
final class Column extends AbstractOperation implements Operation
{
    /**
     * Column constructor.
     *
     * @param U $column
     */
    public function __construct($column)
    {
        $this->storage['column'] = $column;
    }

    /**
     * @return Closure(iterable<TKey, T>, U): Generator<int, V>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param U $column
             *
             * @return Generator<int, V>
             */
            static function (iterable $collection, $column): Generator {
                /**
                 * @psalm-var TKey $key
                 * @psalm-var T $value
                 */
                foreach ((new Run((new Transpose())))($collection) as $key => $value) {
                    if ($key === $column) {
                        return yield from $value;
                    }
                }
            };
    }
}
