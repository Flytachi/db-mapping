<?php

declare(strict_types=1);

namespace Flytachi\DbMapping;

use Flytachi\DbMapping\Structure\StoredProcedure;
use Flytachi\DbMapping\Structure\Table;
use Flytachi\DbMapping\Structure\Trigger;
use Flytachi\DbMapping\Structure\View;

interface DbMappingInterface
{
    public function getTableSql(Table $table): string;
    public function getViewSql(View $view): string;
    public function getStoredProcedureSql(StoredProcedure $sp): string;
    public function getTriggerSql(Trigger $trigger): string;
    public function createSchema(string $schemaName): string;
    public function dropSchema(string $schemaName): string;
}
