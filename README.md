# db-mapping

![License](https://img.shields.io/badge/license-MIT-blue.svg)

## Описание

`Flytachi/DbMapping` - это PHP-библиотека, предназначенная для упрощения определения и управления структурой базы данных с использованием PHP-атрибутов. Она позволяет разработчикам описывать таблицы, столбцы, индексы и ограничения непосредственно в своих PHP-классах, а затем генерировать соответствующие SQL-запросы для различных диалектов баз данных.

Эта библиотека идеально подходит для проектов, которым требуется гибкий и декларативный подход к миграциям баз данных и управлению схемами, особенно в контексте объектно-реляционного сопоставления (ORM) или инструментов построения запросов.

## Особенности

*   **Декларативное определение схемы:** Определяйте структуру базы данных с помощью интуитивно понятных PHP-атрибутов.
*   **Поддержка различных типов данных:** Включает атрибуты для общих типов данных SQL, таких как `VARCHAR`, `INTEGER`, `BOOLEAN`, `DECIMAL`, `TEXT`, `TIMESTAMP`, `JSON` и другие.
*   **Управление индексами и ограничениями:** Легко определяйте первичные ключи, уникальные индексы, обычные индексы, внешние ключи и ограничения проверки.
*   **Генерация SQL:** Автоматически генерируйте SQL-запросы `CREATE TABLE`, `ALTER TABLE` и другие DDL-операции.
*   **Расширяемая архитектура:** Разработана с учетом расширяемости, что позволяет легко добавлять новые атрибуты и типы структур.
*   **Поддержка диалектов:** Возможность генерации SQL для различных диалектов баз данных (например, MySQL, PostgreSQL).

## Установка

Вы можете установить эту библиотеку с помощью Composer:

```bash
composer require flytachi/db-mapping
```

## Использование

### Определение модели таблицы

Чтобы определить таблицу базы данных, создайте PHP-класс и используйте атрибут `#[DbMap]` на уровне класса. Затем используйте другие атрибуты для определения столбцов и их свойств.

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
*   `#[NullableIs(false)]` и `#[DefaultVal("0")]` определяют столбец `status` как `NOT NULL` со значением по умолчанию `0`.
*   `#[ForeignKey(...)]` определяет внешний ключ для столбца `role_id`, ссылающийся на таблицу `roles` и столбец `id`, с каскадным удалением.

### Генерация SQL-схемы

Библиотека может генерировать SQL-запросы для создания таблиц на основе определенных классов. (Для полной реализации потребуется дополнительная логика для сканирования классов и построения объектов `Table`.)

```php
// Пример псевдокода для генерации SQL

// Предположим, у вас есть механизм для загрузки классов и их атрибутов
// $tableStructure = new Table(
//     'users',
//     [
//         new Column('id', 'INT', false, null, [new Index('PRIMARY', ['id'], IndexType::PRIMARY)]),
//         new Column('name', 'VARCHAR(255)', false),
//         new Column('email', 'VARCHAR(255)', false, null, [new Index('email_unique', ['email'], IndexType::UNIQUE)]),
//         new Column('status', 'INT', false, '0'),
//         new Column('role_id', 'INT', false, null, null, new ForeignKey('roles', 'id', FKAction::CASCADE))
//     ]
// );

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

## Тестирование

В репозитории есть несколько тестовых файлов в директории `tests/` (`test1.php`, `test2.php`, `test3.php`), которые демонстрируют использование библиотеки.

## Вклад

Приветствуются любые вклады. Пожалуйста, ознакомьтесь с `phpcs.xml` для стандартов кодирования.

## Лицензия

Этот проект распространяется под лицензией MIT. Подробности см. в файле `LICENSE`.

## Контакты

По вопросам и предложениям обращайтесь к автору: jasur.rakhmatov03@gmail.com
