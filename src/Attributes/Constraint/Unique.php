<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Constraint;

use Attribute;
use Flytachi\DbMapping\Constants\IndexMethod;
use Flytachi\DbMapping\Constants\IndexType;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Unique implements AttributeDbConstraint
{
    public function __construct(
        private readonly ?string $name = null,
        private array $columns = [],
        public IndexMethod $method = IndexMethod::BTREE,
    ) {
    }

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
            type: IndexType::UNIQUE,
            method: IndexMethod::BTREE,
        );
    }
}
