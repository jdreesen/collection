<?php

declare(strict_types=1);

namespace loophp\collection\Transformation;

use loophp\collection\Contract\Transformation;
use stdClass;

use function is_callable;
use function is_string;

/**
 * @template TKey
 * @template TKey of array-key
 * @template T
 * @template U
 * @implements Transformation<TKey, T, bool>
 */
final class Contains implements Transformation
{
    /**
     * @var U
     */
    private $key;

    /**
     * Contains constructor.
     *
     * @param U $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param iterable<TKey, T> $collection
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
