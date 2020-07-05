<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use InvalidArgumentException;
use loophp\collection\Contract\Operation;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @implements Operation<TKey, T, Generator<TKey, int|U>>
 */
final class Times extends AbstractOperation implements Operation
{
    /**
     * Times constructor.
     */
    public function __construct(?int $number = null, ?callable $callback = null)
    {
        $number = $number ?? 0;

        if (1 > $number) {
            throw new InvalidArgumentException('Invalid parameter. $number must be greater than 1.');
        }

        $this->storage = [
            'number' => $number,
            'callback' => $callback ?? static function (int $value): int {
                return $value;
            },
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, float|int, callable(int): (U)): Generator<int, int|U>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param float|int $number
             *
             * @return \Generator<int, mixed, mixed, void>
             */
            static function (iterable $collection, $number, callable $callback): Generator {
                for ($current = 1; $current <= $number; ++$current) {
                    yield $callback($current);
                }
            };
    }
}
