<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use InvalidArgumentException;
use loophp\collection\Contract\Operation;

/**
 * Class Intersperse.
 *
 * Insert a given value between each element of a collection.
 * Indices are not preserved.
 *
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @implements Operation<TKey, T, Generator<TKey, T|U>>
 */
final class Intersperse extends AbstractOperation implements Operation
{
    /**
     * Intersperse constructor.
     *
     * @param U $element
     */
    public function __construct($element, int $atEvery = 1, int $startAt = 0)
    {
        $this->storage = [
            'element' => $element,
            'atEvery' => $atEvery,
            'startAt' => $startAt,
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, U, int, int): Generator<int, T|U>
     */
    public function __invoke(): Closure
    {
        /** @psalm-var int $every */
        $every = $this->get('atEvery');
        /** @psalm-var int $startAt */
        $startAt = $this->get('startAt');

        if (0 > $every) {
            throw new InvalidArgumentException('The second parameter must be a positive integer.');
        }

        if (0 > $startAt) {
            throw new InvalidArgumentException('The third parameter must be a positive integer.');
        }

        return
            /**
             * @todo yield on $key and $value
             *
             * @param iterable<TKey, T> $collection
             * @param U $element
             *
             * @return Generator<int, T|U>
             */
            static function (iterable $collection, $element, int $every, int $startAt): Generator {
                foreach ($collection as $value) {
                    if (0 === $startAt++ % $every) {
                        yield $element;
                    }

                    yield $value;
                }
            };
    }
}
