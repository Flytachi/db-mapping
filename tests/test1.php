<?php

use Flytachi\DbMapping\Constants\ForeignKeyAction;
use Flytachi\DbMapping\Constants\IndexType;
use Flytachi\DbMapping\Structure\Column;
use Flytachi\DbMapping\Structure\ForeignKey;
use Flytachi\DbMapping\Structure\Index;
use Flytachi\DbMapping\Structure\Table;

require 'vendor/autoload.php';


$dialect = 'pgsql';


// USERS
$table1 = new Table(
    name: 'users',
    columns: [
        new Column('id', 'SERIAL'),
        new Column('username', 'VARCHAR(50)', nullable: false),
        new Column('email', 'VARCHAR(100)', nullable: false),
        new Column('password_hash', 'VARCHAR(255)', nullable: false),
        new Column('created_at', 'TIMESTAMP', nullable: false),
    ],
    indexes: [
        new Index(['id'], type: IndexType::PRIMARY),
        new Index(['username'], type: IndexType::UNIQUE),
        new Index(['email'], type: IndexType::UNIQUE),
    ]
);

// POSTS
$table2 = new Table(
    name: 'posts',
    columns: [
        new Column('id', 'SERIAL'),
        new Column('user_id', 'INT', nullable: false),
        new Column('title', 'VARCHAR(255)', nullable: false),
        new Column('content', 'TEXT', nullable: true),
        new Column('published_at', 'TIMESTAMP', nullable: true),
    ],
    indexes: [
        new Index(['id'], type: IndexType::PRIMARY),
        new Index(['user_id']),
    ]
);
$table2->columns[1]->foreignKey = new ForeignKey(
    referencedTable: 'users',
    referencedColumn: 'id',
    onDelete: ForeignKeyAction::CASCADE
);

// TAGS
$table3 = new Table(
    name: 'tags',
    columns: [
        new Column('id', 'SERIAL'),
        new Column('name', 'VARCHAR(100)', nullable: false),
    ],
    indexes: [
        new Index(['id'], type: IndexType::PRIMARY),
        new Index(['name'], type: IndexType::UNIQUE),
    ]
);

// POSTS_TAGS (many-to-many)
$table4 = new Table(
    name: 'post_tags',
    columns: [
        new Column('post_id', 'INT', nullable: false),
        new Column('tag_id', 'INT', nullable: false),
    ],
    indexes: [
        new Index(['post_id', 'tag_id'], type: IndexType::PRIMARY),
    ]
);
$table4->columns[0]->foreignKey = new ForeignKey('posts', 'id', ForeignKeyAction::CASCADE);
$table4->columns[1]->foreignKey = new ForeignKey('tags', 'id', ForeignKeyAction::CASCADE);

echo $table1->toSql($dialect) . "\n\n";
echo $table2->toSql($dialect) . "\n\n";
echo $table3->toSql($dialect) . "\n\n";
echo $table4->toSql($dialect) . "\n\n";
