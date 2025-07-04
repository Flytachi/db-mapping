<?php

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Varchar implements AttributeDbType
{
    public function __construct(
        private int $length = 0,
    ) {
    }

    public function supports(array $phpTypes): bool
    {
        return true;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return 'VARCHAR' . ($this->length > 0 ? "({$this->length})" : '');
    }
}
