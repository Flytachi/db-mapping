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
