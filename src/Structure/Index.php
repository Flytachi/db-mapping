<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

class Index implements StructureInterface
{
    public function __construct(
        public array $columns,
        public ?string $name = null,
        public IndexType $type = IndexType::INDEX,
        public IndexMethod $method = IndexMethod::BTREE,
        public ?string $where = null
    ) {}

    public function toSql(string $tableName, string $dialect = 'mysql'): string
    {
        $columnsSql = '(' . implode(', ', array_map(fn($col) => $col, $this->columns)) . ')';
        $name = $this->name ?: implode('_', $this->columns);
        $nameSql = "{$tableName}_{$name}";

        if ($dialect === 'mysql') {
            return match ($this->type) {
                IndexType::PRIMARY => "PRIMARY KEY {$columnsSql}",
                IndexType::UNIQUE => "UNIQUE KEY {$nameSql} {$columnsSql} USING {$this->method->value}",
                IndexType::INDEX => "KEY {$nameSql} {$columnsSql} USING {$this->method->value}",
            };
        }

        if ($dialect === 'pgsql') {
            $methodSql = $this->method !== IndexMethod::BTREE ? "USING {$this->method->value}" : '';
            $whereSql = $this->where ? " WHERE {$this->where}" : '';

            return match ($this->type) {
                IndexType::PRIMARY => "PRIMARY KEY {$columnsSql}",
                IndexType::UNIQUE => "UNIQUE {$columnsSql} {$methodSql}{$whereSql}",
                IndexType::INDEX => "INDEX {$nameSql} {$methodSql} ON {$tableName} {$columnsSql}{$whereSql}",
            };
        }

        throw new \InvalidArgumentException("Unsupported dialect: {$dialect}");
    }
}