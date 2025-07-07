<?php

require_once 'init.php';

use Flytachi\DbMapping\Attributes\Constraint\Index;
use Flytachi\DbMapping\Attributes\Constraint\Primary;
use Flytachi\DbMapping\Attributes\Constraint\Unique;
use Flytachi\DbMapping\Attributes\Foreign;
use Flytachi\DbMapping\Attributes\Primal\BigInteger;
use Flytachi\DbMapping\Attributes\Primal\Boolean;
use Flytachi\DbMapping\Attributes\Primal\Decimal;
use Flytachi\DbMapping\Attributes\Primal\Json;
use Flytachi\DbMapping\Attributes\Primal\Timestamp;
use Flytachi\DbMapping\Attributes\Primal\Varchar;
use Flytachi\DbMapping\Attributes\Sub\AutoIncrement;
use Flytachi\DbMapping\Structure\Table;
use Flytachi\DbMapping\Tools\ColumnMapping;


class ChargeTypeModel
{
    #[Primary]
    #[AutoIncrement]
    #[BigInteger]
    public ?int $id = null;

    #[Json]
    public array|string $name = [];

    #[Decimal]
    public int $cost = 0;

    #[Unique]
    #[Varchar(length: 20)]
    public string $billing_code;

    #[Index(columns: ['billing_code'])]
    #[Boolean]
    public bool $is_delete = false;
}

class TransactionModel
{
    #[Primary]
    public string $id;

    #[Foreign('charge_types', 'id')]
    public int $charge_type_id;

    #[Decimal]
    public int|float $amount = 0;

    #[Varchar(length: 700)]
    public array|string $description;

    #[Varchar(length: 20)]
    public string $billing_code;

    #[Timestamp]
    public string $created_at;
}

$dialect = 'mysql';
$dialect = 'pgsql';

$reflectionClassModel = new ReflectionClass(ChargeTypeModel::class);
$columnMap = new ColumnMapping($dialect);
foreach ($reflectionClassModel->getProperties() as $property) {
    $columnMap->push($property);
}
$table = new Table('charge_types', $columnMap->getColumns(), schema: 'public');
$tb1 = $table->toSql($dialect);

$reflectionClassModel = new ReflectionClass(TransactionModel::class);
$columnMap = new ColumnMapping($dialect);
foreach ($reflectionClassModel->getProperties() as $property) {
    $columnMap->push($property);
}
$table = new Table('transactions', $columnMap->getColumns(), schema: 'public');
$tb2 =  $table->toSql($dialect);

print_r($tb1);
echo PHP_EOL;
print_r($tb2);
echo PHP_EOL;

dd(
    $tb1,
    $tb2
);
