<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use ArrayAccess;
use Closure;
use Generator;
use loophp\collection\Contract\Operation;
use loophp\collection\Transformation\Get;
use loophp\collection\Transformation\Run;
use ReflectionClass;
use ReflectionException;

use function array_key_exists;
use function in_array;
use function is_array;
use function is_object;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @phpstan-template V
 * @phpstan-template W
 * @template-implements Operation<TKey, T, Generator<int, W>>
 */
final class Pluck extends AbstractOperation implements Operation
{
    /**
     * Pluck constructor.
     *
     * @param array<int, string>|string $key
     * @param mixed $default
     * @psalm-param U $key
     * @psalm-param V $default
     */
    public function __construct($key, $default)
    {
        $this->storage = [
            'key' => $key,
            'default' => $default,
            'operation' => Closure::fromCallable([$this, 'pick']),
        ];
    }

    // phpcs:disable
    /**
     * @todo Fix this types
     * @psalm-return Closure(iterable<TKey, T>, string, V, callable(iterable<TKey, T>, T, string, V): (W)): Generator<TKey, T>
     */
    // phpcs:enable
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param iterable<TKey, T> $collection
             * @psalm-param U $key
             * @psalm-param V $default
             * @psalm-param callable(iterable<TKey, T>, T, U, V): (W) $pick
             *
             * @param array<int, string>|string $key
             * @param mixed $default
             *
             * @psalm-return \Generator<int, W>
             */
            static function (iterable $collection, $key, $default, callable $pick): Generator {
                /** @psalm-var array<int, string> $key */
                $key = true === is_scalar($key) ? explode('.', trim((string) $key, '.')) : $key;

                foreach ($collection as $value) {
                    yield $pick($collection, $value, $key, $default);
                }
            };
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param iterable<mixed> $collection
     * @psalm-param iterable<TKey, T> $collection
     *
     * @param mixed $target
     * @psalm-param T $target
     *
     * @param array<int, string> $key
     * @param mixed $default
     * @psalm-param V $default
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    private function pick(iterable $collection, $target, array $key, $default = null)
    {
        while (null !== $segment = array_shift($key)) {
            if ('*' === $segment) {
                if (false === is_iterable($target)) {
                    return $default;
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = $this->pick($collection, $item, $key);
                }

                return in_array('*', $key, true) ? (new Run((new Collapse())))($result) : $result;
            }

            if ((true === is_array($target)) && (true === array_key_exists($segment, $target))) {
                $target = $target[$segment];
            } elseif (($target instanceof ArrayAccess) && (true === $target->offsetExists($segment))) {
                $target = $target[$segment];
            } elseif (is_iterable($target)) {
                $target = (new Get($segment, $default))($target);
            } elseif ((true === is_object($target)) && (true === property_exists($target, $segment))) {
                $target = (new ReflectionClass($target))->getProperty($segment)->getValue($target);
            } else {
                $target = $default;
            }
        }

        return $target;
    }
}
