<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Sub;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AutoIncrement implements AttributeDbSubType
{
    public function supports(array $phpTypes): bool
    {
        $phpTypes = array_filter($phpTypes, fn($type) => $type !== 'null');
        if (
            count($phpTypes) === 1
            && in_array($phpTypes[0], ['mixed', 'int'])
        ) {
            return true;
        }
        return false;
    }

    public function toSql(string $type, string $dialect = 'mysql'): string
    {
        if (!in_array($type, ['BIGINT', 'INT', 'SMALLINT'])) {
            throw new \RuntimeException(
                'AutoIncrement invalid type ' . $type
            );
        }
        return match ($dialect) {
            'pgsql' => $type . ' GENERATED ALWAYS AS IDENTITY',
            'mysql' => $type . ' AUTO_INCREMENT',
        };
    }
}
