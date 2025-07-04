<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes;

use Attribute;
use Flytachi\DbMapping\Constants\ForeignKeyAction;
use Flytachi\DbMapping\Structure\ForeignKey;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Foreign implements AttributeDb
{
    public function __construct(
        public string $referencedTable,
        public string $referencedColumn,
        public ForeignKeyAction $onUpdate = ForeignKeyAction::RESTRICT,
        public ForeignKeyAction $onDelete = ForeignKeyAction::RESTRICT,
        public ?string $name = null,
    ) {
    }

    public function toObject(string $dialect = 'mysql'): ForeignKey
    {
        return new ForeignKey(
            referencedTable: $this->referencedTable,
            referencedColumn: $this->referencedColumn,
            onUpdate: $this->onUpdate,
            onDelete: $this->onDelete,
        );
    }
}
