<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

class Table implements StructureInterface
{
    /** @var Column[] */
    public array $columns;
    /** @var Index[] */
    public array $indexes;
    /** @var CheckConstraint[] */
    public array $checks;
    /** @var ForeignKey[] */
    public array $foreignKeys;

    public function __construct(
        public string $name,
        array $columns,
        array $indexes = [],
        array $checks = [],
        array $foreignKeys = [],
        public ?string $schema = null
    ) {
        NameValidator::validate($name);
        if ($schema) {
            NameValidator::validate($schema);
        }
        $this->columns = $columns;
        $this->indexes = $indexes;
        $this->checks = $checks;
        $this->foreignKeys = $foreignKeys;
    }

    public function getFullName(): string
    {
        return $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        $tableName = $this->getFullName();
        $columnDefinitions = [];
        $constraints = [];
        $indexStatements = [];

        foreach ($this->columns as $column) {
            $columnDefinitions[] = '  ' . $column->toSql($tableName, $dialect);

            foreach ($column->constraintsSql($tableName, $dialect) as $constraint) {
                if (
                    str_starts_with($constraint, 'CREATE ')
                    || str_starts_with($constraint, 'ALTER TABLE')
                ) {
                    // отложим для отдельного CREATE
                    $indexStatements[] = $constraint . ';';
                } else {
                    $constraints[] = '  ' . $constraint;
                }
            }
        }

        foreach ($this->indexes as $index) {
            $sql = $index->toSql($tableName, $dialect);
            if ($dialect === 'pgsql' && str_starts_with($sql, 'INDEX ')) {
                $indexStatements[] = 'CREATE ' . $sql . ';';
            } else {
                $constraints[] = '  ' . $sql;
            }
        }

        foreach ($this->foreignKeys as $foreignKey) {
            // Assuming the ForeignKey object now holds the column name, it applies to
            // This requires a change in ForeignKey class or how it's used.
            // For now, let's assume ForeignKey has a property `columnName`
            $constraints[] = '  ' . $foreignKey->toSql($tableName, $foreignKey->columnName, $dialect);
        }

        foreach ($this->checks as $check) {
            $constraints[] = '  ' . $check->toSql($tableName, $dialect);
        }

        $constraints = $this->mergePrimaryKeys($constraints);
        $body = implode(",\n", array_merge($columnDefinitions, $constraints));
        $tableSql = sprintf("CREATE TABLE IF NOT EXISTS %s (\n%s\n);", $tableName, $body);

        if (!empty($indexStatements)) {
            $tableSql .= "\n" . implode("\n", $indexStatements);
        }

        return $tableSql;
    }

    private function mergePrimaryKeys(array $constraints): array
    {
        $primaryColumns = [];
        $otherConstraints = [];

        foreach ($constraints as $line) {
            if (preg_match('/PRIMARY KEY\s*\((.*?)\)/i', $line, $matches)) {
                $columns = array_map('trim', explode(',', $matches[1]));
                $primaryColumns = array_merge($primaryColumns, $columns);
            } else {
                $otherConstraints[] = $line;
            }
        }

        if (!empty($primaryColumns)) {
            $primaryLine = '  PRIMARY KEY (' . implode(', ', array_unique($primaryColumns)) . ')';
            array_unshift($otherConstraints, $primaryLine);
        }

        return $otherConstraints;
    }

    public function createSchemaIfNotExists(string $dialect = 'mysql'): ?string
    {
        if (empty($this->schema)) {
            return null;
        }

        return match ($dialect) {
            'pgsql' => "CREATE SCHEMA IF NOT EXISTS {$this->schema};",
            default => null
        };
    }

    public function addColumn(Column $column, string $dialect = 'mysql'): string
    {
        $this->columns[] = $column;
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        return sprintf("ALTER TABLE %s ADD COLUMN %s;", $tableName, $column->toSql($this->name, $dialect));
    }

    public function dropColumn(string $columnName): string
    {
        $this->columns = array_filter($this->columns, fn($col) => $col->name !== $columnName);
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        return sprintf("ALTER TABLE %s DROP COLUMN %s;", $tableName, $columnName);
    }

    public function addIndex(Index $index, string $dialect = 'mysql'): string
    {
        $this->indexes[] = $index;
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        $sql = $index->toSql($this->name, $dialect);
        if ($dialect === 'pgsql' && str_starts_with($sql, 'INDEX ')) {
            return 'CREATE ' . $sql . ';';
        } else {
            return sprintf("ALTER TABLE %s ADD %s;", $tableName, $sql);
        }
    }

    public function dropIndex(string $indexName, string $dialect = 'mysql'): string
    {
        $this->indexes = array_filter($this->indexes, fn($idx) => $idx->name !== $indexName);
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        if ($dialect === 'mysql') {
            return sprintf("ALTER TABLE %s DROP INDEX %s;", $tableName, $indexName);
        } elseif ($dialect === 'pgsql') {
            return sprintf("DROP INDEX %s;", $indexName);
        } else {
            throw new \InvalidArgumentException("Unsupported dialect: {$dialect}");
        }
    }

    public function addForeignKey(ForeignKey $foreignKey, string $dialect = 'mysql'): string
    {
        $this->foreignKeys[] = $foreignKey;
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        return sprintf("ALTER TABLE %s ADD %s;", $tableName, $foreignKey->toSql($this->name, $foreignKey->columnName, $dialect));
    }

    public function dropForeignKey(string $constraintName, string $dialect = 'mysql'): string
    {
        $this->foreignKeys = array_filter($this->foreignKeys, fn($fk) => $fk->name !== $constraintName); // Assuming ForeignKey has a name property
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        if ($dialect === 'mysql') {
            return sprintf("ALTER TABLE %s DROP FOREIGN KEY %s;", $tableName, $constraintName);
        } elseif ($dialect === 'pgsql') {
            return sprintf("ALTER TABLE %s DROP CONSTRAINT %s;", $tableName, $constraintName);
        } else {
            throw new \InvalidArgumentException("Unsupported dialect: {$dialect}");
        }
    }

    public function addCheckConstraint(CheckConstraint $check, string $dialect = 'mysql'): string
    {
        $this->checks[] = $check;
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        return sprintf("ALTER TABLE %s ADD %s;", $tableName, $check->toSql($this->name, $dialect));
    }

    public function dropCheckConstraint(string $checkName, string $dialect = 'mysql'): string
    {
        $this->checks = array_filter($this->checks, fn($chk) => $chk->name !== $checkName);
        $tableName = $this->schema ? "{$this->schema}.{$this->name}" : $this->name;
        if ($dialect === 'mysql' || $dialect === 'pgsql') {
            return sprintf("ALTER TABLE %s DROP CONSTRAINT %s;", $tableName, $checkName);
        } else {
            throw new \InvalidArgumentException("Unsupported dialect: {$dialect}");
        }
    }
}
