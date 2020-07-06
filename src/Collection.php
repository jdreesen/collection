<?php

declare(strict_types=1);

namespace loophp\collection;

use ArrayAccess;
use Exception;
use Generator;
use IteratorAggregate;
use loophp\collection\Contract\Collection as CollectionInterface;
use loophp\collection\Contract\Operation;
use loophp\collection\Contract\Transformation;
use loophp\collection\Iterator\ClosureIterator;
use loophp\collection\Iterator\IterableIterator;
use loophp\collection\Iterator\ResourceIterator;
use loophp\collection\Iterator\StringIterator;
use loophp\collection\Operation\Append;
use loophp\collection\Operation\Apply;
use loophp\collection\Operation\Chunk;
use loophp\collection\Operation\Collapse;
use loophp\collection\Operation\Column;
use loophp\collection\Operation\Combinate;
use loophp\collection\Operation\Combine;
use loophp\collection\Operation\Cycle;
use loophp\collection\Operation\Distinct;
use loophp\collection\Operation\Explode;
use loophp\collection\Operation\Filter;
use loophp\collection\Operation\Flatten;
use loophp\collection\Operation\Flip;
use loophp\collection\Operation\Forget;
use loophp\collection\Operation\Frequency;
use loophp\collection\Operation\Group;
use loophp\collection\Operation\Intersperse;
use loophp\collection\Operation\Iterate;
use loophp\collection\Operation\Keys;
use loophp\collection\Operation\Limit;
use loophp\collection\Operation\Loop;
use loophp\collection\Operation\Merge;
use loophp\collection\Operation\Normalize;
use loophp\collection\Operation\Nth;
use loophp\collection\Operation\Only;
use loophp\collection\Operation\Pad;
use loophp\collection\Operation\Permutate;
use loophp\collection\Operation\Pluck;
use loophp\collection\Operation\Prepend;
use loophp\collection\Operation\Product;
use loophp\collection\Operation\Range;
use loophp\collection\Operation\Reduction;
use loophp\collection\Operation\Reverse;
use loophp\collection\Operation\RSample;
use loophp\collection\Operation\Scale;
use loophp\collection\Operation\Shuffle;
use loophp\collection\Operation\Since;
use loophp\collection\Operation\Skip;
use loophp\collection\Operation\Slice;
use loophp\collection\Operation\Sort;
use loophp\collection\Operation\Split;
use loophp\collection\Operation\Tail;
use loophp\collection\Operation\Times;
use loophp\collection\Operation\Transpose;
use loophp\collection\Operation\Until;
use loophp\collection\Operation\Unwrap;
use loophp\collection\Operation\Walk;
use loophp\collection\Operation\Window;
use loophp\collection\Operation\Wrap;
use loophp\collection\Operation\Zip;
use loophp\collection\Transformation\All;
use loophp\collection\Transformation\Contains;
use loophp\collection\Transformation\Count;
use loophp\collection\Transformation\Falsy;
use loophp\collection\Transformation\First;
use loophp\collection\Transformation\FoldLeft;
use loophp\collection\Transformation\FoldRight;
use loophp\collection\Transformation\Get;
use loophp\collection\Transformation\Implode;
use loophp\collection\Transformation\Last;
use loophp\collection\Transformation\Nullsy;
use loophp\collection\Transformation\Reduce;
use loophp\collection\Transformation\Run;
use loophp\collection\Transformation\Transform;
use loophp\collection\Transformation\Truthy;
use StdClass;

use const INF;
use const PHP_INT_MAX;

/**
 * Class Collection.
 *
 * @implements IteratorAggregate<mixed, mixed>
 * @implements ArrayAccess<mixed, mixed>
 */
final class Collection implements ArrayAccess, CollectionInterface, IteratorAggregate
{
    public function all(): array
    {
        return $this->transform(new All());
    }

    public function append(...$items): CollectionInterface
    {
        return $this->run(new Append(...$items));
    }

    public function apply(callable ...$callables): CollectionInterface
    {
        return $this->run(new Apply(...$callables));
    }

    public function chunk(int ...$size): CollectionInterface
    {
        return $this->run(new Chunk(...$size));
    }

    public function collapse(): CollectionInterface
    {
        return $this->run(new Collapse());
    }

