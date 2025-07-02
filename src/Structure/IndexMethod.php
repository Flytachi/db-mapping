<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

enum IndexMethod: string
{
    case BTREE = 'BTREE';
    case HASH = 'HASH';
    case GIST = 'GIST';
    case GIN = 'GIN';
}