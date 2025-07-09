<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Double extends FloatType implements AttributeDbType
{
    public function toSql(string $dialect = 'mysql'): string
    {
        return match ($dialect) {
            'mysql' => "DOUBLE",
            'pgsql' => "DOUBLE PRECISION",
        };
    }
}
