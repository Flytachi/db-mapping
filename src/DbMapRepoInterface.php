<?php

namespace Flytachi\DbMapping;

interface DbMapRepoInterface
{
    public function originTable(): string;
    public function mapIdentifierColumnName(): string;
}
