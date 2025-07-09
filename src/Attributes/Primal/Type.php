<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Type implements AttributeDbType
{
    public function __construct(
        private string $definition,
    ) {
    }

    public function supports(array $phpTypes): bool
    {
        return true;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return $this->definition;
    }
}
