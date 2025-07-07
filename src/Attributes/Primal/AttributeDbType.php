<?php

namespace Flytachi\DbMapping\Attributes\Primal;

use Flytachi\DbMapping\Attributes\AttributeDb;

interface AttributeDbType extends AttributeDb
{
    public function supports(array $phpTypes): bool;
    public function toSql(string $dialect = 'mysql'): string;
}
