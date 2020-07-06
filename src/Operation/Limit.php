<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use LimitIterator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Limit extends AbstractOperation implements Operation
{
    public function __construct(int $limit, int $offset = 0)
    {
        $this->storage = [
            'limit' => $limit,
            'offset' => $offset,
        ];
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, int, int): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<TKey, T>
             */
            static function (iterable $collection, int $limit, int $offset): Generator {
                yield from new LimitIterator(new IterableIterator($collection), $offset, $limit);
            };
    }
}
