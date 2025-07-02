<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

enum IndexType: string
{
    case PRIMARY = 'PRIMARY';
    case INDEX = 'INDEX';
    case UNIQUE = 'UNIQUE';
}
