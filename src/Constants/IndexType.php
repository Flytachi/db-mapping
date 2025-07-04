<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Constants;

enum IndexType: string
{
    case PRIMARY = 'PRIMARY';
    case INDEX = 'INDEX';
    case UNIQUE = 'UNIQUE';
}
