<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class DbMap implements AttributeDb
{
}
