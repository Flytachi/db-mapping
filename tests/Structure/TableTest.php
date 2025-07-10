<?php

declare(strict_types=1);

require_once __DIR__ . 
'/../../vendor/autoload.php';

use Flytachi\DbMapping\Structure\Table;
use Flytachi\DbMapping\Structure\Column;
use Flytachi\DbMapping\Structure\Index;
use Flytachi\DbMapping\Structure\ForeignKey;
use Flytachi\DbMapping\Structure\CheckConstraint;
use Flytachi\DbMapping\Constants\IndexType;
use Flytachi\DbMapping\Constants\FKAction;

// Test Case 1: Basic Table Creation
$column1 = new Column(name: 'id', type: 'INT', nullable: false);
$column2 = new Column(name: 'name', type: 'VARCHAR(255)');
$table1 = new Table(name: 'users', columns: [$column1, $column2]);
$sql1 = $table1->toSql();
assert(str_contains($sql1, 'CREATE TABLE IF NOT EXISTS users') && str_contains($sql1, 'id INT NOT NULL') && str_contains($sql1, 'name VARCHAR(255)'), 'TableTest 1 Failed: Basic table creation');

// Test Case 2: Table with Primary Key
$column3 = new Column(name: 'id', type: 'INT', nullable: false, indexes: [new Index(columns: ["id"], type: IndexType::PRIMARY)]);
$table2 = new Table(name: 'products', columns: [$column3, new Column(name: 'product_name', type: 'VARCHAR(255)')]);
$sql2 = $table2->toSql();
assert(str_contains($sql2, 'PRIMARY KEY (id)'), 'TableTest 2 Failed: Table with Primary Key');

// Test Case 3: Table with Foreign Key
$column4 = new Column(name: 'order_id', type: 'INT', foreignKey: new ForeignKey(referencedTable: 'orders', referencedColumn: 'id', onDelete: FKAction::CASCADE));
$table3 = new Table(name: 'order_items', columns: [$column4, new Column(name: 'item_name', type: 'VARCHAR(255)')]);
$sql3 = $table3->toSql();
assert(str_contains($sql3, 'FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE'), 'TableTest 3 Failed: Table with Foreign Key');

// Test Case 4: Table with Check Constraint
$column5 = new Column(name: 'age', type: 'INT');
$check1 = new CheckConstraint(expression: 'age >= 18');
$table4 = new Table(name: 'people', columns: [$column5], checks: [$check1]);
$sql4 = $table4->toSql();
assert(str_contains($sql4, 'CHECK (age >= 18)'), 'TableTest 4 Failed: Table with Check Constraint');

// Test Case 5: Table with Schema (PostgreSQL dialect)
$table5 = new Table(name: 'logs', columns: [$column1, $column2], schema: 'app_schema');
$sql5 = $table5->toSql('pgsql');
assert(str_contains($sql5, 'CREATE TABLE IF NOT EXISTS app_schema.logs') && str_contains($sql5, 'id INT NOT NULL') && str_contains($sql5, 'name VARCHAR(255)'), 'TableTest 5 Failed: Table with Schema (PostgreSQL)');

// Test Case 6: addColumn method
$table6 = new Table(name: 'temp_table', columns: [new Column(name: 'col1', type: 'INT')]);
$addColumnSql = $table6->addColumn(new Column(name: 'col2', type: 'TEXT'));
assert(str_contains($addColumnSql, 'ALTER TABLE temp_table ADD COLUMN col2 TEXT'), 'TableTest 6 Failed: addColumn method');

// Test Case 7: dropColumn method
$table7 = new Table(name: 'temp_table_drop', columns: [new Column(name: 'col1', type: 'INT'), new Column(name: 'col2', type: 'TEXT')]);
$dropColumnSql = $table7->dropColumn('col1');
assert(str_contains($dropColumnSql, 'ALTER TABLE temp_table_drop DROP COLUMN col1'), 'TableTest 7 Failed: dropColumn method');

