<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Hybrid;

use Attribute;
use Flytachi\DbMapping\Attributes\Idx\Primary;
use Flytachi\DbMapping\Attributes\Primal\Integer;
use Flytachi\DbMapping\Attributes\Sub\AutoIncrement;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Id implements AttributeDbHybrid
{
    public function getInstances(string $dialect = 'mysql'): array
    {
        return [
            new Primary(),
            new AutoIncrement(),
            new Integer()
        ];
    }
}
