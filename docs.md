# Документация Flytachi/DbMapping

Flytachi/DbMapping - это PHP-библиотека для сопоставления объектов базы данных, которая позволяет определять структуру базы данных с помощью атрибутов PHP. Она упрощает создание и управление схемами баз данных, а также обеспечивает генерацию SQL-запросов для различных диалектов баз данных.

## Основные концепции

Библиотека основана на следующих ключевых концепциях:

*   **Атрибуты (Attributes):** Используются для определения свойств таблиц, столбцов, индексов, ограничений и других элементов базы данных непосредственно в коде PHP.
*   **Структура (Structure):** Представляет собой классы, которые моделируют элементы базы данных, такие как `Table`, `Column`, `Index`, `ForeignKey` и т.д.
*   **Генерация SQL:** Библиотека может генерировать SQL-запросы для создания и изменения структуры базы данных на основе определенных атрибутов и классов структуры.

## Структура проекта

Проект организован следующим образом:

*   `src/`: Содержит основной исходный код библиотеки.
    *   `Attributes/`: PHP-атрибуты для определения структуры базы данных.
        *   `Additive/`: Атрибуты, добавляющие дополнительные свойства к столбцам (например, `DefaultVal`, `NullableIs`).
        *   `Constraint/`: Атрибуты для определения ограничений (например, `ForeignKey`, `CheckConstraint`).
        *   `Hybrid/`: Атрибуты для определения идентификаторов (например, `Id`, `BigId`, `SmallId`).
        *   `Idx/`: Атрибуты для определения индексов (например, `Index`, `Primary`, `Unique`).
        *   `Primal/`: Атрибуты для определения основных типов данных столбцов (например, `Type`, `Varchar`, `Integer`, `Boolean`).
        *   `Sub/`: Атрибуты для определения подтипов (например, `AutoIncrement`).
        *   `AttributeDb.php`: Базовый интерфейс для всех атрибутов базы данных.
        *   `DbMap.php`: Атрибут уровня класса для сопоставления класса PHP с таблицей базы данных.
    *   `Constants/`: Константы, используемые в библиотеке (например, `FKAction`, `IndexMethod`, `IndexType`).
    *   `Structure/`: Классы, представляющие структуру базы данных.
        *   `Table.php`: Представляет таблицу базы данных.
        *   `Column.php`: Представляет столбец таблицы.
        *   `Index.php`: Представляет индекс таблицы.
        *   `ForeignKey.php`: Представляет внешний ключ.
        *   `CheckConstraint.php`: Представляет ограничение проверки.
        *   `NameValidator.php`: Утилита для проверки имен.
        *   `StructureInterface.php`: Интерфейс для всех структурных классов.
    *   `Tools/`: Вспомогательные утилиты (например, `ColumnMapping.php`).
    *   `DbMapRepoInterface.php`: Интерфейс репозитория для сопоставления базы данных.
*   `tests/`: Содержит тестовые примеры.
*   `composer.json`: Файл Composer для управления зависимостями.
*   `LICENSE`: Лицензия проекта (MIT).
*   `phpcs.xml`: Файл конфигурации PHP_CodeSniffer.

## Использование

### Определение структуры таблицы

Вы можете определить структуру таблицы, создав класс PHP и используя атрибуты `DbMap` и другие атрибуты для определения столбцов и их свойств.

Пример:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Flytachi\DbMapping\Attributes\Additive\DefaultVal;use Flytachi\DbMapping\Attributes\Additive\NullableIs;use Flytachi\DbMapping\Attributes\Constraint\ForeignKey;use Flytachi\DbMapping\Attributes\Entity\Table;use Flytachi\DbMapping\Attributes\Hybrid\Id;use Flytachi\DbMapping\Attributes\Idx\Unique;use Flytachi\DbMapping\Attributes\Primal\Integer;use Flytachi\DbMapping\Attributes\Primal\Varchar;use Flytachi\DbMapping\Constants\FKAction;

#[Table]
class User
{
    #[Id]
    #[Integer]
    #[Unique]
    public int $id;

