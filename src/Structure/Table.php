<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

class Table implements StructureInterface
{
    public function __construct(
        public string $name,
        /** @var Column[] */
        public array $columns,
        /** @var Index[] */
        public array $indexes = []
    ) {
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        $columnDefinitions = [];
        $constraints = [];
        $indexStatements = [];

        foreach ($this->columns as $column) {
            $columnDefinitions[] = '  ' . $column->toSql($this->name, $dialect);

            foreach ($column->constraintsSql($this->name, $dialect) as $constraint) {
                if ($dialect === 'pgsql' && str_starts_with($constraint, 'INDEX ')) {
                    // отложим для отдельного CREATE INDEX
                    $indexStatements[] = 'CREATE ' . $constraint . ';';
                } else {
                    $constraints[] = '  ' . $constraint;
                }
            }
        }

        foreach ($this->indexes as $index) {
            $sql = $index->toSql($this->name, $dialect);
            if ($dialect === 'pgsql' && str_starts_with($sql, 'INDEX ')) {
                $indexStatements[] = 'CREATE ' . $sql . ';';
            } else {
                $constraints[] = '  ' . $sql;
            }
        }

        $body = implode(",\n", array_merge($columnDefinitions, $constraints));
        $tableSql = sprintf("CREATE TABLE %s (\n%s\n);", $this->name, $body);

        if ($dialect === 'pgsql' && !empty($indexStatements)) {
            $tableSql .= "\n" . implode("\n", $indexStatements);
        }

        return $tableSql;
    }
}
