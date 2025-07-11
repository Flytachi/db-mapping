<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Idx;

use Attribute;
use Flytachi\DbMapping\Constants\IndexMethod;
use Flytachi\DbMapping\Constants\IndexType;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Primary implements AttributeDbIdx
{
    private array $columns = [];

    public function columnPreparation(string $columnMain): void
    {
        if (!in_array($columnMain, $this->columns)) {
            array_unshift($this->columns, $columnMain);
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
