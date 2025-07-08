<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

use Flytachi\DbMapping\Constants\IndexMethod;
use Flytachi\DbMapping\Constants\IndexType;

class Index implements StructureInterface
{
    public function __construct(
        public array $columns,
        public ?string $name = null,
        public IndexType $type = IndexType::INDEX,
        public IndexMethod $method = IndexMethod::BTREE,
        public ?string $where = null,
        public array $includeColumns = [] // New property for PostgreSQL INCLUDE clause
    ) {
        if ($name) {
            NameValidator::validate($name);
        }
    }

    public function toSql(string $tableName, string $dialect = 'mysql'): string
    {
        $columnsSql = '(' . implode(', ', array_map(fn($col) => $col, $this->columns)) . ')';
        $name = $this->name ?: implode('_', $this->columns);
        $expName = explode('.', $tableName);
        $nameSql = (count($expName) > 1 ? $expName[1] : "{$tableName}") . "_{$name}";

        if ($dialect === 'mysql') {
            return match ($this->type) {
                IndexType::PRIMARY => "PRIMARY KEY {$columnsSql}",
                IndexType::UNIQUE => "CREATE UNIQUE INDEX {$nameSql}_udx ON {$tableName}"
                    . " {$columnsSql}  USING {$this->method->value}",
                IndexType::INDEX => "CREATE INDEX {$nameSql}_idx ON {$tableName}"
                    . " {$columnsSql} USING {$this->method->value}",
            };
        }

        if ($dialect === 'pgsql') {
            $methodSql = $this->method !== IndexMethod::BTREE ? "USING {$this->method->value}" : '';
            $whereSql = $this->where ? " WHERE {$this->where}" : '';
            $includeSql = !empty($this->includeColumns)
                ? ' INCLUDE (' . implode(', ', $this->includeColumns) . ')'
                : '';

            return match ($this->type) {
                IndexType::PRIMARY => "PRIMARY KEY {$columnsSql}",
                IndexType::UNIQUE => "CREATE UNIQUE INDEX IF NOT EXISTS {$nameSql}_udx {$methodSql} "
                    . "ON {$tableName} {$columnsSql}{$includeSql}{$whereSql}",
                IndexType::INDEX => "CREATE INDEX IF NOT EXISTS {$nameSql}_idx {$methodSql} "
                    . "ON {$tableName} {$columnsSql}{$includeSql}{$whereSql}",
            };
        }

        throw new \InvalidArgumentException("Unsupported dialect: {$dialect}");
    }
}
