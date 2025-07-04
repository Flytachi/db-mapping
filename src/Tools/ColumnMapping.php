<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Tools;

use Flytachi\DbMapping\Attributes\AttributeDb;
use Flytachi\DbMapping\Attributes\Constraint\AttributeDbConstraint;
use Flytachi\DbMapping\Attributes\Constraint\Index;
use Flytachi\DbMapping\Attributes\Constraint\Primary;
use Flytachi\DbMapping\Attributes\Foreign;
use Flytachi\DbMapping\Attributes\Primal\AttributeDbType;
use Flytachi\DbMapping\Structure\Column;
use Flytachi\DbMapping\Structure\ForeignKey;
use ReflectionAttribute;
use ReflectionProperty;

final class ColumnMapping
{
    /** @var Column[]  */
    private array $columns = [];

    public function __construct(
        private string $dialect = 'mysql'
    ) {
    }

    public function push(ReflectionProperty $property): void
    {
        $this->columns[] = $this->toColumn($property);
    }

    private function toColumn(ReflectionProperty $property): Column
    {
        /** @var Index[] $indexes */
        $indexes = [];
        /** @var ?ForeignKey $foreignKey */
        $foreignKey = null;
        /** @var ?AttributeDbType $attributeType */
        $attributeType = null;

        $types = $this->checkingType($property);

        foreach ($property->getAttributes(AttributeDb::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $attributeObj = $attribute->newInstance();
            if ($attributeObj instanceof AttributeDbType) {
                if (!$attributeObj->supports($types)) {
                    throw new \InvalidArgumentException(
                        $property->getName() . " in " . $property->getDeclaringClass()->getName() . " "
                        . $attribute->getName()
                        . " does not support this type: " . json_encode($types)
                    );
                };
                if ($attributeType !== null) {
                    throw new \RuntimeException(
                        $property->getName() . " in " . $property->getDeclaringClass()->getName() . " "
                        . $attribute->getName()
                        . " is already set to " . $attributeType::class
                    );
                }
                $attributeType = $attributeObj;
            } elseif ($attributeObj instanceof AttributeDbConstraint) {
                $attributeObj->columnPreparation($property->getName());
                if ($attributeObj instanceof Primary) {
                    $types = array_filter($types, fn($type) => $type !== 'null');
                }
                $indexes[] = $attributeObj->toObject($this->dialect);
            } elseif ($attributeObj instanceof Foreign) {
                $foreignKey = $attributeObj->toObject($this->dialect);
            }
        }

        return new Column(
            name: $property->getName(),
            type: empty($attributeType)
                ? Column::getPrimitiveSqlType($types, $this->dialect)
                : $attributeType->toSql($this->dialect),
            nullable: in_array('null', $types),
            default: ($property->hasDefaultValue()
                ? ($property->isDefault()
                    ? match (getType($property->getDefaultValue())) {
                        'NULL' => in_array('null', $types)
                            ? 'NULL'
                            : null,
                        'boolean' => $property->getDefaultValue() ? 'TRUE' : 'FALSE',
                        'string' => "'{$property->getDefaultValue()}'",
                        default => "{$property->getDefaultValue()}"
                    }
                    : null
                )
                : null),
            indexes: $indexes,
            foreignKey: $foreignKey
        );
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    private function checkingType(ReflectionProperty $property): array
    {
        $types = [];
        if ($property->hasType()) {
            $type = $property->getType();

            if ($type instanceof \ReflectionNamedType) {
                $types[] = $type->getName();
                if ($type->allowsNull()) {
                    $types[] = 'null';
                }
            } elseif ($type instanceof \ReflectionUnionType) {
                foreach ($type->getTypes() as $typeSub) {
                    $types[] = $typeSub->getName();
                }
            }
        } else {
            $types[] = 'null';
        }
        return $types;
    }
}
