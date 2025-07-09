<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Additive;

use Flytachi\DbMapping\Attributes\AttributeDb;

interface AttributeDbAdditive extends AttributeDb
{
    public function preparation(?bool &$nullable, ?string &$default): void;
}
