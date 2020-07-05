<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Transformation\All;
use loophp\collection\Transformation\Run;
use loophp\collection\Transformation\Transform;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Shuffle extends AbstractOperation implements Operation
{
    /**
     * @return Closure(iterable<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection): Generator {
                $data = (new Transform(new All()))((new Run(new Wrap()))($collection));

                while ([] !== $data) {
                    $randomKey = array_rand($data);
                    $randomValue = $data[$randomKey];
                    unset($data[$randomKey]);

                    yield key($randomValue) => current($randomValue);
                }
            };
    }
}
