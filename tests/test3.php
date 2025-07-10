<?php

use Flytachi\DbMapping\Attributes\Additive\NullableIs;
use Flytachi\DbMapping\Attributes\Constraint\ForeignRepo;
use Flytachi\DbMapping\Attributes\Hybrid\Uuid;
use Flytachi\DbMapping\Attributes\Idx\Index;
use Flytachi\DbMapping\Attributes\Idx\Primary;
use Flytachi\DbMapping\Attributes\Idx\Unique;
use Flytachi\DbMapping\Attributes\Primal\BigInteger;
use Flytachi\DbMapping\Attributes\Primal\Blob;
use Flytachi\DbMapping\Attributes\Primal\Boolean;
use Flytachi\DbMapping\Attributes\Primal\Decimal;
use Flytachi\DbMapping\Attributes\Primal\Json;
use Flytachi\DbMapping\Attributes\Primal\Timestamp;
use Flytachi\DbMapping\Attributes\Primal\Varchar;
use Flytachi\DbMapping\Attributes\Sub\AutoIncrement;
use Flytachi\DbMapping\DbMapRepoInterface;
use Flytachi\DbMapping\Structure\Table;
use Flytachi\DbMapping\Tools\ColumnMapping;

require 'vendor/autoload.php';

class Repo implements DbMapRepoInterface
{
    public function originTable(): string
    {
        return 'public.charge_types';
    }

    public function mapIdentifierColumnName(): string
    {
        return 'id';
    }
}

enum Status: int
{
    case CONFIRMED = 1;
    case PENDING = 2;
    case CANCELLED = 3;
}

class ChargeTypeModel
{
    #[Primary]
    #[AutoIncrement]
    #[BigInteger]
    public ?int $id = null;

    #[NullableIs(false)]
    public null|array|string $nameEver = [];

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
    #[Uuid]
    public ?string $id = null;

    #[ForeignRepo(Repo::class)]
    public int $charge_type_id;

    #[Decimal]
    public int|float $amount = 0;

    #[Varchar(length: 700)]
    public array|string $description;

    #[Varchar(length: 20)]
    public string $billing_code;

    #[Timestamp]
    public string $created_at;

    #[\Flytachi\DbMapping\Attributes\Constraint\CheckEnum(Status::class)]
    public int $status;

    #[Blob('long')]
    public string $cre;
}

$dialect = 'mysql';
//$dialect = 'pgsql';

$schema = 'public';
if ($dialect === 'mysql') {
    $schema = null;
}
$reflectionClassModel = new ReflectionClass(ChargeTypeModel::class);
$columnMap = new ColumnMapping($dialect);
foreach ($reflectionClassModel->getProperties() as $property) {
    $columnMap->push($property);
}
$table = new Table('charge_types', $columnMap->getColumns(), schema: $schema);
$tb1 = $table->toSql($dialect);

$reflectionClassModel = new ReflectionClass(TransactionModel::class);
$columnMap = new ColumnMapping($dialect);
foreach ($reflectionClassModel->getProperties() as $property) {
    $columnMap->push($property);
}
$table = new Table('transactions', $columnMap->getColumns(), schema: $schema);
$tb2 =  $table->toSql($dialect);

echo "-- 1. Table 'charge_types' --\n";
print_r($tb1);
echo PHP_EOL;

echo "\n-- 2. Table 'transactions' --\n";
print_r($tb2);
echo PHP_EOL;
