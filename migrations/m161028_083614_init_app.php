<?php

use yii\db\Migration;

class m161028_083614_init_app extends Migration
{
    public function up()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array('auth_assignment', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_assignment}}', [
                    'item_name' => 'VARCHAR(64) NOT NULL',
                    'user_id' => 'VARCHAR(64) NOT NULL',
                    'created_at' => 'INT(11) NULL',
                    3 => 'PRIMARY KEY (`item_name`, `user_id`)',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_item', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_item}}', [
                    'name' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`name`)',
                    'type' => 'INT(11) NOT NULL',
                    'description' => 'TEXT NULL',
                    'rule_name' => 'VARCHAR(64) NULL',
                    'data' => 'TEXT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_item_child', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_item_child}}', [
                    'parent' => 'VARCHAR(64) NOT NULL',
                    'child' => 'VARCHAR(64) NOT NULL',
                    2 => 'PRIMARY KEY (`parent`, `child`)',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_rule', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_rule}}', [
                    'name' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`name`)',
                    'data' => 'TEXT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('news', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%news}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'title' => 'VARCHAR(512) NOT NULL',
                    'preview_text' => 'TEXT NOT NULL',
                    'body' => 'TEXT NOT NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('notifications', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%notifications}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'model_name' => 'VARCHAR(512) NOT NULL',
                    'events_list' => 'INT(11) NOT NULL',
                    'level' => 'INT(11) NOT NULL',
                    'user_groups' => 'INT(11) NOT NULL',
                    'template_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('templates', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%templates}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'title' => 'VARCHAR(512) NOT NULL',
                    'body' => 'TEXT NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('user', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%user}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'username' => 'VARCHAR(255) NOT NULL',
                    'auth_key' => 'VARCHAR(32) NOT NULL',
                    'password_hash' => 'VARCHAR(255) NOT NULL',
                    'approve_token' => 'VARCHAR(255) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'active' => 'TINYINT(4) NOT NULL',
                    'notifications_level' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }


        $this->createIndex('idx_rule_name_5034_00','auth_item','rule_name',0);
        $this->createIndex('idx_type_5034_01','auth_item','type',0);
        $this->createIndex('idx_child_5045_02','auth_item_child','child',0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_auth_item_5015_00','{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_rule_5032_01','{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_item_5043_02','{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_item_5043_03','{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array('auth_assignment', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_assignment}}', [
                    'item_name' => 'VARCHAR(64) NOT NULL',
                    'user_id' => 'VARCHAR(64) NOT NULL',
                    'created_at' => 'INT(11) NULL',
                    3 => 'PRIMARY KEY (`item_name`, `user_id`)',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_item', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_item}}', [
                    'name' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`name`)',
                    'type' => 'INT(11) NOT NULL',
                    'description' => 'TEXT NULL',
                    'rule_name' => 'VARCHAR(64) NULL',
                    'data' => 'TEXT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_item_child', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_item_child}}', [
                    'parent' => 'VARCHAR(64) NOT NULL',
                    'child' => 'VARCHAR(64) NOT NULL',
                    2 => 'PRIMARY KEY (`parent`, `child`)',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('auth_rule', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%auth_rule}}', [
                    'name' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`name`)',
                    'data' => 'TEXT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('news', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%news}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'title' => 'VARCHAR(512) NOT NULL',
                    'preview_text' => 'TEXT NOT NULL',
                    'body' => 'TEXT NOT NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('notifications', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%notifications}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'model_name' => 'VARCHAR(512) NOT NULL',
                    'events_list' => 'INT(11) NOT NULL',
                    'level' => 'INT(11) NOT NULL',
                    'user_groups' => 'INT(11) NOT NULL',
                    'template_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('templates', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%templates}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'title' => 'VARCHAR(512) NOT NULL',
                    'body' => 'TEXT NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('user', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%user}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'username' => 'VARCHAR(255) NOT NULL',
                    'auth_key' => 'VARCHAR(32) NOT NULL',
                    'password_hash' => 'VARCHAR(255) NOT NULL',
                    'approve_token' => 'VARCHAR(255) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'active' => 'TINYINT(4) NOT NULL',
                    'notifications_level' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }


        $this->createIndex('idx_rule_name_5034_00','auth_item','rule_name',0);
        $this->createIndex('idx_type_5034_01','auth_item','type',0);
        $this->createIndex('idx_child_5045_02','auth_item_child','child',0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_auth_item_5015_00','{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_rule_5032_01','{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_item_5043_02','{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_auth_item_5043_03','{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'NO ACTION' );
        $this->execute('SET foreign_key_checks = 1;');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
