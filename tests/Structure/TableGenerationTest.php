<?php

declare(strict_types=1);

require_once __DIR__ .
    '/../../vendor/autoload.php';

use Flytachi\DbMapping\Constants\FKAction;
use Flytachi\DbMapping\Constants\IndexType;
use Flytachi\DbMapping\Structure\Column;
use Flytachi\DbMapping\Structure\ForeignKey;
use Flytachi\DbMapping\Structure\Index;
use Flytachi\DbMapping\Structure\Table;

class TableGenerationTest
{
    private string $dialect = 'pgsql';

    public function testUsersTableGeneration(): void
    {
        $table = new Table(
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

        $sql = $table->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE TABLE IF NOT EXISTS users'), 'Users table SQL missing CREATE TABLE');
        assert(str_contains($sql, 'id SERIAL'), 'Users table SQL missing id column');
        assert(str_contains($sql, 'username VARCHAR(50) NOT NULL'), 'Users table SQL missing username column');
        assert(str_contains($sql, 'email VARCHAR(100) NOT NULL'), 'Users table SQL missing email column');
        assert(str_contains($sql, 'password_hash VARCHAR(255) NOT NULL'), 'Users table SQL missing password_hash column');
        assert(str_contains($sql, 'created_at TIMESTAMP NOT NULL'), 'Users table SQL missing created_at column');
        assert(str_contains($sql, 'PRIMARY KEY (id)'), 'Users table SQL missing PRIMARY KEY');
        assert(str_contains($sql, 'CREATE UNIQUE INDEX users_username_udx ON users (username)'), 'Users table SQL missing UNIQUE INDEX for username');
        assert(str_contains($sql, 'CREATE UNIQUE INDEX users_email_udx ON users (email)'), 'Users table SQL missing UNIQUE INDEX for email');
        echo "Users table generation test passed.\n";
    }

    public function testPostsTableGeneration(): void
    {
        $table = new Table(
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
                new Index(['user_id'], type: IndexType::INDEX),
            ]
        );
        $table->columns[1]->foreignKey = new ForeignKey(
            referencedTable: 'users',
            referencedColumn: 'id',
            onDelete: FKAction::CASCADE
        );

        $sql = $table->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE TABLE IF NOT EXISTS posts'), 'Posts table SQL missing CREATE TABLE');
        assert(str_contains($sql, 'id SERIAL'), 'Posts table SQL missing id column');
        assert(str_contains($sql, 'user_id INT NOT NULL'), 'Posts table SQL missing user_id column');
        assert(str_contains($sql, 'title VARCHAR(255) NOT NULL'), 'Posts table SQL missing title column');
        assert(str_contains($sql, 'content TEXT'), 'Posts table SQL missing content column');
        assert(str_contains($sql, 'published_at TIMESTAMP'), 'Posts table SQL missing published_at column');
        assert(str_contains($sql, 'PRIMARY KEY (id)'), 'Posts table SQL missing PRIMARY KEY');
        assert(str_contains($sql, 'CREATE INDEX posts_user_id_idx ON posts (user_id)'), 'Posts table SQL missing INDEX for user_id');
        assert(str_contains($sql, 'ALTER TABLE posts ADD CONSTRAINT fk_posts_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT'), 'Posts table SQL missing FOREIGN KEY');
        echo "Posts table generation test passed.\n";
    }

    public function testTagsTableGeneration(): void
    {
        $table = new Table(
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

        $sql = $table->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE TABLE IF NOT EXISTS tags'), 'Tags table SQL missing CREATE TABLE');
        assert(str_contains($sql, 'id SERIAL'), 'Tags table SQL missing id column');
        assert(str_contains($sql, 'name VARCHAR(100) NOT NULL'), 'Tags table SQL missing name column');
        assert(str_contains($sql, 'PRIMARY KEY (id)'), 'Tags table SQL missing PRIMARY KEY');
        assert(str_contains($sql, 'CREATE UNIQUE INDEX tags_name_udx ON tags (name)'), 'Tags table SQL missing UNIQUE INDEX for name');
        echo "Tags table generation test passed.\n";
    }

    public function testPostTagsTableGeneration(): void
    {
        $table = new Table(
            name: 'post_tags',
            columns: [
                new Column('post_id', 'INT', nullable: false),
                new Column('tag_id', 'INT', nullable: false),
            ],
            indexes: [
                new Index(['post_id', 'tag_id'], type: IndexType::PRIMARY),
            ]
        );
        $table->columns[0]->foreignKey = new ForeignKey('posts', 'id', FKAction::CASCADE);
        $table->columns[1]->foreignKey = new ForeignKey('tags', 'id', FKAction::CASCADE);

        $sql = $table->toSql($this->dialect);
        assert(str_contains($sql, 'CREATE TABLE IF NOT EXISTS post_tags'), 'Post_tags table SQL missing CREATE TABLE');
        assert(str_contains($sql, 'post_id INT NOT NULL'), 'Post_tags table SQL missing post_id column');
        assert(str_contains($sql, 'tag_id INT NOT NULL'), 'Post_tags table SQL missing tag_id column');
        assert(str_contains($sql, 'PRIMARY KEY (post_id, tag_id)'), 'Post_tags table SQL missing PRIMARY KEY');
        assert(str_contains($sql, 'ALTER TABLE post_tags ADD CONSTRAINT fk_post_tags_post_id FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE RESTRICT'), 'Post_tags table SQL missing FOREIGN KEY for post_id');
        assert(str_contains($sql, 'ALTER TABLE post_tags ADD CONSTRAINT fk_post_tags_tag_id FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE ON UPDATE RESTRICT'), 'Post_tags table SQL missing FOREIGN KEY for tag_id');
        echo "Post_tags table generation test passed.\n";
    }
}

$test = new TableGenerationTest();
$test->testUsersTableGeneration();
$test->testPostsTableGeneration();
$test->testTagsTableGeneration();
$test->testPostTagsTableGeneration();

echo "All Table Generation tests passed successfully!\n";


