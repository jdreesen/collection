<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U of T
 * @template-implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Append extends AbstractOperation implements Operation
{
    /**
     * Append constructor.
     *
     * @param mixed ...$items
     * @psalm-param U ...$items
     */
    public function __construct(...$items)
    {
        $this->storage['items'] = $items;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, array<int, U>):Generator<TKey|int, T|U>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, mixed> $items
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param array<int, U> $items
             *
             * @psalm-return \Generator<TKey|int, T|U>
             */
            static function (iterable $collection, array $items): Generator {
                foreach ($collection as $key => $value) {
                    yield $key => $value;
                }

                foreach ($items as $key => $item) {
                    yield $key => $item;
                }
            };
    }
}
