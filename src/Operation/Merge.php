<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Merge extends AbstractOperation implements Operation
{
    /**
     * Merge constructor.
     *
     * @param iterable<TKey, T> ...$sources
     */
    public function __construct(iterable ...$sources)
    {
        $this->storage['sources'] = $sources;
    }

    /**
     * @return Closure(iterable<TKey, T>, list<iterable<TKey, T>>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param list<iterable<TKey, T>> $sources
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection, array $sources): Generator {
                foreach ($collection as $key => $value) {
                    yield $key => $value;
                }

                foreach ($sources as $source) {
                    foreach ($source as $key => $value) {
                        yield $key => $value;
                    }
                }
            };
    }
}
