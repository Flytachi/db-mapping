<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\BigInteger;

// Test Case 1: Basic BigInteger
$bigInteger1 = new BigInteger();
assert($bigInteger1->toSql() === 'BIGINT', 'BigIntegerTest 1 Failed: Basic BigInteger');

// Test Case 2: supports method with int type
$bigInteger2 = new BigInteger();
assert($bigInteger2->supports(['int']) === true, 'BigIntegerTest 2 Failed: supports with int');

// Test Case 3: supports method with mixed type
$bigInteger3 = new BigInteger();
assert($bigInteger3->supports(['mixed']) === true, 'BigIntegerTest 3 Failed: supports with mixed');

// Test Case 4: supports method with string type (should be false)
$bigInteger4 = new BigInteger();
assert($bigInteger4->supports(['string']) === false, 'BigIntegerTest 4 Failed: supports with string');

// Test Case 5: supports method with int and null type
$bigInteger5 = new BigInteger();
assert($bigInteger5->supports(['int', 'null']) === true, 'BigIntegerTest 5 Failed: supports with int and null');

echo "All BigInteger tests passed successfully!\n";


