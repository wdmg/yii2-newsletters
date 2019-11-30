<?php

use yii\db\Migration;

/**
 * Class m191028_164515_newsletters
 */
class m191028_164515_newsletters extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%newsletters}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->text(),

            'subject' => $this->string(255),
            'content' => $this->text(),

            'layouts' => $this->string(255)->notNull(),
            'views' => $this->string(255)->null(),
            'recipients' => $this->text(),

            'unique_token' => $this->string(32)->unique(),
            'status' => $this->boolean()->defaultValue(true),

            'workflow' => $this->text(),
            'params' => $this->text(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(11)->notNull()->defaultValue(0),
            'updated_at' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_by' => $this->integer(11)->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex(
            'idx_newsletters',
            '{{%newsletters}}',
            [
                'title',
                'subject',
                'layouts',
                'views',
                'unique_token',
                'status',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_newsletters', '{{%newsletters}}');
        $this->truncateTable('{{%newsletters}}');
        $this->dropTable('{{%newsletters}}');
    }
}
