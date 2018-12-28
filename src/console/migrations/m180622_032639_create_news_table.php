<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 */
class m180622_032639_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('news', [

            'id'    => $this->primaryKey(),
            'title' => $this->string(),

            'owner_id' => $this->integer()->notNull(),
            'team_id'  => $this->integer(),

            'country_id' => $this->integer(),
            'city_id'    => $this->integer(),
            'region_id'  => $this->integer(),

            'description' => $this->text(),

            'photo_id' => $this->integer(),

            'status' => $this->smallInteger(1),
            'type'   => $this->smallInteger(1),

            'post_date'       => $this->dateTime(),
            'post_date_close' => $this->dateTime(),

            'views'  => $this->integer()->notNull()->defaultValue(0),
            'likes'  => $this->integer()->notNull()->defaultValue(0),
            'shares' => $this->integer()->notNull()->defaultValue(0),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->createTable('news_tag', [
            'id'      => $this->primaryKey(),
            'news_id' => $this->integer(),
            'name'    => $this->string(20)
        ]);




        $this->addForeignKey('fk-news-owner', 'news', 'owner_id', 'user', 'id');
        $this->addForeignKey('fk-news-team', 'news', 'team_id', 'team', 'id');

        $this->addForeignKey('fk-news-countries', 'news', 'country_id', 'countries', 'country_id');
        $this->addForeignKey('fk-news-cities', 'news', 'city_id', 'cities', 'city_id');
        $this->addForeignKey('fk-news-regions', 'news', 'region_id', 'regions', 'region_id');

        $this->addForeignKey('fk-news-file', 'news', 'photo_id', 'files', 'id');

        $this->addForeignKey('fk-news_tag-news', 'news_tag', 'news_id', 'news', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('news');
        $this->dropTable('news_tag');
        $this->dropTable('news_counters');
    }
}
