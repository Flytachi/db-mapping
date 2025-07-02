<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

class CheckConstraint implements StructureInterface
{
    public function __construct(
        public string $expression,
        public ?string $name = null,
    ) {
        if ($name) {
            NameValidator::validate($name);
        }
    }

    public function toSql(string $tableName, string $dialect = 'mysql'): string
    {
        $constraintName = $this->name ?: "chk_{$tableName}_" . md5($this->expression);
        return "CONSTRAINT {$constraintName} CHECK ({$this->expression})";
    }
}
