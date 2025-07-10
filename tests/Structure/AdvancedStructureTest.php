<?php

declare(strict_types=1);

require_once __DIR__ .
    '/../../vendor/autoload.php';

use Flytachi\DbMapping\Structure\View;
use Flytachi\DbMapping\Structure\StoredProcedure;
use Flytachi\DbMapping\Structure\Trigger;
use Flytachi\DbMapping\Structure\CheckConstraint;
use Flytachi\DbMapping\Structure\Table;
use Flytachi\DbMapping\Structure\Column;

class AdvancedStructureTest
{
    private string $dialect = 'pgsql';

    public function testViewCreation(): void
    {
        $view = new View('my_view', 'SELECT id, name FROM test_table');
        $sql = $view->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE VIEW my_view AS SELECT id, name FROM test_table'), 'View creation failed');
        echo "View creation test passed.\n";
    }

    public function testStoredProcedureCreation(): void
    {
        $sp = new StoredProcedure('my_proc', 'UPDATE test_table SET name = \'test\' WHERE id = 1;');
        $sql = $sp->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE PROCEDURE my_proc() LANGUAGE plpgsql AS $$ UPDATE test_table SET name = \'test\' WHERE id = 1; $$;'), 'Stored procedure creation failed');
        echo "Stored procedure creation test passed.\n";
    }

    public function testTriggerCreation(): void
    {
        $trigger = new Trigger('my_trigger', 'test_table', 'INSERT', 'AFTER', 'my_proc');
        $sql = $trigger->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE TRIGGER my_trigger AFTER INSERT ON test_table FOR EACH ROW EXECUTE PROCEDURE my_proc()'), 'Trigger creation failed');
        echo "Trigger creation test passed.\n";
    }

    public function testCheckConstraintCreation(): void
    {
        $checkConstraint = new CheckConstraint('age >= 18', 'age_check');
        $table = new Table(
            name: 'users_with_age',
            columns: [
                new Column('id', 'SERIAL'),
                new Column('age', 'INT'),
            ],
            checks: [$checkConstraint]
        );
        $sql = $table->toSql($this->dialect);
        assert(str_contains($sql, 'CONSTRAINT age_check CHECK (age >= 18)'), 'Check constraint creation failed');
        echo "Check constraint creation test passed.\n";
    }

    public function testNameValidation(): void
    {
        try {
            new Table('invalid-table-name', []);
            assert(false, 'Name validation failed: Invalid table name was accepted');
        } catch (\InvalidArgumentException $e) {
            assert(str_contains($e->getMessage(), 'Invalid name: invalid-table-name'), 'Name validation failed: Incorrect exception message');
            echo "Name validation test passed.\n";
        }
    }

    public function testSchemaCreation(): void
    {
        $table = new Table(
            name: 'my_table',
            columns: [new Column('id', 'INT')],
            schema: 'my_schema'
        );
        $sql = $table->createSchemaIfNotExists($this->dialect);
        assert(str_contains($sql, 'CREATE SCHEMA IF NOT EXISTS my_schema;'), 'Schema creation failed');
        echo "Schema creation test passed.\n";
    }

    public function testTableWithSchema(): void
    {
        $table = new Table(
            name: 'my_table_with_schema',
            columns: [new Column('id', 'INT')],
            schema: 'my_schema'
        );
        $sql = $table->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE TABLE IF NOT EXISTS my_schema.my_table_with_schema'), 'Table with schema creation failed');
        echo "Table with schema test passed.\n";
    }
}

$test = new AdvancedStructureTest();
$test->testViewCreation();
$test->testStoredProcedureCreation();
$test->testTriggerCreation();
$test->testCheckConstraintCreation();
$test->testNameValidation();
$test->testSchemaCreation();
$test->testTableWithSchema();

echo "All Advanced Structure tests passed successfully!\n";


