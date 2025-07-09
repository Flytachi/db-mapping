<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Timestamp extends DateTime implements AttributeDbType
{
    /**
     * @param bool $withTimeZone If true, the timestamp will
     * include time zone information. Otherwise, it will be without time zone.
     */
    public function __construct(
        private bool $withTimeZone = true
    ) {
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        if ($this->withTimeZone) {
            return match ($dialect) {
                'pgsql' => "TIMESTAMP WITH TIME ZONE",
                'sqlite' => "DATETIME",
                default => "TIMESTAMP",
            };
        } else {
            return match ($dialect) {
                'pgsql' => "TIMESTAMP WITHOUT TIME ZONE",
                default => "DATETIME",
            };
        }
    }
}
