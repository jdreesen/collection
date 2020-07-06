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
 * @phpstan-template U
 * @template-implements Operation<TKey, T, Generator<TKey|int, T|U>>
 */
final class Pad extends AbstractOperation implements Operation
{
    /**
     * Pad constructor.
     *
     * @param mixed $value
     * @psalm-param U $value
     */
    public function __construct(int $size, $value)
    {
        $this->storage = [
            'size' => $size,
            'value' => $value,
        ];
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, int, U):Generator<TKey|int, T|U>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param mixed $padValue
             *
             * @psalm-return \Generator<TKey|int, T|U>
             */
            static function (iterable $collection, int $size, $padValue): Generator {
                $y = 0;

                foreach ($collection as $key => $value) {
                    ++$y;

                    yield $key => $value;
                }

                while ($y++ < $size) {
                    yield $padValue;
                }
            };
    }
}