    #[Varchar(255)]
    public string $name;

    #[Varchar(255)]
    #[Unique]
    public string $email;

    #[Integer]
    #[NullableIs(false)]
    #[DefaultVal("0")]
    public int $status;

    #[Integer]
    #[ForeignKey(referencedTable: 'roles', referencedColumn: 'id', onDelete: FKAction::CASCADE)]
    public int $role_id;
}
```

В этом примере:

*   `#[DbMap]` указывает, что класс `User` сопоставляется с таблицей базы данных.
*   `#[Id]` и `#[Integer]` определяют столбец `id` как первичный ключ типа `INTEGER`.
*   `#[Varchar(255)]` определяет столбцы `name` и `email` как `VARCHAR` с длиной 255.
*   `#[Unique]` создает уникальный индекс для столбцов `id` и `email`.
*   `#[NullableIs(false)]` и `#[DefaultVal("0")]` определяют столбец `status` как `NOT NULL` с значением по умолчанию `0`.
*   `#[ForeignKey(...)]` определяет внешний ключ для столбца `role_id`, ссылающийся на таблицу `roles` и столбец `id`, с каскадным удалением.

### Генерация SQL-схемы

Библиотека может генерировать SQL-запросы для создания таблиц на основе определенных классов.

Пример (псевдокод, так как для этого потребуется дополнительная логика инициализации библиотеки):

```php
<?php

// Предположим, у вас есть механизм для загрузки классов и их атрибутов
// $tableStructure = new Table('users', [
//     new Column('id', 'INT', false, null, [new Index('PRIMARY', ['id'], IndexType::PRIMARY)]),
//     new Column('name', 'VARCHAR(255)', false),
//     new Column('email', 'VARCHAR(255)', false, null, [new Index('email_unique', ['email'], IndexType::UNIQUE)]),
//     new Column('status', 'INT', false, '0'),
//     new Column('role_id', 'INT', false, null, null, new ForeignKey('roles', 'id', FKAction::CASCADE))
// ]);

// $sql = $tableStructure->toSql('mysql');
// echo $sql;

/*
Пример вывода SQL:

CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  status INT NOT NULL DEFAULT 0,
  role_id INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (email),
  FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE
);
*/
```

## Атрибуты

### `#[DbMap]`

*   **Применение:** Класс
*   **Описание:** Отмечает класс PHP как сущность, которая должна быть сопоставлена с таблицей базы данных.

### `#[Id]`

*   **Применение:** Свойство (Property)
*   **Описание:** Отмечает свойство как первичный идентификатор (ключ) таблицы. Обычно используется в сочетании с атрибутом типа данных (например, `#[Integer]`).

### `#[Integer]`, `#[Varchar]`, `#[Boolean]`, `#[Decimal]`, `#[Text]`, `#[Timestamp]`, `#[Json]`, `#[BigInteger]`, `#[SmallInteger]`

*   **Применение:** Свойство
*   **Описание:** Определяют тип данных столбца в базе данных. Например, `#[Varchar(255)]` для строкового столбца с максимальной длиной 255.

### `#[Unique]`

*   **Применение:** Свойство
*   **Описание:** Создает уникальный индекс для столбца.

### `#[Primary]`

*   **Применение:** Свойство
*   **Описание:** Создает первичный ключ для столбца. Обычно используется для столбца `id`.

### `#[Index]`

*   **Применение:** Свойство
*   **Описание:** Создает обычный индекс для столбца.

### `#[NullableIs]`

*   **Применение:** Свойство
*   **Описание:** Определяет, может ли столбец содержать `NULL` значения. `#[NullableIs(false)]` делает столбец `NOT NULL`.

### `#[DefaultVal]`

*   **Применение:** Свойство
*   **Описание:** Устанавливает значение по умолчанию для столбца. Например, `#[DefaultVal("0")]`.

### `#[AutoIncrement]`

*   **Применение:** Свойство
*   **Описание:** Указывает, что столбец должен автоматически увеличиваться (например, для первичных ключей).

