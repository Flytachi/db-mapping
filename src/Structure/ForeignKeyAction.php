<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

enum ForeignKeyAction: string
{
    case RESTRICT = 'RESTRICT';
    case NO_ACTION = 'NO ACTION';
    case SET_DEFAULT = 'SET DEFAULT';
    case SET_NULL = 'SET NULL';
    case CASCADE = 'CASCADE';
}
