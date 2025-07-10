<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../vendor/autoload.php';

use Flytachi\DbMapping\Structure\Column;
use Flytachi\DbMapping\Structure\Index;
use Flytachi\DbMapping\Structure\ForeignKey;
use Flytachi\DbMapping\Constants\IndexMethod;
use Flytachi\DbMapping\Constants\IndexType;
use Flytachi\DbMapping\Constants\FKAction;

// Test Case 1: Basic Column Definition
$column1 = new Column(
    name: 'id',
    type: 'INT',
    nullable: false,
    default: null
);
assert($column1->toSql('users') === 'id INT NOT NULL', 'ColumnTest 1 Failed: Basic INT column');

// Test Case 2: Column with Nullable and Default
$column2 = new Column(
    name: 'name',
    type: 'VARCHAR(255)',
    nullable: true,
    default: "'John Doe'"
);
assert($column2->toSql('users') === 'name VARCHAR(255) DEFAULT \'John Doe\'', 'ColumnTest 2 Failed: Nullable VARCHAR with default');

// Test Case 3: Column with Index
$column3 = new Column(
    name: 'email',
    type: 'VARCHAR(255)',
    indexes: [new Index(columns: ["email"], type: IndexType::UNIQUE)]
);
$constraints3 = $column3->constraintsSql('users');
assert(count($constraints3) === 1 && str_contains($constraints3[0], 'UNIQUE INDEX email_idx ON users (email)'), 'ColumnTest 3 Failed: Column with Unique Index');

// Test Case 4: Column with Foreign Key
$column4 = new Column(
    name: 'user_id',
    type: 'INT',
    foreignKey: new ForeignKey(referencedTable: 'users', referencedColumn: 'id', onDelete: FKAction::CASCADE));
$constraints4 = $column4->constraintsSql('orders');
assert(count($constraints4) === 1 && str_contains($constraints4[0], 'FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE'), 'ColumnTest 4 Failed: Column with Foreign Key');

// Test Case 5: Column with multiple indexes
$column5 = new Column(
    name: 'product_code',
    type: 'VARCHAR(50)',
    indexes: [
        new Index(name: 'code_idx', columns: ['product_code'], type: IndexType::INDEX, method: IndexMethod::BTREE),
        new Index(name: 'code_unique', columns: ['product_code'], type: IndexType::UNIQUE)
    ]);
$constraints5 = $column5->constraintsSql('products');
assert(count($constraints5) === 2 && str_contains($constraints5[0], 'INDEX code_idx USING BTREE ON products (product_code)') && str_contains($constraints5[1], 'UNIQUE INDEX code_unique ON products (product_code)'), 'ColumnTest 5 Failed: Column with multiple indexes');

// Test Case 6: getPrimitiveSqlType - int
assert(Column::getPrimitiveSqlType(['int']) === 'INT', 'ColumnTest 6 Failed: getPrimitiveSqlType for int');

// Test Case 7: getPrimitiveSqlType - string
assert(Column::getPrimitiveSqlType(['string']) === 'VARCHAR(255)', 'ColumnTest 7 Failed: getPrimitiveSqlType for string');

// Test Case 8: getPrimitiveSqlType - bool
assert(Column::getPrimitiveSqlType(['bool']) === 'BOOLEAN', 'ColumnTest 8 Failed: getPrimitiveSqlType for bool');

// Test Case 9: getPrimitiveSqlType - float (mysql)
assert(Column::getPrimitiveSqlType(['float'], 'mysql') === 'FLOAT', 'ColumnTest 9 Failed: getPrimitiveSqlType for float (mysql)');

// Test Case 10: getPrimitiveSqlType - float (pgsql)
assert(Column::getPrimitiveSqlType(['float'], 'pgsql') === 'REAL', 'ColumnTest 10 Failed: getPrimitiveSqlType for float (pgsql)');

// Test Case 11: getPrimitiveSqlType - int|float (mysql)
assert(Column::getPrimitiveSqlType(['int', 'float'], 'mysql') === 'DOUBLE', 'ColumnTest 11 Failed: getPrimitiveSqlType for int|float (mysql)');

// Test Case 12: getPrimitiveSqlType - int|float (pgsql)
assert(Column::getPrimitiveSqlType(['int', 'float'], 'pgsql') === 'DOUBLE PRECISION', 'ColumnTest 12 Failed: getPrimitiveSqlType for int|float (pgsql)');

// Test Case 13: getPrimitiveSqlType - int|string
assert(Column::getPrimitiveSqlType(['int', 'string']) === 'TEXT', 'ColumnTest 13 Failed: getPrimitiveSqlType for int|string');

// Test Case 14: getPrimitiveSqlType - bool|int
assert(Column::getPrimitiveSqlType(['bool', 'int']) === 'INT', 'ColumnTest 14 Failed: getPrimitiveSqlType for bool|int');

echo "All Column tests passed successfully!\n";