### `#[ForeignKey]`

*   **Применение:** Свойство
*   **Описание:** Определяет внешний ключ. Требует указания `referencedTable` (ссылающаяся таблица) и `referencedColumn` (ссылающийся столбец). Также можно указать действия `onUpdate` и `onDelete` (например, `FKAction::CASCADE`, `FKAction::RESTRICT`, `FKAction::SET_NULL`).

## Классы структуры

### `Table`

Представляет таблицу базы данных. Содержит коллекции `Column`, `Index`, `CheckConstraint` и `ForeignKey`.

*   `__construct(string $name, array $columns, array $indexes = [], array $checks = [], array $foreignKeys = [], ?string $schema = null)`: Конструктор.
*   `toSql(string $dialect = 'mysql'): string`: Генерирует SQL-запрос `CREATE TABLE`.
*   `addColumn(Column $column, string $dialect = 'mysql'): string`: Генерирует SQL-запрос `ALTER TABLE ADD COLUMN`.
*   `dropColumn(string $columnName): string`: Генерирует SQL-запрос `ALTER TABLE DROP COLUMN`.
*   `addIndex(Index $index, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для добавления индекса.
*   `dropIndex(string $indexName, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для удаления индекса.
*   `addForeignKey(ForeignKey $foreignKey, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для добавления внешнего ключа.
*   `dropForeignKey(string $constraintName, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для удаления внешнего ключа.
*   `addCheckConstraint(CheckConstraint $check, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для добавления ограничения проверки.
*   `dropCheckConstraint(string $checkName, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для удаления ограничения проверки.

### `Column`

Представляет столбец таблицы.

*   `__construct(string $name, string $type, bool $nullable = true, ?string $default = null, array $indexes = [], ?ForeignKey $foreignKey = null)`: Конструктор.
*   `toSql(string $tableName, string $dialect = 'mysql'): string`: Генерирует часть SQL-запроса для определения столбца.
*   `constraintsSql(string $tableName, string $dialect = 'mysql'): array`: Возвращает SQL-запросы для ограничений, связанных со столбцом (индексы, внешние ключи).
*   `getPrimitiveSqlType(array $types, string $dialect = 'mysql'): string`: Статический метод для получения примитивного SQL-типа на основе типов PHP.

### `Index`

Представляет индекс таблицы.

*   `__construct(string $name, array $columns, IndexType $type = IndexType::INDEX, IndexMethod $method = IndexMethod::BTREE)`: Конструктор.
*   `toSql(string $tableName, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для создания индекса.

### `ForeignKey` (класс структуры)

Представляет внешний ключ.

*   `__construct(string $referencedTable, string $referencedColumn, FKAction $onUpdate = FKAction::RESTRICT, FKAction $onDelete = FKAction::RESTRICT, ?string $name = null)`: Конструктор.
*   `toSql(string $tableName, string $columnName, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для внешнего ключа.

### `CheckConstraint`

Представляет ограничение проверки.

*   `__construct(string $expression, ?string $name = null)`: Конструктор.
*   `toSql(string $tableName, string $dialect = 'mysql'): string`: Генерирует SQL-запрос для ограничения проверки.

## Константы

### `FKAction`

Перечисление для определения действий при обновлении или удалении внешнего ключа:

*   `RESTRICT`
*   `CASCADE`
*   `SET_NULL`
*   `NO_ACTION`
*   `SET_DEFAULT`

### `IndexMethod`

Перечисление для определения метода индексирования:

*   `BTREE`
*   `HASH`

### `IndexType`

Перечисление для определения типа индекса:

*   `INDEX`
*   `PRIMARY`
*   `UNIQUE`

## Установка

Для установки библиотеки используйте Composer:

```bash
composer require flytachi/db-mapping
```

## Тестирование

В репозитории есть несколько тестовых файлов в директории `tests/` (`test1.php`, `test2.php`, `test3.php`), которые демонстрируют использование библиотеки.

## Лицензия

Этот проект распространяется под лицензией MIT. Подробности см. в файле `LICENSE`.

## Вклад

Приветствуются любые вклады. Пожалуйста, ознакомьтесь с `phpcs.xml` для стандартов кодирования.

## Контакты

По вопросам и предложениям обращайтесь к автору: jasur.rakhmatov03@gmail.com

# DB Mapping Library - Full Documentation

## Table of Contents

1.  [Introduction](#introduction)
2.  [Core Concepts](#core-concepts)
    *   [Attributes](#attributes)
    *   [Structure](#structure)
    *   [Tools](#tools)
3.  [Attributes Reference](#attributes-reference)
    *   [Entity Attributes](#entity-attributes)
    *   [Primal Attributes](#primal-attributes)
    *   [Additive Attributes](#additive-attributes)
    *   [Hybrid Attributes](#hybrid-attributes)
    *   [Index Attributes](#index-attributes)
    *   [Constraint Attributes](#constraint-attributes)
4.  [Structure Reference](#structure-reference)
    *   [Table](#table)
    *   [Column](#column)
    *   [Index](#index)
    *   [ForeignKey](#foreignkey)
5.  [Usage Examples](#usage-examples)
    *   [Basic Table Definition](#basic-table-definition)
    *   [Advanced Table with Constraints](#advanced-table-with-constraints)
6.  [Extending the Library](#extending-the-library)
    *   [Creating Custom Attributes](#creating-custom-attributes)
    *   [Supporting New Database Dialects](#supporting-new-database-dialects)

---

## 1. Introduction

Welcome to the full documentation for the DB Mapping Library. This document provides a comprehensive guide to using the library, including detailed explanations of its components, a complete reference for all available attributes, and examples of how to use them effectively.

## 2. Core Concepts

The library is built around three core concepts: **Attributes**, **Structure**, and **Tools**.

### Attributes

Attributes are PHP 8 features that allow you to add metadata to your classes and properties. In this library, attributes are used to define the mapping between your PHP classes and the database schema. They provide a declarative way to specify table names, column types, constraints, and other database-related information directly in your code.

### Structure

The `Structure` namespace contains classes that represent the different components of a database schema, such as tables, columns, indexes, and foreign keys. These classes are used internally by the library to build a representation of the database schema based on the attributes you define in your model classes.

### Tools

The `Tools` namespace provides utility classes that help with the process of mapping and generating SQL. The most important tool is the `ColumnMapping` class, which is responsible for analyzing the attributes of a class and creating the corresponding `Structure` objects.

---

## 3. Attributes Reference

This section provides a detailed reference for all the attributes available in the library.

### Entity Attributes

Entity attributes are used to define the main entities in your database schema, such as tables.

*   `#[Table(name: string)]`: Defines a database table with the specified name.

### Primal Attributes

Primal attributes define the fundamental data type of a column.

*   `#[Type(definition: string)]`: A generic attribute to define a column with a custom SQL type.
*   `#[Varchar(length: int)]`: Defines a `VARCHAR` column with a specified length.
*   `#[Integer]`: Defines an `INT` column.
*   `#[BigInteger]`: Defines a `BIGINT` column.
*   `#[SmallInteger]`: Defines a `SMALLINT` column.
*   `#[Text]`: Defines a `TEXT` column.
*   `#[Boolean]`: Defines a `BOOLEAN` column.
*   `#[Date]`: Defines a `DATE` column.
*   `#[DateTime]`: Defines a `DATETIME` column.
*   `#[Timestamp]`: Defines a `TIMESTAMP` column.
*   `#[Time]`: Defines a `TIME` column.
*   `#[Json]`: Defines a `JSON` column.
*   `#[Decimal(precision: int, scale: int)]`: Defines a `DECIMAL` column with specified precision and scale.
*   `#[FloatType]`: Defines a `FLOAT` column.
*   `#[Double]`: Defines a `DOUBLE` column.
*   `#[Binary(length: int)]`: Defines a `BINARY` column with a specified length.
*   `#[Blob]`: Defines a `BLOB` column.
*   `#[UuidBase]`: A base attribute for UUID columns.

### Additive Attributes

Additive attributes provide additional properties for a column.

*   `#[NullableIs(bool)]`: Specifies whether a column can be `NULL`.
*   `#[DefaultVal(mixed)]`: Sets a default value for a column.

### Hybrid Attributes

Hybrid attributes are combinations of primal and additive attributes for common use cases.

*   `#[Id]`: A shorthand for a primary key column (`INT`, `AUTO_INCREMENT`).
*   `#[BigId]`: A shorthand for a `BIGINT` primary key.
*   `#[SmallId]`: A shorthand for a `SMALLINT` primary key.
*   `#[Uuid]`: A shorthand for a UUID primary key.

### Index Attributes

Index attributes are used to define indexes on your tables.

*   `#[Primary]`: Defines a primary key on a column.
*   `#[Unique]`: Defines a unique index on a column.
*   `#[Index(name: ?string, method: ?IndexMethod, type: ?IndexType)]`: Defines a general index on a column.

### Constraint Attributes

Constraint attributes are used to define constraints on your tables.

*   `#[ForeignKey(table: string, column: string, onDelete: ?FKAction, onUpdate: ?FKAction)]`: Defines a foreign key constraint.
*   `#[ForeignRepo(repo: string, column: string, onDelete: ?FKAction, onUpdate: ?FKAction)]`: Defines a foreign key constraint by referencing another repository.
*   `#[Check(expression: string)]`: Defines a `CHECK` constraint.
*   `#[CheckEnum(values: array)]`: Defines a `CHECK` constraint for an enum-like column.

---

## 4. Structure Reference

This section provides a reference for the main classes in the `Structure` namespace.

### Table

The `Table` class represents a database table. It contains a collection of `Column` objects and other metadata about the table.

### Column

The `Column` class represents a column in a database table. It holds information about the column's name, type, nullability, default value, and any associated indexes or foreign keys.

### Index

The `Index` class represents an index on a table. It includes the index name, method (e.g., `BTREE`), and type (e.g., `UNIQUE`).

### ForeignKey

The `ForeignKey` class represents a foreign key constraint. It stores information about the referenced table and column, as well as the actions to be taken on delete and update operations.

---

## 5. Usage Examples

### Basic Table Definition

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Flytachi\DbMapping\Attributes\Entity\Table;
use Flytachi\DbMapping\Attributes\Hybrid\Id;
use Flytachi\DbMapping\Attributes\Primal\Varchar;
use Flytachi\DbMapping\Attributes\Primal\Integer;

#[Table(name: "products")]
class Product
{
    #[Id]
    #[Integer]
    public int $id;

    #[Varchar(length: 100)]
    public string $name;

    #[Integer]
    public int $price;
}

```

### Advanced Table with Constraints

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Flytachi\DbMapping\Attributes\Entity\Table;
use Flytachi\DbMapping\Attributes\Hybrid\Id;
use Flytachi\DbMapping\Attributes\Primal\Varchar;
use Flytachi\DbMapping\Attributes\Primal\Integer;
use Flytachi\DbMapping\Attributes\Idx\Unique;
use Flytachi\DbMapping\Attributes\Constraint\ForeignKey;
use Flytachi\DbMapping\Constants\FKAction;

#[Table(name: "orders")]
class Order
{
    #[Id]
    #[Integer]
    public int $id;

    #[Varchar(length: 50)]
    #[Unique]
    public string $order_number;

    #[Integer]
    #[ForeignKey(table: "users", column: "id", onDelete: FKAction::CASCADE)]
    public int $user_id;
}

```

---

## 6. Extending the Library

### Creating Custom Attributes

You can extend the library by creating your own custom attributes. This is useful for adding support for database-specific features or for creating your own shorthand attributes.

### Supporting New Database Dialects

The library is designed to be extensible to support different database dialects. You can add support for a new dialect by implementing the necessary logic for generating SQL specific to that dialect.

---

*Generated by Manus AI*

