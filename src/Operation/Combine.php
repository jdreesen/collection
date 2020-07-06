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
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Combine extends AbstractOperation implements Operation
{
    /**
     * Combine constructor.
     *
     * @param mixed ...$keys
     * @psalm-param TKey ...$keys
     */
    public function __construct(...$keys)
    {
        $this->storage['keys'] = $keys;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, list<TKey>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param list<TKey> $keys
             *
             * @psalm-return \Generator<TKey, T>
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
