<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\Integer;

// Test Case 1: Basic Integer
$integer1 = new Integer();
assert($integer1->toSql() === 'INT', 'IntegerTest 1 Failed: Basic Integer');

// Test Case 2: supports method with int type
$integer2 = new Integer();
assert($integer2->supports(['int']) === true, 'IntegerTest 2 Failed: supports with int');

// Test Case 3: supports method with mixed type
$integer3 = new Integer();
assert($integer3->supports(['mixed']) === true, 'IntegerTest 3 Failed: supports with mixed');

// Test Case 4: supports method with string type (should be false)
$integer4 = new Integer();
assert($integer4->supports(['string']) === false, 'IntegerTest 4 Failed: supports with string');

// Test Case 5: supports method with int and null type
$integer5 = new Integer();
assert($integer5->supports(['int', 'null']) === true, 'IntegerTest 5 Failed: supports with int and null');

echo "All Integer tests passed successfully!\n";


