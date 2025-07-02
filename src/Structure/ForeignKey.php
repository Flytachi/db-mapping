<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

class ForeignKey implements StructureInterface
{
    public function __construct(
        public string $referencedTable,
        public string $referencedColumn,
        public ForeignKeyAction $onUpdate = ForeignKeyAction::RESTRICT,
        public ForeignKeyAction $onDelete = ForeignKeyAction::RESTRICT,
        public ?string $name = null, // Add name property for the constraint
        public ?string $columnName = null // Add columnName property to link to the local column
    ) {
        if ($name) {
            NameValidator::validate($name);
        }
    }

    public function toSql(string $tableName, string $columnName, string $dialect = 'mysql'): string
    {
        $this->columnName = $columnName; // Ensure columnName is set when toSql is called
        $constraintName = $this->name ?: "fk_{$tableName}_{$columnName}";
        $onDelete = "ON DELETE " . $this->onDelete->value;
        $onUpdate = "ON UPDATE " . $this->onUpdate->value;

        return "CONSTRAINT {$constraintName} FOREIGN KEY ({$columnName})"
            . " REFERENCES {$this->referencedTable}({$this->referencedColumn}) {$onDelete} {$onUpdate}";
    }
}