    public function column($column): CollectionInterface
    {
        return $this->run(new Column($column));
    }

    public function combinate(?int $length = null): CollectionInterface
    {
        return $this->run(new Combinate($length));
    }

    public function combine(...$keys): CollectionInterface
    {
        return $this->run(new Combine(...$keys));
    }

    public function contains($key): bool
    {
        return $this->transform(new Contains($key));
    }

    public function count(): int
    {
        return $this->transform(new Count());
    }

    public function cycle(?int $length = null): CollectionInterface
    {
        return $this->run(new Cycle($length));
    }

    public function distinct(): CollectionInterface
    {
        return $this->run(new Distinct());
    }

    public static function empty(): CollectionInterface
    {
        return self::fromIterable([]);
    }

    public function explode(...$explodes): CollectionInterface
    {
        return $this->run(new Explode(...$explodes));
    }

    public function falsy(): bool
    {
        return $this->transform(new Falsy());
    }

    public function filter(callable ...$callbacks): CollectionInterface
    {
        return $this->run(new Filter(...$callbacks));
    }

    public function first(?callable $callback = null, $default = null)
    {
        return $this->transform(new First($callback, $default));
    }

    public function flatten(int $depth = PHP_INT_MAX): CollectionInterface
    {
        return $this->run(new Flatten($depth));
    }

    public function flip(): CollectionInterface
    {
        return $this->run(new Flip());
    }

    public function foldLeft(callable $callback, $initial = null)
    {
        return $this->transform(new FoldLeft($callback, $initial));
    }

    public function foldRight(callable $callback, $initial = null)
    {
        return $this->transform(new FoldRight($callback, $initial));
    }

    public function forget(...$keys): CollectionInterface
    {
        return $this->run(new Forget(...$keys));
    }

    public function frequency(): CollectionInterface
    {
        return $this->run(new Frequency());
    }

    public static function fromCallable(callable $callable, ...$parameters): CollectionInterface
    {
        return new self(
            static function () use ($callable, $parameters): Generator {
                return yield from new ClosureIterator($callable, ...$parameters);
            }
        );
    }

    public static function fromIterable(iterable $iterable): CollectionInterface
    {
        return new self(
            static function () use ($iterable): Generator {
                return yield from new IterableIterator($iterable);
            }
        );
    }

    public static function fromResource($resource): CollectionInterface
    {
        return new self(
            static function () use ($resource): Generator {
                return yield from new ResourceIterator($resource);
            }
        );
    }

    public static function fromString(string $string, string $delimiter = ''): CollectionInterface
    {
        return new self(
            static function () use ($string, $delimiter): Generator {
                return yield from new StringIterator($string, $delimiter);
            }
        );
    }

    public function get($key, $default = null)
    {
        return $this->transform(new Get($key, $default));
    }

    public function getIterator(): ClosureIterator
    {
        return new ClosureIterator($this->source);
    }

    public function group(?callable $callable = null): CollectionInterface
    {
        return $this->run(new Group($callable));
    }

    public function implode(string $glue = ''): string
    {
        return $this->transform(new Implode($glue));
    }

    public function intersperse($element, int $every = 1, int $startAt = 0): CollectionInterface
    {
        return $this->run(new Intersperse($element, $every, $startAt));
    }

    public static function iterate(callable $callback, ...$parameters): CollectionInterface
    {
        return self::fromIterable([])->run(new Iterate($callback, $parameters));
    }

    public function keys(): CollectionInterface
    {
        return $this->run(new Keys());
    }

    public function last()
    {
        return $this->transform(new Last());
    }

    public function limit(int $limit): CollectionInterface
    {
        return $this->run(new Limit($limit));
    }

    public function loop(): CollectionInterface
    {
        return $this->run(new Loop());
    }

    public function map(callable ...$callbacks): CollectionInterface
    {
        return $this->run(new Walk(...$callbacks), new Normalize());
    }

    public function merge(iterable ...$sources): CollectionInterface
    {
        return $this->run(new Merge(...$sources));
    }

    public function normalize(): CollectionInterface
    {
        return $this->run(new Normalize());
    }

    public function nth(int $step, int $offset = 0): CollectionInterface
    {
        return $this->run(new Nth($step, $offset));
    }

