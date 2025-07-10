<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\Varchar;

// Test Case 1: Basic Varchar with length
$varchar1 = new Varchar(length: 255);
assert($varchar1->toSql() === 'VARCHAR(255)', 'VarcharTest 1 Failed: Basic Varchar');

// Test Case 2: Varchar with different length
$varchar2 = new Varchar(length: 100);
assert($varchar2->toSql() === 'VARCHAR(100)', 'VarcharTest 2 Failed: Varchar with different length');

// Test Case 3: supports method always returns true
$varchar3 = new Varchar(length: 50);
assert($varchar3->supports([]) === true, 'VarcharTest 3 Failed: supports method');

echo "All Varchar tests passed successfully!\n";


