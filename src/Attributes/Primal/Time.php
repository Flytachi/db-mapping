<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Time extends DateTime implements AttributeDbType
{
    public function toSql(string $dialect = 'mysql'): string
    {
        return 'TIME';
    }
}
