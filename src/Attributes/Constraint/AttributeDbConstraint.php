<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Constraint;

use Flytachi\DbMapping\Attributes\AttributeDb;
use Flytachi\DbMapping\Structure\StructureInterface;

interface AttributeDbConstraint extends AttributeDb
{
    public function toObject(string $columnName, string $dialect = 'mysql'): StructureInterface;
}
