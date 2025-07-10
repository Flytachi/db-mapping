<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Additive\DefaultVal;

// Test Case 1: DefaultVal with string value
$defaultVal1 = new DefaultVal(definition: "'test_string'");
$nullable1 = null;
$default1 = null;
$defaultVal1->preparation($nullable1, $default1);
assert($default1 === "'test_string'", 'DefaultValTest 1 Failed: String default value');

// Test Case 2: DefaultVal with integer value
$defaultVal2 = new DefaultVal(definition: "123");
$nullable2 = null;
$default2 = null;
$defaultVal2->preparation($nullable2, $default2);
assert($default2 === "123", 'DefaultValTest 2 Failed: Integer default value');

// Test Case 3: DefaultVal with boolean true
$defaultVal3 = new DefaultVal(definition: "TRUE");
$nullable3 = null;
$default3 = null;
$defaultVal3->preparation($nullable3, $default3);
assert($default3 === "TRUE", 'DefaultValTest 3 Failed: Boolean true default value');

// Test Case 4: DefaultVal with boolean false
$defaultVal4 = new DefaultVal(definition: "FALSE");
$nullable4 = null;
$default4 = null;
$defaultVal4->preparation($nullable4, $default4);
assert($default4 === "FALSE", 'DefaultValTest 4 Failed: Boolean false default value');

// Test Case 5: DefaultVal with NULL
$defaultVal5 = new DefaultVal(definition: "NULL");
$nullable5 = null;
$default5 = null;
$defaultVal5->preparation($nullable5, $default5);
assert($default5 === "NULL", 'DefaultValTest 5 Failed: NULL default value');

// Test Case 6: Nullable parameter remains unchanged
$defaultVal6 = new DefaultVal(definition: "'some_value'");
$nullable6 = true;
$default6 = null;
$defaultVal6->preparation($nullable6, $default6);
assert($nullable6 === true, 'DefaultValTest 6 Failed: Nullable parameter unchanged');

echo "All DefaultVal tests passed successfully!\n";


