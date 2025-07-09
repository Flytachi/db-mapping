<?php

namespace Flytachi\DbMapping\Attributes\Primal\Betta;

use Attribute;
use Flytachi\DbMapping\Attributes\Primal\AttributeDbType;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Blob implements AttributeDbType
{
    /**
     * @param 'default'|'tiny'|'medium'|'long' $size 'tiny', 'medium', 'long'
     */
    public function __construct(
        private string $size = 'default'
    ) {
        $allowedSizes = ['default', 'tiny', 'medium', 'long'];
        if (!in_array($this->size, $allowedSizes)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid BLOB size "%s". Allowed sizes are: %s',
                    $this->size,
                    implode(', ', $allowedSizes)
                )
            );
        }
    }

    public function supports(array $phpTypes): bool
    {
        $phpTypes = array_filter($phpTypes, fn($type) => $type !== 'null');
        if (
            count($phpTypes) === 1
            && in_array($phpTypes[0], ['mixed', 'string'])
        ) {
            return true;
        }
        return false;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return match ($dialect) {
            'mysql' => match ($this->size) {
                'tiny' => "TINYBLOB",
                'medium' => "MEDIUMBLOB",
                'long' => "LONGBLOB",
                default => "BLOB"
            },
            'pgsql' => "BYTEA",
            default => "BLOB"
        };
    }
}
