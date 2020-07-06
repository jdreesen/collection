<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

use function in_array;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, \Generator<TKey, T>>
 */
final class Distinct extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-return \Generator<TKey, T>
             */
            static function (iterable $collection): Generator {
                $seen = [];

                foreach ($collection as $key => $value) {
                    if (true === in_array($value, $seen, true)) {
                        continue;
                    }

                    $seen[] = $value;

                    yield $key => $value;
                }
            };
    }
}
