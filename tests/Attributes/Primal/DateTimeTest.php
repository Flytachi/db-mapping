<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../../vendor/autoload.php';

use Flytachi\DbMapping\Attributes\Primal\DateTime;

// Test Case 1: Basic DateTime (MySQL)
$dateTime1 = new DateTime();
assert($dateTime1->toSql("mysql") === "DATETIME", "DateTimeTest 1 Failed: Basic DateTime (MySQL)");

// Test Case 2: Basic DateTime (PostgreSQL)
$dateTime2 = new DateTime();
assert($dateTime2->toSql("pgsql") === "TIMESTAMP WITHOUT TIME ZONE", "DateTimeTest 2 Failed: Basic DateTime (PostgreSQL)");

// Test Case 3: supports method with string type
$dateTime3 = new DateTime();
assert($dateTime3->supports(["string"]) === true, "DateTimeTest 3 Failed: supports with string");

// Test Case 4: supports method with DateTimeImmutable type
$dateTime4 = new DateTime();
assert($dateTime4->supports([DateTimeImmutable::class]) === true, "DateTimeTest 4 Failed: supports with DateTimeImmutable");

// Test Case 5: supports method with DateTime type
$dateTime5 = new DateTime();
assert($dateTime5->supports([DateTime::class]) === true, "DateTimeTest 5 Failed: supports with DateTime");

// Test Case 6: supports method with mixed type
$dateTime6 = new DateTime();
assert($dateTime6->supports(["mixed"]) === true, "DateTimeTest 6 Failed: supports with mixed");

// Test Case 7: supports method with int type (should be false)
$dateTime7 = new DateTime();
assert($dateTime7->supports(["int"]) === false, "DateTimeTest 7 Failed: supports with int");

// Test Case 8: supports method with string and null type
$dateTime8 = new DateTime();
assert($dateTime8->supports(["string", "null"]) === true, "DateTimeTest 8 Failed: supports with string and null");

echo "All DateTime tests passed successfully!\n";


