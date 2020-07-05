<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

use function array_key_exists;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Forget extends AbstractOperation implements Operation
{
    /**
     * Forget constructor.
     *
     * @param U ...$keys
     */
    public function __construct(...$keys)
    {
        $this->storage['keys'] = $keys;
    }

    /**
     * @return Closure(iterable<TKey, T>, list<U>): \Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param list<U> $keys
             *
             * @return Generator<TKey, T>
             */
            static function (iterable $collection, array $keys): Generator {
                $keys = array_flip($keys);

                foreach ($collection as $key => $value) {
                    if (false === array_key_exists($key, $keys)) {
                        yield $key => $value;
                    }
                }
            };
    }
}
