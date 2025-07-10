<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Hybrid;

use Flytachi\DbMapping\Attributes\Additive\AttributeDbAdditive;
use Flytachi\DbMapping\Attributes\AttributeDb;
use Flytachi\DbMapping\Attributes\Idx\AttributeDbIdx;
use Flytachi\DbMapping\Attributes\Primal\AttributeDbType;
use Flytachi\DbMapping\Attributes\Sub\AttributeDbSubType;

interface AttributeDbHybrid extends AttributeDb
{
    /**
     * @param string $dialect
     * @return array<AttributeDbType|AttributeDbSubType|AttributeDbIdx|AttributeDbAdditive>
     */
    public function getInstances(string $dialect = 'mysql'): array;
}
