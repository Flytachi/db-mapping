<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Attributes\Primal;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Decimal extends FloatType implements AttributeDbType
{
    /**
     * @param int $precision The total number of digits that can be stored
     * both to the left and to the right of the decimal point.
     * @param int $scale The number of digits to the right of the decimal point.
     */
    public function __construct(
        private int $precision = 12,
        private int $scale = 2
    ) {
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return match ($dialect) {
            'mysql' => "DECIMAL({$this->precision}, {$this->scale})",
            'pgsql' => "NUMERIC({$this->precision}, {$this->scale})",
        };
    }
}
