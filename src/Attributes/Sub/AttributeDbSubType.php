<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Sub;

use Flytachi\DbMapping\Attributes\AttributeDb;

interface AttributeDbSubType extends AttributeDb
{
    public function supports(array &$phpTypes): bool;
    public function toSql(string $type, string $dialect = 'mysql'): string;
}
