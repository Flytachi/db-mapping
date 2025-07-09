<?php

use Flytachi\DbMapping\Constants\IndexType;
use Flytachi\DbMapping\Structure\CheckConstraint;
use Flytachi\DbMapping\Structure\Column;
use Flytachi\DbMapping\Structure\ForeignKey;
use Flytachi\DbMapping\Structure\Index;
use Flytachi\DbMapping\Structure\StoredProcedure;
use Flytachi\DbMapping\Structure\Table;
use Flytachi\DbMapping\Structure\Trigger;
use Flytachi\DbMapping\Structure\View;

require 'vendor/autoload.php';

$dialect = 'pgsql';

// 1. Test ALTER TABLE
$table = new Table(
    name: 'test_table',
    columns: [
        new Column('id', 'SERIAL'),
        new Column('name', 'VARCHAR(50)', false),
    ],
    indexes: [new Index(['id'], type: IndexType::PRIMARY)]
);

echo "\-- 1. ALTER TABLE --\n";
echo $table->addColumn(new Column('email', 'VARCHAR(100)'), $dialect) . "\n";
echo $table->dropColumn('name') . "\n";
echo $table->addIndex(new Index(['email'], type: IndexType::UNIQUE), $dialect) . "\n";
echo $table->dropIndex('test_table_email', $dialect) . "\n";

// 2. Test CHECK constraint
$checkConstraint = new CheckConstraint('id > 0', 'id_positive');
$tableWithCheck = new Table(
    name: 'test_check',
    columns: [new Column('id', 'INT')],
    checks: [$checkConstraint]
);
echo "\n-- 2. CHECK constraint --\n";
echo $tableWithCheck->toSql($dialect) . "\n";

// 3. Test PostgreSQL index with INCLUDE
$indexWithInclude = new Index(['name'], type: IndexType::INDEX, includeColumns: ['email']);
$tableWithInclude = new Table(
    name: 'test_include',
    columns: [new Column('name', 'VARCHAR(50)'), new Column('email', 'VARCHAR(100)')],
    indexes: [$indexWithInclude]
);
echo "\n-- 3. PostgreSQL index with INCLUDE --\n";
echo $tableWithInclude->toSql($dialect) . "\n";

// 4. Test VIEWS, STORED PROCEDURES, TRIGGERS
$view = new View('my_view', 'SELECT id, name FROM test_table');
$sp = new StoredProcedure('my_proc', 'UPDATE test_table SET name = \'test\' WHERE id = 1;');
$trigger = new Trigger('my_trigger', 'test_table', 'INSERT', 'AFTER', 'my_proc');
echo "\n-- 4. VIEWS, STORED PROCEDURES, TRIGGERS --\n";
echo $view->toSql($dialect) . "\n";
echo $sp->toSql($dialect) . "\n";
echo $trigger->toSql($dialect) . "\n";

// 5. Test SCHEMAS
$tableWithSchema = new Table(
    name: 'my_table',
    columns: [new Column('id', 'INT')],
    schema: 'my_schema'
);
echo "\n-- 5. SCHEMAS --\n";
echo $tableWithSchema->createSchemaIfNotExists($dialect) . "\n";
echo $tableWithSchema->toSql($dialect) . "\n";

// 6. Test Name validation
echo "\n-- 6. Name validation --\n";
try {
    new Table('invalid-table-name', []);
} catch (\InvalidArgumentException $e) {
    echo "Caught expected exception: " . $e->getMessage() . "\n";
}

// 8. Test improved ForeignKey handling
$fk = new ForeignKey('users', 'id', name: 'fk_test_users');
$tableWithFk = new Table(
    name: 'test_fk',
    columns: [new Column('id', 'INT'), new Column('user_id', 'INT', foreignKey: $fk)],
    foreignKeys: []
);
echo "\n-- 8. Improved ForeignKey handling --\n";
echo $tableWithFk->toSql($dialect) . "\n";
