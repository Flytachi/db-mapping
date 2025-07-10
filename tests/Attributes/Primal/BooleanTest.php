<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\Boolean;

// Test Case 1: Basic Boolean
$boolean1 = new Boolean();
assert($boolean1->toSql() === 'BOOLEAN', 'BooleanTest 1 Failed: Basic Boolean');

// Test Case 2: supports method with bool type
$boolean2 = new Boolean();
assert($boolean2->supports(['bool']) === true, 'BooleanTest 2 Failed: supports with bool');

// Test Case 3: supports method with int type
$boolean3 = new Boolean();
assert($boolean3->supports(['int']) === true, 'BooleanTest 3 Failed: supports with int');

// Test Case 4: supports method with string type
$boolean4 = new Boolean();
assert($boolean4->supports(['string']) === true, 'BooleanTest 4 Failed: supports with string');

// Test Case 5: supports method with mixed type
$boolean5 = new Boolean();
assert($boolean5->supports(['mixed']) === true, 'BooleanTest 5 Failed: supports with mixed');

// Test Case 6: supports method with float type (should be false)
$boolean6 = new Boolean();
assert($boolean6->supports(['float']) === false, 'BooleanTest 6 Failed: supports with float');

// Test Case 7: supports method with bool and null type
$boolean7 = new Boolean();
assert($boolean7->supports(['bool', 'null']) === true, 'BooleanTest 7 Failed: supports with bool and null');

echo "All Boolean tests passed successfully!\n";


