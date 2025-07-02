<?php

declare(strict_types=1);

namespace Flytachi\DbMapping\Structure;

class View implements StructureInterface
{
    public function __construct(
        public string $name,
        public string $definition,
    ) {
        NameValidator::validate($name);
    }

    public function toSql(string $dialect = 'mysql'): string
    {
        return sprintf("CREATE VIEW %s AS %s;", $this->name, $this->definition);
    }
}
