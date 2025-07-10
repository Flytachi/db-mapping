<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Hybrid;

use Attribute;
use Flytachi\DbMapping\Attributes\Additive\DefaultVal;
use Flytachi\DbMapping\Attributes\Additive\NullableIs;
use Flytachi\DbMapping\Attributes\Idx\Primary;
use Flytachi\DbMapping\Attributes\Primal\UuidBase;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Uuid implements AttributeDbHybrid
{
    public function getInstances(string $dialect = 'mysql'): array
    {
        return [
            new Primary(),
            new UuidBase(),
            new NullableIs(false),
            new DefaultVal($dialect === 'pgsql'
                ? "gen_random_uuid()"
                : "UUID()")
        ];
    }
}
