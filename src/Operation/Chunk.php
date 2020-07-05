<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;
use loophp\collection\Transformation\Run;

use function count;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, \Generator<int, array<int, T>>>
 */
final class Chunk extends AbstractOperation implements Operation
{
    public function __construct(int ...$size)
    {
        $this->storage['size'] = $size;
    }

    /**
     * @return Closure(iterable<TKey, T>, array<int, int>): Generator<int, list<T>>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, int> $sizes
             *
             * @return Generator<int, list<T>>
             */
            static function (iterable $collection, array $sizes): Generator {
                $sizesIterator = new IterableIterator(
                    (new Run(new Loop()))($sizes)
                );

                $values = [];

                foreach ($collection as $value) {
                    if (0 >= $sizesIterator->current()) {
                        return yield from [];
                    }

                    if (count($values) !== $sizesIterator->current()) {
                        $values[] = $value;

                        continue;
                    }

                    $sizesIterator->next();

                    yield $values;

                    $values = [$value];
                }

                yield $values;
            };
    }
}
