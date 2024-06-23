<?php

namespace FS\PostCounter\Model;

class PostAndThreadStats
{

    protected function _getDb()
    {
        return \XF::db();
    }

    /**
     * Get stored stats of a specific user
     * 
     * @param type $userId
     * @return type
     */
    public function getPostAndThreadCountsFromCache($userId)
    {

        $pcFinder = \XF::finder('FS\PostCounter:PostCounter');


        $postCounters = $pcFinder->where('user_id', $userId)
            ->order('post_count', 'DESC')
            ->fetch();

        if ($postCounters->count() === 0) {
            return array();
        }

        // convert Entities to arrays
        foreach ($postCounters as $postCounter) {
            $postCountersArray[] = [
                'node_id' => $postCounter->node_id,
                'post_count' => $postCounter->post_count,
                'thread_count' => $postCounter->thread_count
            ];
        }

        // get all forum titles                  
        $forumFinder = \XF::finder('XF:Forum');
        $forums = $forumFinder->fetch();

        foreach ($forums as $forum) {
            $forumTitles[$forum->node_id] = $forum->title;
        }

        // add title to each post area
        foreach ($postCountersArray as &$postCounter) {
            $postCounter['title'] = $forumTitles[$postCounter['node_id']];
        }

        return $postCountersArray;
    }


    /**
     * Calculate current stats of a specific user
     * 
     * Returned array contains the following info for each forum:
     *    [ node_id, title, post_count, thread_count ]
     * 
     * @param int $userId
     * @return array
     */
    public function getPostAndThreadCounts($userId)
    {
        $postCounts = $this->_getPostCounts($userId);
        $threadCounts = $this->_getThreadCounts($userId);

        // add threads counts to the posts conuts
        foreach ($postCounts as &$postEntry) {
            $postEntry['thread_count'] = 0;

            foreach ($threadCounts as $threadEntry) {
                if ($threadEntry['node_id'] == $postEntry['node_id']) {
                    $postEntry['thread_count'] = $threadEntry['thread_count'];
                }
            }
        }

        return $postCounts;
    }


    /**
     * Get all post counts of a specific user
     * 
     * @param string $userId 
     * @return array 
     */
    protected function _getPostCounts($userId)
    {
        $db = $this->_getDb();

        $stmt = 'SELECT xf_node.node_id, xf_node.title, COUNT(post_id) AS post_count 
                 FROM xf_post 
                   JOIN xf_thread ON xf_post.thread_id = xf_thread.thread_id 
                   JOIN xf_node ON xf_thread.node_id = xf_node.node_id
                 WHERE xf_post.user_id = ? AND 
                       xf_post.message_state = \'visible\' AND
                       xf_thread.discussion_state =\'visible\' AND
                       xf_node.node_type_id = \'Forum\'                       
                 GROUP BY xf_node.title
                 ORDER BY post_count DESC';

        $result = $db->fetchAll($stmt, $userId);

        return $result;
    }


    /**
     * Get all thread counts of a specific user
     * 
     * @param type $userId
     * @return array
     */
    protected function _getThreadCounts($userId)
    {
        $db = $this->_getDb();

        $stmt = 'SELECT xf_thread.node_id, COUNT(xf_thread.thread_id) AS thread_count
                 FROM xf_thread
                 WHERE xf_thread.user_id = ? AND 
                       xf_thread.discussion_state =\'visible\'
                 GROUP BY xf_thread.node_id
                 ORDER BY thread_count DESC';

        $result = $db->fetchAll($stmt, $userId);

        return $result;
    }
}