    public function nullsy(): bool
    {
        return $this->transform(new Nullsy());
    }

    public function offsetExists($offset)
    {
        $default = new StdClass();

        return $this->get($offset, $default) !== $default;
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new Exception('Unexisting offset.');
        }

        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        throw new Exception('Not implemented.');
    }

    public function offsetUnset($offset)
    {
        throw new Exception('Not implemented.');
    }

    public function only(...$keys): CollectionInterface
    {
        return $this->run(new Only(...$keys));
    }

    public function pad(int $size, $value): CollectionInterface
    {
        return $this->run(new Pad($size, $value));
    }

    public function permutate(): CollectionInterface
    {
        return $this->run(new Permutate());
    }

    public function pluck($pluck, $default = null): CollectionInterface
    {
        return $this->run(new Pluck($pluck, $default));
    }

    public function prepend(...$items): CollectionInterface
    {
        return $this->run(new Prepend(...$items));
    }

    public function product(iterable ...$iterables): CollectionInterface
    {
        return $this->run(new Product(...$iterables));
    }

    public static function range(float $start = 0.0, float $end = INF, float $step = 1.0): CollectionInterface
    {
        return self::fromIterable([])->run(new Range($start, $end, $step));
    }

    public function reduce(callable $callback, $initial = null)
    {
        return $this->transform(new Reduce($callback, $initial));
    }

    public function reduction(callable $callback, $initial = null): CollectionInterface
    {
        return $this->run(new Reduction($callback, $initial));
    }

    public function reverse(): CollectionInterface
    {
        return $this->run(new Reverse());
    }

    public function rsample(float $probability): CollectionInterface
    {
        return $this->run(new RSample($probability));
    }

    public function run(Operation ...$operations): CollectionInterface
    {
        return self::fromIterable((new Run(...$operations))($this));
    }

    public function scale(
        float $lowerBound,
        float $upperBound,
        ?float $wantedLowerBound = null,
        ?float $wantedUpperBound = null,
        ?float $base = null
    ): CollectionInterface {
        return $this->run(new Scale($lowerBound, $upperBound, $wantedLowerBound, $wantedUpperBound, $base));
    }

    public function shuffle(): BaseInterface
    {
        return $this->run(new Shuffle());
    }

    public function since(callable ...$callbacks): CollectionInterface
    {
        return $this->run(new Since(...$callbacks));
    }

    public function skip(int ...$counts): CollectionInterface
    {
        return $this->run(new Skip(...$counts));
    }

    public function slice(int $offset, ?int $length = null): CollectionInterface
    {
        return $this->run(new Slice($offset, $length));
    }

    public function sort(?callable $callback = null): CollectionInterface
    {
        return $this->run(new Sort($callback));
    }

    public function split(callable ...$callbacks): CollectionInterface
    {
        return $this->run(new Split(...$callbacks));
    }

    public function tail(?int $length = null): CollectionInterface
    {
        return $this->run(new Tail($length));
    }

    public static function times(int $number = 0, ?callable $callback = null): CollectionInterface
    {
        return self::fromIterable([])->run(new Times($number, $callback));
    }

    public function transform(Transformation ...$transformers)
    {
        return (new Transform(...$transformers))($this);
    }

    public function transpose(): CollectionInterface
    {
        return $this->run(new Transpose());
    }

    public function truthy(): bool
    {
        return $this->transform(new Truthy());
    }

    public function until(callable ...$callbacks): CollectionInterface
    {
        return $this->run(new Until(...$callbacks));
    }

    public function unwrap(): CollectionInterface
    {
        return $this->run(new Unwrap());
    }

    public function walk(callable ...$callbacks): CollectionInterface
    {
        return $this->run(new Walk(...$callbacks));
    }

    public function window(int ...$length): CollectionInterface
    {
        return $this->run(new Window(...$length));
    }

    public static function with($data = [], ...$parameters): CollectionInterface
    {
        return new self($data, ...$parameters);
    }

    public function wrap(): CollectionInterface
    {
        return $this->run(new Wrap());
    }

    public function zip(iterable ...$iterables): CollectionInterface
    {
        return $this->run(new Zip(...$iterables));
    }
}
