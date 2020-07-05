<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use ArrayIterator;
use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Iterator\IterableIterator;

use const E_USER_WARNING;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Combine extends AbstractOperation implements Operation
{
    /**
     * Combine constructor.
     *
     * @param TKey ...$keys
     */
    public function __construct(...$keys)
    {
        $this->storage['keys'] = $keys;
    }

    /**
     * @return Closure(iterable<TKey, T>, list<TKey>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param list<TKey> $keys
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection, array $keys): Generator {
                $original = new IterableIterator($collection);
                $keysIterator = new ArrayIterator($keys);

                while ($original->valid() && $keysIterator->valid()) {
                    yield $keysIterator->current() => $original->current();

                    $original->next();
                    $keysIterator->next();
                }

                if ($original->valid() !== $keysIterator->valid()) {
                    trigger_error('Both keys and values must have the same amount of items.', E_USER_WARNING);
                }
            };
    }
}
