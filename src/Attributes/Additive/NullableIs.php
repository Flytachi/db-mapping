<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Additive;

use Attribute;
use Flytachi\DbMapping\Attributes\AttributeDb;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NullableIs implements AttributeDb
{
    public function __construct(
        private bool $isNullable = true,
    ) {
    }

    public function preparation(?bool &$nullable, ?string &$default): void
    {
        $nullable = $this->isNullable;
    }
}
