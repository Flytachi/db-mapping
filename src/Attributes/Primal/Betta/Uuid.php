<?php

namespace Flytachi\DbMapping\Attributes\Primal\Betta;

use Attribute;
use Flytachi\DbMapping\Attributes\Primal\AttributeDbType;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Uuid implements AttributeDbType
{
    /**
     * @param bool $asBinary Указывает, следует ли хранить UUID как BINARY(16) (более эффективно)
     *                       или как CHAR(36) (более читабельно).
     *                       По умолчанию false (хранить как CHAR(36) строку).
     */
    public function __construct(
        private bool $asBinary = false
    ) {
    }

    public function supports(array $phpTypes): bool
    {
        $phpTypes = array_filter($phpTypes, fn($type) => $type !== 'null');
        // UUID в PHP обычно представляется строкой (например, "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx")
        if (
            count($phpTypes) === 1
            && in_array($phpTypes[0], ['mixed', 'string'])
        ) {
            return true;
        }
        return false;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return match ($dialect) {
            // MySQL: BINARY(16) для эффективности, CHAR(36) для читабельности
            'mysql' => $this->asBinary ? "BINARY(16)" : "CHAR(36)",
            'pgsql' => "UUID", // PostgreSQL: Встроенный тип UUID
            'sqlite' => "TEXT", // SQLite: Хранит UUID как TEXT (строку)
            default => "CHAR(36)", // Запасной вариант
        };
    }
}
