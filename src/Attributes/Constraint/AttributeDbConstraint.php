<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Constraint;

use Flytachi\DbMapping\Attributes\AttributeDb;
use Flytachi\DbMapping\Structure\ForeignKey;

interface AttributeDbConstraint extends AttributeDb
{
    public function toObject(string $dialect = 'mysql'): ForeignKey;
}
