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
 * @implements Operation<TKey, T, Generator<TKey, T|list<T>>>
 */
final class Group extends AbstractOperation implements Operation
{
    /**
     * @param null|callable(TKey, T): (U) $callable
     */
    public function __construct(?callable $callable = null)
    {
        $this->storage['callable'] = $callable ??
            /**
             * @param TKey $key
             * @param T $value
             *
             * @return TKey|U
             */
            static function ($key, $value) {
                return $key;
            };
    }

    /**
     * @return Closure(iterable<TKey, T>, callable(TKey, T): U): Generator<U, list<T>|T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param iterable<TKey, T> $collection
             * @param callable(TKey, T): (U) $callable
             *
             * @return Generator<U, list<T>|T>
             */
            static function (iterable $collection, callable $callable): Generator {
                $data = [];

                foreach ($collection as $key => $value) {
                    $key = ($callable)($key, $value);

                    if (false === array_key_exists($key, $data)) {
                        $data[$key] = $value;

                        continue;
                    }

                    $data[$key] = (array) $data[$key];
                    $data[$key][] = $value;
                }

                return yield from $data;
            };
    }
}
