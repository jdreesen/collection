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
 * @template U of T
 * @implements Operation<TKey, T, Generator<int, array<int, T>>>
 */
final class Explode extends AbstractOperation implements Operation
{
    /**
     * Explode constructor.
     *
     * @param U ...$explodes
     */
    public function __construct(...$explodes)
    {
        $this->storage['explodes'] = $explodes;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, U>): \Generator<int, array<int, T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param array<int, U> $explodes
             *
             * @return Generator<int, array<int, T>>
             */
            static function (iterable $collection, array $explodes): Generator {
                yield from (new Run(
                    new Split(
                        ...array_map(
                            /**
                             * @param U $explode
                             */
                            static function ($explode) {
                                return
                                    /**
                                     * @param T $value
                                     */
                                    static function ($value) use ($explode): bool {
                                        return $value === $explode;
                                    };
                            },
                            $explodes
                        )
                    )
                ))($collection);
            };
    }
}