// Test Case 8: addIndex method
$table8 = new Table(name: 'temp_table_index', columns: [new Column(name: 'col1', type: 'INT')]);
$addIndexSql = $table8->addIndex(new Index(name: 'idx_col1', columns: ['col1'], type: IndexType::INDEX));
assert(str_contains($addIndexSql, 'ALTER TABLE temp_table_index ADD INDEX idx_col1 (col1)'), 'TableTest 8 Failed: addIndex method');

// Test Case 9: dropIndex method (MySQL)
$table9 = new Table(name: 'temp_table_drop_index', columns: [new Column(name: 'col1', type: 'INT')]);
$dropIndexSql = $table9->dropIndex('idx_col1', 'mysql');
assert(str_contains($dropIndexSql, 'ALTER TABLE temp_table_drop_index DROP INDEX idx_col1'), 'TableTest 9 Failed: dropIndex method (MySQL)');

// Test Case 10: dropIndex method (PostgreSQL)
$table10 = new Table(name: 'temp_table_drop_index_pg', columns: [new Column(name: 'col1', type: 'INT')]);
$dropIndexSqlPg = $table10->dropIndex('idx_col1', 'pgsql');
assert(str_contains($dropIndexSqlPg, 'DROP INDEX idx_col1'), 'TableTest 10 Failed: dropIndex method (PostgreSQL)');

// Test Case 11: addForeignKey method
$table11 = new Table(name: 'temp_table_fk', columns: [new Column(name: 'col1', type: 'INT')]);
$addForeignKeySql = $table11->addForeignKey(new ForeignKey(referencedTable: 'parent_table', referencedColumn: 'parent_id', onDelete: FKAction::RESTRICT), 'mysql');

// Test Case 12: dropForeignKey method (MySQL)
$table12 = new Table(name: 'temp_table_drop_fk', columns: [new Column(name: 'col1', type: 'INT')]);
$dropForeignKeySql = $table12->dropForeignKey('fk_name', 'mysql');
assert(str_contains($dropForeignKeySql, 'ALTER TABLE temp_table_drop_fk DROP FOREIGN KEY fk_name'), 'TableTest 12 Failed: dropForeignKey method (MySQL)');

// Test Case 13: dropForeignKey method (PostgreSQL)
$table13 = new Table(name: 'temp_table_drop_fk_pg', columns: [new Column(name: 'col1', type: 'INT')]);
$dropForeignKeySqlPg = $table13->dropForeignKey('fk_name', 'pgsql');
assert(str_contains($dropForeignKeySqlPg, 'ALTER TABLE temp_table_drop_fk_pg DROP CONSTRAINT fk_name'), 'TableTest 13 Failed: dropForeignKey method (PostgreSQL)');

// Test Case 14: addCheckConstraint method
$table14 = new Table(name: 'temp_table_check', columns: [new Column(name: 'col1', type: 'INT')]);
$addCheckSql = $table14->addCheckConstraint(new CheckConstraint(expression: 'col1 > 0', name: 'check_col1'));
assert(str_contains($addCheckSql, 'ALTER TABLE temp_table_check ADD CONSTRAINT check_col1 CHECK (col1 > 0)'), 'TableTest 14 Failed: addCheckConstraint method');

// Test Case 15: dropCheckConstraint method
$table15 = new Table(name: 'temp_table_drop_check', columns: [new Column(name: 'col1', type: 'INT')]);
$dropCheckSql = $table15->dropCheckConstraint('check_col1');
assert(str_contains($dropCheckSql, 'ALTER TABLE temp_table_drop_check DROP CONSTRAINT check_col1'), 'TableTest 15 Failed: dropCheckConstraint method');

echo "All Table tests passed successfully!\n";



assert(str_contains($addForeignKeySql, 'ALTER TABLE temp_table_fk ADD FOREIGN KEY (col1) REFERENCES parent_table (parent_id) ON DELETE RESTRICT'), 'TableTest 11 Failed: addForeignKey method');


