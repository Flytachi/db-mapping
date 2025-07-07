<?php

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Text implements AttributeDbType
{
    public function supports(array $phpTypes): bool
    {
        return true;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return 'TEXT';
    }
}
