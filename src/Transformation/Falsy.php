<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @template-implements Transformation<TKey, T, bool>
 */
final class Falsy implements Transformation
{
    /**
     * @psalm-param iterable<TKey, T> $collection
     * @psalm-return bool
     */
    public function __invoke(iterable $collection): bool
    {
        foreach ($collection as $key => $value) {
            if (true === (bool) $value) {
                return false;
            }
        }

        return true;
    }
}
