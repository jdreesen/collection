<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use InfiniteIterator;
use LimitIterator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Cycle extends AbstractOperation implements Operation
{
    public function __construct(?int $length = null)
    {
        $this->storage['length'] = $length ?? 0;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, int): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return /**
         * @psalm-return \Generator<TKey, T>
         */
            static function (iterable $collection, int $length): Generator {
                if (0 === $length) {
                    return yield from [];
                }

                $iterator = new LimitIterator(
                    new InfiniteIterator(
                        new IterableIterator($collection)
                    ),
                    0,
                    $length
                );

                /**
                 * @psalm-var TKey $key
                 * @psalm-var T $value
                 */
                foreach ($iterator as $key => $value) {
                    yield $key => $value;
                }
            };
    }
}
