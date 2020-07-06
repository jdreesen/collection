<?php

declare(strict_types=1);

namespace loophp\collection\Contract;

use ArrayAccess;
use IteratorAggregate;
use loophp\collection\Contract\Operation\Appendable;
use loophp\collection\Contract\Operation\Applyable;
use loophp\collection\Contract\Operation\Chunkable;
use loophp\collection\Contract\Operation\Collapseable;
use loophp\collection\Contract\Operation\Columnable;
use loophp\collection\Contract\Operation\Combinateable;
use loophp\collection\Contract\Operation\Combineable;
use loophp\collection\Contract\Operation\Cycleable;
use loophp\collection\Contract\Operation\Distinctable;
use loophp\collection\Contract\Operation\Explodeable;
use loophp\collection\Contract\Operation\Filterable;
use loophp\collection\Contract\Operation\Flattenable;
use loophp\collection\Contract\Operation\Flipable;
use loophp\collection\Contract\Operation\Forgetable;
use loophp\collection\Contract\Operation\Frequencyable;
use loophp\collection\Contract\Operation\Groupable;
use loophp\collection\Contract\Operation\Intersperseable;
use loophp\collection\Contract\Operation\Iterateable;
use loophp\collection\Contract\Operation\Keysable;
use loophp\collection\Contract\Operation\Limitable;
use loophp\collection\Contract\Operation\Loopable;
use loophp\collection\Contract\Operation\Mapable;
use loophp\collection\Contract\Operation\Mergeable;
use loophp\collection\Contract\Operation\Normalizeable;
use loophp\collection\Contract\Operation\Nthable;
use loophp\collection\Contract\Operation\Onlyable;
use loophp\collection\Contract\Operation\Padable;
use loophp\collection\Contract\Operation\Permutateable;
use loophp\collection\Contract\Operation\Pluckable;
use loophp\collection\Contract\Operation\Prependable;
use loophp\collection\Contract\Operation\Productable;
use loophp\collection\Contract\Operation\Rangeable;
use loophp\collection\Contract\Operation\Reductionable;
use loophp\collection\Contract\Operation\Reverseable;
use loophp\collection\Contract\Operation\RSampleable;
use loophp\collection\Contract\Operation\Scaleable;
use loophp\collection\Contract\Operation\Shuffleable;
use loophp\collection\Contract\Operation\Sinceable;
use loophp\collection\Contract\Operation\Skipable;
use loophp\collection\Contract\Operation\Sliceable;
use loophp\collection\Contract\Operation\Sortable;
use loophp\collection\Contract\Operation\Splitable;
use loophp\collection\Contract\Operation\Tailable;
use loophp\collection\Contract\Operation\Timesable;
use loophp\collection\Contract\Operation\Transposeable;
use loophp\collection\Contract\Operation\Untilable;
use loophp\collection\Contract\Operation\Unwrapable;
use loophp\collection\Contract\Operation\Walkable;
use loophp\collection\Contract\Operation\Windowable;
use loophp\collection\Contract\Operation\Wrapable;
use loophp\collection\Contract\Operation\Zipable;
use loophp\collection\Contract\Transformation\Allable;
use loophp\collection\Contract\Transformation\Containsable;
use loophp\collection\Contract\Transformation\Falsyable;
use loophp\collection\Contract\Transformation\Firstable;
use loophp\collection\Contract\Transformation\FoldLeftable;
use loophp\collection\Contract\Transformation\FoldRightable;
use loophp\collection\Contract\Transformation\Getable;
use loophp\collection\Contract\Transformation\Implodeable;
use loophp\collection\Contract\Transformation\Lastable;
use loophp\collection\Contract\Transformation\Nullsyable;
use loophp\collection\Contract\Transformation\Reduceable;
use loophp\collection\Contract\Transformation\Runable;
use loophp\collection\Contract\Transformation\Transformable;
use loophp\collection\Contract\Transformation\Truthyable;

/**
 * Interface Collection.
 *
 * @phpstan-template TKey
 * @psalm-template TKey of array-key
 * @psalm-template T
 * @template-extends \IteratorAggregate<TKey, T>
 * @template-extends \ArrayAccess<TKey|null, T>
 */
interface Collection extends
    Allable,
    Appendable,
    Applyable,
    ArrayAccess,
    Chunkable,
    Collapseable,
    Columnable,
    Combinateable,
    Combineable,
    Containsable,
    Cycleable,
    Distinctable,
    Explodeable,
    Falsyable,
    Filterable,
    Firstable,
    Flattenable,
    Flipable,
    FoldLeftable,
    FoldRightable,
    Forgetable,
    Frequencyable,
    Getable,
    Groupable,
    Implodeable,
    Intersperseable,
    Iterateable,
    IteratorAggregate,
    Keysable,
    Lastable,
    Limitable,
    Loopable,
    Mapable,
    Mergeable,
    Normalizeable,
    Nthable,
    Nullsyable,
    Onlyable,
    Padable,
    Permutateable,
    Pluckable,
    Prependable,
    Productable,
    Rangeable,
    Reduceable,
    Reductionable,
    Reverseable,
    RSampleable,
    Runable,
    Scaleable,
    Shuffleable,
    Sinceable,
    Skipable,
    Sliceable,
    Sortable,
    Splitable,
    Tailable,
    Timesable,
    Transformable,
    Transposeable,
    Truthyable,
    Untilable,
    Unwrapable,
    Walkable,
    Windowable,
    Wrapable,
    Zipable
{
    /**
     * Create a new instance with no items.
     */
    public static function empty(): Collection;

    /**
     * @param mixed ...$parameters
     *
     * @return \loophp\collection\Contract\Collection
     */
    public static function fromCallable(callable $callable, ...$parameters): Collection;

    /**
     * @param iterable<mixed> $iterable
     */
    public static function fromIterable(iterable $iterable): Collection;

    /**
     * @param resource $resource
     */
    public static function fromResource($resource): Collection;

    public static function fromString(string $string, string $delimiter = ''): Collection;

    /**
     * Create a collection with the data.
     *
     * @param mixed $data
     * @param mixed ...$parameters
     */
    public static function with($data = [], ...$parameters): Collection;
}
