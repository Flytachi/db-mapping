<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\Text;

// Test Case 1: Basic Text
$text1 = new Text();
assert($text1->toSql() === 'TEXT', 'TextTest 1 Failed: Basic Text');

// Test Case 2: supports method always returns true
$text2 = new Text();
assert($text2->supports([]) === true, 'TextTest 2 Failed: supports method');

echo "All Text tests passed successfully!\n";


