<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Constraint;

use Attribute;
use Flytachi\DbMapping\Constants\FKAction;
use Flytachi\DbMapping\DbMapRepoInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class ForeignRepo implements AttributeDbConstraintForeign
{
    /**
     * @param class-string<DbMapRepoInterface> $referencedRepoClass
     * @param FKAction $onUpdate
     * @param FKAction $onDelete
     * @param string|null $name
     */
    public function __construct(
        public string $referencedRepoClass,
        public FKAction $onUpdate = FKAction::RESTRICT,
        public FKAction $onDelete = FKAction::RESTRICT,
        public ?string $name = null,
    ) {
    }

    public function toObject(string $columnName, string $dialect = 'mysql'): \Flytachi\DbMapping\Structure\ForeignKey
    {
        $referencedRepoInstance = new $this->referencedRepoClass();

        if (!($referencedRepoInstance instanceof DbMapRepoInterface)) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" must implement DbMapRepoInterface.',
                $this->referencedRepoClass
            ));
        }

        return new \Flytachi\DbMapping\Structure\ForeignKey(
            referencedTable: $referencedRepoInstance->originTable(),
            referencedColumn: $referencedRepoInstance->mapIdentifierColumnName(),
            onUpdate: $this->onUpdate,
            onDelete: $this->onDelete,
        );
    }
}
