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
 * @phpstan-template U of T
 * @template-implements Operation<TKey, T, Generator<int, array<int, T>>>
 */
final class Explode extends AbstractOperation implements Operation
{
    /**
     * Explode constructor.
     *
     * @param mixed ...$explodes
     * @psalm-param U ...$explodes
     */
    public function __construct(...$explodes)
    {
        $this->storage['explodes'] = $explodes;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, array<int, U>): \Generator<int, array<int, T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param array<int, U> $explodes
             *
             * @psalm-return \Generator<int, array<int, T>>
             */
            static function (iterable $collection, array $explodes): Generator {
                yield from (new Run(
                    new Split(
                        ...array_map(
                            /**
                             * @param mixed $explode
                             * @psalm-param U $explode
                             */
                            static function ($explode) {
                                return
                                    /**
                                     * @param mixed $value
                                     * @psalm-param T $value
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
