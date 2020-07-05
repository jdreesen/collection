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
 * @implements Operation<TKey, T, Generator<int, T>>
 */
final class Only extends AbstractOperation implements Operation
{
    /**
     * Only constructor.
     *
     * @param TKey ...$keys
     */
    public function __construct(...$keys)
    {
        $this->storage = [
            'keys' => $keys,
        ];
    }

    /**
     * @return Closure(iterable<TKey, T>, list<TKey>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return static function (iterable $collection, array $keys): Generator {
            if ([] === $keys) {
                return yield from $collection;
            }

            $keys = array_flip($keys);

            foreach ($collection as $key => $value) {
                if (true === array_key_exists($key, $keys)) {
                    yield $key => $value;
                }
            }
        };
    }
}
