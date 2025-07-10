<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Constraint;

use Flytachi\DbMapping\Structure\ForeignKey;

interface AttributeDbConstraintForeign extends AttributeDbConstraint
{
    public function toObject(string $columnName, string $dialect = 'mysql'): ForeignKey;
}
