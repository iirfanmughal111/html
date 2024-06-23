<?php

namespace FS\PostCounter;

use XF\AddOn\AbstractSetup;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{

    const PA_TABLE_NAME = 'fs_post_counter';

    public function install(array $stepParams = [])
    {
        $this->_createCacheTable();
        $this->updatePostCounter();
    }

    public function upgrade(array $stepParams = [])
    {
        $this->_createCacheTable();  // previous version 2.0.0 did not have a cache 
    }

    public function uninstall(array $stepParams = [])
    {
        $this->schemaManager()->dropTable(self::PA_TABLE_NAME);
    }

    protected function _createCacheTable()
    {
        $this->schemaManager()->createTable(
            self::PA_TABLE_NAME,
            function (Create $table) {
                $table->addColumn('user_id', 'mediumint');
                $table->addColumn('node_id', 'smallint');
                $table->addColumn('post_count', 'smallint');
                $table->addColumn('thread_count', 'smallint');
                $table->addPrimaryKey(['user_id', 'node_id']);
            }
        );
    }

    public function updatePostCounter()
    {
        $db = \XF::db();
        $pcTableName = \XF::em()->getEntityStructure('FS\PostCounter:PostCounter')->table;

        $class = \XF::extendClass('FS\PostCounter\Model\PostAndThreadStats');

        /* @var $stats \FS\PostCounter\Model\PostAndThreadStats */
        $stats = new $class;

        // delete old data                
        $db->query("DELETE FROM $pcTableName");

        // get highest user_id
        $lastUserId = $db->fetchOne('SELECT COUNT(*) FROM xf_user');

        // update post and thread counts
        for ($userId = 1; $userId <= $lastUserId; $userId++) {
            $allStats = $stats->getPostAndThreadCounts($userId);

            foreach ($allStats as $subForumStats) {
                $nodeId = $subForumStats['node_id'];
                $postCount = $subForumStats['post_count'];
                $threadCount = $subForumStats['thread_count'];

                $db->query("INSERT INTO $pcTableName VALUES ($userId, $nodeId, $postCount, $threadCount)");
            }
        }
    }
}
