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
 * @implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Slice extends AbstractOperation implements Operation
{
    public function __construct(int $offset, ?int $length = null)
    {
        $this->storage = [
            'offset' => $offset,
            'length' => $length,
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, int, int|null): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection, int $offset, ?int $length): Generator {
                $skip = new Skip($offset);

                if (null === $length) {
                    return yield from (new Run($skip))($collection);
                }

                yield from (new Run($skip, new Limit($length)))($collection);
            };
    }
}
