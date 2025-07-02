<?php

namespace Flytachi\DbMapping\Structure;

class ForeignKey implements StructureInterface
{
    public function __construct(
        public string $referencedTable,
        public string $referencedColumn,
        public ForeignKeyAction $onUpdate = ForeignKeyAction::RESTRICT,
        public ForeignKeyAction $onDelete = ForeignKeyAction::RESTRICT,
    ) {}

    public function toSql(string $tableName, string $columnName, string $dialect = 'mysql'): string
    {
        $constraintName = "fk_{$tableName}_{$columnName}";
        $onDelete = "ON DELETE " . $this->onDelete->value;
        $onUpdate = "ON UPDATE " . $this->onUpdate->value;

        return "CONSTRAINT {$constraintName} FOREIGN KEY ({$columnName}) REFERENCES {$this->referencedTable}({$this->referencedColumn}) {$onDelete} {$onUpdate}";
    }
}