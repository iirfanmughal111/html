<?php

namespace FS\PostCounter\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{

    public function actionPostCounter(ParameterBag $params)
    {

        $options = \XF::options();
        $applicable_forum = $options->ca_applicable_forums;
        $user = $this->assertViewableUser($params->user_id);

        $class = \XF::extendClass('FS\PostCounter\Model\PostAndThreadStats');

        /* @var $stats \FS\postCounter\Model\PostAndThreadStats */
        $stats = new $class;

        if ($this->options()->fsApaUseSelf) {
            $postCounter = $stats->getPostAndThreadCountsFromCache($params->user_id);
        } else {
            $postCounter = $stats->getPostAndThreadCounts($params->user_id);
        }

        // filter out stats for which the current user has no view perm 
        if (!in_array('0', $applicable_forum) && !empty($applicable_forum)) {
            foreach ($postCounter as $forumData) {
                if (\XF::visitor()->hasNodePermission($forumData['node_id'], 'view')) {
                    if (in_array($forumData['node_id'], $applicable_forum)) {

                        $postCounterFiltered[] = $forumData;
                    }
                }
            }
        }

        if (!isset($postCounterFiltered)) {
            $postCounterFiltered = array();
        }

        $viewParams = [
            'user' => $user,
            'postCounters' => $postCounterFiltered,
            'hasCreatedAThread' => $this->_containsThreadCount($postCounter)
        ];

        return $this->view('FS\PostCounter:PostCounter', 'fs_post_counter_index', $viewParams);
    }


    /**
     * Returns true if there is at least one forum with a thread count
     * 
     * @param array $postCounter
     * @return boolean
     */
    protected function _containsThreadCount($postCounter)
    {
        foreach ($postCounter as $postCount) {
            if (array_key_exists('thread_count', $postCount)) {
                return true;
            }
        }

        return false;
    }
}
