<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Additive;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class DefaultVal implements AttributeDbAdditive
{
    public function __construct(
        private string $definition,
    ) {
    }

    public function preparation(?bool &$nullable, ?string &$default): void
    {
        $default = $this->definition;
    }
}
