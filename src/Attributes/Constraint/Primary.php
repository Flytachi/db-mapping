<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Constraint;

use Attribute;
use Flytachi\DbMapping\Constants\IndexMethod;
use Flytachi\DbMapping\Constants\IndexType;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Primary implements AttributeDbConstraint
{
    private array $columns = [];

    public function columnPreparation(string $columnMain): void
    {
        if (!in_array($columnMain, $this->columns)) {
            $this->columns[] = $columnMain;
        }
    }

    public function toObject(string $dialect = 'mysql'): \Flytachi\DbMapping\Structure\Index
    {
        return new \Flytachi\DbMapping\Structure\Index(
            columns: $this->columns,
            type: IndexType::PRIMARY,
            method: IndexMethod::BTREE,
        );
    }
}
