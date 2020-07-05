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
 * @implements Operation<TKey, T, Generator<int, T>>
 */
final class Flatten extends AbstractOperation implements Operation
{
    public function __construct(int $depth)
    {
        $this->storage['depth'] = $depth;
    }

    /**
     * @return Closure(iterable<TKey, T>, int): Generator<int, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<int, T>
             */
            static function (iterable $collection, int $depth): Generator {
                foreach ($collection as $value) {
                    if (false === is_iterable($value)) {
                        yield $value;
                    } elseif (1 === $depth) {
                        /**
                         * @psalm-var T $subValue
                         */
                        foreach ($value as $subValue) {
                            yield $subValue;
                        }
                    } else {
                        /**
                         * @psalm-var T $subValue
                         */
                        foreach ((new Run(new Flatten($depth - 1)))($value) as $subValue) {
                            yield $subValue;
                        }
                    }
                }
            };
    }
}
