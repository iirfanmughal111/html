<?php

namespace FS\ForumGroups\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{

    public function actionPostThread(ParameterBag $params)
    {
        $parent =  parent::actionPostThread($params);

        $nodeGroup = $this->em()->find('XF:Node', $params->node_id);

        if (($nodeGroup->parent_node_id == $this->app()->options()->fs_forum_groups_applicable_forum) && ($nodeGroup['node_state'] != 'visible')) {
            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
        }

        return $parent;
    }
}
