<?php

declare(strict_types=1);

namespace Flytachi\DbMapping;

use Flytachi\DbMapping\Structure\StoredProcedure;
use Flytachi\DbMapping\Structure\Table;
use Flytachi\DbMapping\Structure\Trigger;
use Flytachi\DbMapping\Structure\View;

readonly class DbMappingBetta implements DbMappingInterface
{
    public function __construct(
        public string $dialect = 'mysql',
    ) {
    }

    public function getTableSql(Table $table): string
    {
        return $table->toSql($this->dialect);
    }

    public function getViewSql(View $view): string
    {
        return $view->toSql($this->dialect);
    }

    public function getStoredProcedureSql(StoredProcedure $sp): string
    {
        return $sp->toSql($this->dialect);
    }

    public function getTriggerSql(Trigger $trigger): string
    {
        return $trigger->toSql($this->dialect);
    }

    public function createSchema(string $schemaName): string
    {
        if ($this->dialect === 'pgsql') {
            return "CREATE SCHEMA {$schemaName};";
        } else {
            return "CREATE DATABASE {$schemaName};";
        }
    }

    public function dropSchema(string $schemaName): string
    {
        if ($this->dialect === 'pgsql') {
            return "DROP SCHEMA {$schemaName};";
        } else {
            return "DROP DATABASE {$schemaName};";
        }
    }
}
