<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Operation<TKey, T, Generator<TKey, T>>
 */
final class Frequency extends AbstractOperation implements Operation
{
    /**
     * @psalm-return Closure(iterable<TKey, T>): Generator<int, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             *
             * @psalm-return \Generator<int, T>
             */
            static function (iterable $collection): Generator {
                $storage = [];

                foreach ($collection as $value) {
                    $added = false;

                    foreach ($storage as $key => $data) {
                        if ($data['value'] !== $value) {
                            continue;
                        }

                        ++$storage[$key]['count'];
                        $added = true;

                        break;
                    }

                    if (true === $added) {
                        continue;
                    }

                    $storage[] = [
                        'value' => $value,
                        'count' => 1,
                    ];
                }

                foreach ($storage as $value) {
                    yield $value['count'] => $value['value'];
                }
            };
    }
}
