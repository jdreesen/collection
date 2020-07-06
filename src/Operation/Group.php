<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

use function array_key_exists;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @template-implements Operation<TKey, T, Generator<TKey, T|list<T>>>
 */
final class Group extends AbstractOperation implements Operation
{
    /**
     * @psalm-param null|callable(TKey, T): (U) $callable
     */
    public function __construct(?callable $callable = null)
    {
        $this->storage['callable'] = $callable ??
            /**
             * @param mixed $key
             * @param mixed $value
             *
             * @psalm-param TKey $key
             * @psalm-param T $value
             *
             * @return mixed
             * @psalm-return U|TKey
             */
            static function ($key, $value) {
                return $key;
            };
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, callable(TKey, T): U): Generator<U, list<T>|T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param callable(TKey, T): (U) $callable
             *
             * @psalm-return \Generator<U, list<T>|T>
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
