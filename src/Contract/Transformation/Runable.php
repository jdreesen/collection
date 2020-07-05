<?php

declare(strict_types=1);

namespace loophp\collection\Contract\Transformation;

use loophp\collection\Contract\Collection;
use loophp\collection\Contract\Operation;

interface Runable
{
    public function run(Operation ...$operations): Collection;
}
