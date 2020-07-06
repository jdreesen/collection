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
 * @template-implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class RSample extends AbstractOperation implements Operation
{
    public function __construct(float $probability)
    {
        $this->storage['probability'] = $probability;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, float):Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<TKey, T>
             */
            static function (iterable $collection, float $probability): Generator {
                yield from (new Run(
                    new Filter(
                        static function () use ($probability): bool {
                            return (mt_rand() / mt_getrandmax()) < $probability;
                        }
                    )
                ))($collection);
            };
    }
}
