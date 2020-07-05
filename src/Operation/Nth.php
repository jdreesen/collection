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
 * @implements Operation<TKey, T, Generator<int, T>>
 */
final class Nth extends AbstractOperation implements Operation
{
    public function __construct(int $step, int $offset)
    {
        $this->storage = [
            'step' => $step,
            'offset' => $offset,
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, int, int): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return /**
         * @return Generator<TKey, T>
         */
        static function (iterable $collection, int $step, int $offset): Generator {
            $position = 0;

            foreach ($collection as $key => $value) {
                if ($position++ % $step !== $offset) {
                    continue;
                }

                yield $key => $value;
            }
        };
    }
}
