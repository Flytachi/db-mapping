<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class FloatType implements AttributeDbType
{
    final public function supports(array $phpTypes): bool
    {
        $phpTypes = array_filter($phpTypes, fn($type) => $type !== 'null');
        if (
            count($phpTypes) === 1
            && in_array($phpTypes[0], ['mixed', 'int', 'float', 'string'])
        ) {
            return true;
        } elseif (
            count($phpTypes) > 1
        ) {
            $array = array_diff($phpTypes, ['int', 'float']);
            return empty($array);
        }
        return false;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return match ($dialect) {
            'mysql' => "FLOAT",
            'pgsql' => "REAL",
        };
    }
}
