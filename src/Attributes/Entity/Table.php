<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Entity;

use Attribute;
use Flytachi\DbMapping\Attributes\AttributeDbEntity;

#[Attribute(Attribute::TARGET_CLASS)]
final class Table implements AttributeDbEntity
{
}
