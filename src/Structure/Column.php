<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

class Column implements StructureInterface
{
    public function __construct(
        public string $name,
        public string $type,
        public bool $nullable = true,
        public ?string $default = null,
        /** @var Index[] */
        public array $indexes = [],
        public ?ForeignKey $foreignKey = null,
    ) {
    }

    public function toSql(string $tableName, string $dialect = 'mysql'): string
    {
        $parts = [
            $this->name,
            $this->type,
            $this->nullable ? '' : 'NOT NULL',
        ];

        if ($this->default !== null) {
            $parts[] = 'DEFAULT ' . $this->default;
        }

        return implode(' ', array_filter($parts));
    }


    public function constraintsSql(string $tableName, string $dialect = 'mysql'): array
    {
        $result = [];

        foreach ($this->indexes as $index) {
            $result[] = $index->toSql($tableName, $dialect);
        }

        if ($this->foreignKey) {
            $result[] = $this->foreignKey->toSql($tableName, $this->name, $dialect);
        }

        return $result;
    }
}
