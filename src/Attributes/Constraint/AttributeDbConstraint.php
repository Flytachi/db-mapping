<?php

namespace Flytachi\DbMapping\Attributes\Constraint;

use Flytachi\DbMapping\Attributes\AttributeDb;
use Flytachi\DbMapping\Structure\Index;

interface AttributeDbConstraint extends AttributeDb
{
    public function columnPreparation(string $columnMain): void;
    public function toObject(string $dialect = 'mysql'): Index;
}
