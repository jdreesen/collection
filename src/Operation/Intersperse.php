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
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @template-implements Operation<TKey, T, Generator<TKey, T|U>>
 */
final class Intersperse extends AbstractOperation implements Operation
{
    /**
     * Intersperse constructor.
     *
     * @param mixed $element
     * @psalm-param U $element
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
     * @psalm-return Closure(iterable<TKey, T>, U, int, int): Generator<int, T|U>
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
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param U $element
             *
             * @param mixed $element
             *
             * @psalm-return \Generator<int, T|U>
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
