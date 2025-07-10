<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\SmallInteger;

// Test Case 1: Basic SmallInteger
$smallInteger1 = new SmallInteger();
assert($smallInteger1->toSql() === 'SMALLINT', 'SmallIntegerTest 1 Failed: Basic SmallInteger');

// Test Case 2: supports method with int type
$smallInteger2 = new SmallInteger();
assert($smallInteger2->supports(['int']) === true, 'SmallIntegerTest 2 Failed: supports with int');

// Test Case 3: supports method with mixed type
$smallInteger3 = new SmallInteger();
assert($smallInteger3->supports(['mixed']) === true, 'SmallIntegerTest 3 Failed: supports with mixed');

// Test Case 4: supports method with string type (should be false)
$smallInteger4 = new SmallInteger();
assert($smallInteger4->supports(['string']) === false, 'SmallIntegerTest 4 Failed: supports with string');

// Test Case 5: supports method with int and null type
$smallInteger5 = new SmallInteger();
assert($smallInteger5->supports(['int', 'null']) === true, 'SmallIntegerTest 5 Failed: supports with int and null');

echo "All SmallInteger tests passed successfully!\n";


