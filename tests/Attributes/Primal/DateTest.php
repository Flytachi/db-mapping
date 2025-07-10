<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\Date;

// Test Case 1: Basic Date
$date1 = new Date();
assert($date1->toSql() === 'DATE', 'DateTest 1 Failed: Basic Date');

// Test Case 2: supports method (inherited from DateTime, should return true for string, DateTimeInterface)
$date2 = new Date();
assert($date2->supports(['string']) === true, 'DateTest 2 Failed: supports with string');
assert($date2->supports([DateTimeInterface::class]) === true, 'DateTest 3 Failed: supports with DateTimeInterface');
assert($date2->supports(['int']) === false, 'DateTest 4 Failed: supports with int');

echo "All Date tests passed successfully!\n";


