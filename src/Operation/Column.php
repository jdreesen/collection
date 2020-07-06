<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Transformation\Run;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @phpstan-template V
 * @template-implements Operation<TKey, T, \Generator<int, V>>
 */
final class Column extends AbstractOperation implements Operation
{
    /**
     * Column constructor.
     *
     * @param int|string $column
     * @psalm-param  $column
     */
    public function __construct($column)
    {
        $this->storage['column'] = $column;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, U): Generator<int, V>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param int|string $column
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param U $column
             * @psalm-return \Generator<int, V>
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
