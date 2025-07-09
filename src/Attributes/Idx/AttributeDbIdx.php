<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Idx;

use Flytachi\DbMapping\Attributes\AttributeDb;
use Flytachi\DbMapping\Structure\Index;

interface AttributeDbIdx extends AttributeDb
{
    public function columnPreparation(string $columnMain): void;
    public function toObject(string $dialect = 'mysql'): Index;
}
