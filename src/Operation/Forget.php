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
 * @template-implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Forget extends AbstractOperation implements Operation
{
    /**
     * Forget constructor.
     *
     * @param mixed ...$keys
     * @psalm-param U ...$keys
     */
    public function __construct(...$keys)
    {
        $this->storage['keys'] = $keys;
    }

    /**
     * @psalm-return Closure(iterable<TKey, T>, list<U>): \Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param list<U> $keys
             *
             * @param array<int, mixed> $keys
             *
             * @psalm-return \Generator<TKey, T>
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
