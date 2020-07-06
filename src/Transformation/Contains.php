<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;
use stdClass;

use function is_callable;
use function is_string;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @template-implements Transformation<TKey, T, bool>
 */
final class Contains implements Transformation
{
    /**
     * @var mixed
     * @psalm-var U
     */
    private $key;

    /**
     * Contains constructor.
     *
     * @param mixed $key
     * @psalm-param U $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @psalm-param iterable<TKey, T> $collection
     *
     * @return bool
     */
    public function __invoke(iterable $collection)
    {
        $key = $this->key;

        if ((false === is_string($key)) && (true === is_callable($key))) {
            $placeholder = new stdClass();

            return (new First($key, $placeholder))($collection) !== $placeholder;
        }

        foreach ($collection as $value) {
            if ($value === $key) {
                return true;
            }
        }

        return false;
    }
}
