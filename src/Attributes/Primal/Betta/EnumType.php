<?php

namespace Flytachi\DbMapping\Attributes\Primal\Betta;

use Attribute;
use BackedEnum;
use Flytachi\DbMapping\Attributes\Primal\AttributeDbType;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class EnumType implements AttributeDbType
{
    /**
     * @param class-string<BackedEnum> $enumClass
     */
    public function __construct(
        private string $enumClass
    ) {
        if (!enum_exists($this->enumClass)) {
            throw new InvalidArgumentException(sprintf('Class "%s" is not a valid enum.', $this->enumClass));
        }
        if (!is_subclass_of($this->enumClass, BackedEnum::class)) {
            throw new InvalidArgumentException(
                sprintf('Enum class "%s" must be a Backed Enum (string or int).', $this->enumClass)
            );
        }
    }

    public function supports(array $phpTypes): bool
    {
        $phpTypes = array_filter($phpTypes, fn($type) => $type !== 'null');
        // Поддерживает сам класс Enum, string (для строковых enum) или int (для целочисленных enum)
        if (
            count($phpTypes) === 1
            && (
                $phpTypes[0] === 'mixed'
                || $phpTypes[0] === $this->enumClass
                || (is_subclass_of($this->enumClass, BackedEnum::class)
                && in_array($phpTypes[0], ['string', 'int']))
            )
        ) {
            return true;
        } elseif (
            count($phpTypes) > 1
        ) {
            $backedType = $this->enumClass::cases()[0]->value;
            $allowedTypes = [$this->enumClass, gettype($backedType)];
            $array = array_diff($phpTypes, $allowedTypes);
            return empty($array);
        }
        return false;
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        $values = array_map(
            fn(BackedEnum $case) => "'" . addslashes((string)$case->value) . "'",
            $this->enumClass::cases()
        );
        $enumValues = implode(', ', $values);

        return match ($dialect) {
            'mysql' => "ENUM({$enumValues})",
            'pgsql' => $this->getPgsqlEnumSql($enumValues),
            default => "VARCHAR(255)"
        };
    }

    private function getPgsqlEnumSql(string $enumValues): string
    {
        return strtolower(str_replace('\\', '_', $this->enumClass)) . '_enum';
    }
}
