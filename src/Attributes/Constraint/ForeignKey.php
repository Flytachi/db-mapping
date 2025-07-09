<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Constraint;

use Attribute;
use Flytachi\DbMapping\Constants\FKAction;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ForeignKey implements AttributeDbConstraint
{
    public function __construct(
        public string $referencedTable,
        public string $referencedColumn,
        public FKAction $onUpdate = FKAction::RESTRICT,
        public FKAction $onDelete = FKAction::RESTRICT,
        public ?string $name = null,
    ) {
    }

    public function toObject(string $dialect = 'mysql'): \Flytachi\DbMapping\Structure\ForeignKey
    {
        return new \Flytachi\DbMapping\Structure\ForeignKey(
            referencedTable: $this->referencedTable,
            referencedColumn: $this->referencedColumn,
            onUpdate: $this->onUpdate,
            onDelete: $this->onDelete,
        );
    }
}
