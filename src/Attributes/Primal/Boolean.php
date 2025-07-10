<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Boolean implements AttributeDbType
{
    public function supports(array $phpTypes): bool
    {
        $phpTypes = array_filter($phpTypes, fn($type) => $type !== 'null');
        if (
            count($phpTypes) === 1
            && in_array($phpTypes[0], ['mixed', 'bool', 'int', 'string'])
        ) {
            return true;
        }
        return false;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return 'BOOLEAN';
    }
}
