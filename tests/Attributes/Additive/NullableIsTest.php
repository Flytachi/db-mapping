<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Additive\NullableIs;

// Test Case 1: NullableIs true
$nullableIs1 = new NullableIs(isNullable: true);
$nullable1 = false;
$default1 = null;
$nullableIs1->preparation($nullable1, $default1);
assert($nullable1 === true, 'NullableIsTest 1 Failed: isNullable true');

// Test Case 2: NullableIs false
$nullableIs2 = new NullableIs(isNullable: false);
$nullable2 = true;
$default2 = null;
$nullableIs2->preparation($nullable2, $default2);
assert($nullable2 === false, 'NullableIsTest 2 Failed: isNullable false');

// Test Case 3: Default parameter remains unchanged
$nullableIs3 = new NullableIs(isNullable: true);
$nullable3 = false;
$default3 = 'some_default';
$nullableIs3->preparation($nullable3, $default3);
assert($default3 === 'some_default', 'NullableIsTest 3 Failed: Default parameter unchanged');

echo "All NullableIs tests passed successfully!\n";


