<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Text implements AttributeDbType
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
