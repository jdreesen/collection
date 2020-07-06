<?php

declare(strict_types=1);

namespace loophp\collection\Iterator;

use Closure;
use Generator;
use Iterator;
use OuterIterator;

/**
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @phpstan-template T
 * @phpstan-template U
 * @implements Iterator<TKey, T>
 */
final class ClosureIterator extends ProxyIterator implements Iterator, OuterIterator
{
    /**
     * @var array<int, mixed>
     * @psalm-var array<int, U>
     */
    private $arguments;

    /**
     * @var callable
     * @psalm-var callable(U...): (\Generator<TKey, T>)
     */
    private $callable;

    /**
     * @var Closure
     * @psalm-var closure(callable(U...):(\Generator<TKey, T>), U): \Generator<TKey, T>
     */
    private $generator;

    /**
     * @psalm-param callable(U...): (\Generator<TKey, T>) $callable
     * @psalm-param U ...$arguments
     *
     * @param callable $callable
     * @param mixed ...$arguments
     */
    public function __construct(callable $callable, ...$arguments)
    {
        $this->callable = $callable;
        $this->arguments = $arguments;
        $this->generator = static function (callable $callable, array $arguments): Generator {
            return yield from ($callable)(...$arguments);
        };

        $this->iterator = $this->getGenerator();
    }

    public function rewind(): void
    {
        $this->iterator = $this->getGenerator();
    }

    /**
     * Init the generator if not initialized yet.
     *
     * @return Generator
     * @psalm-return Generator<TKey, T>
     */
    private function getGenerator(): Generator
    {
        return ($this->generator)($this->callable, $this->arguments);
    }
}
