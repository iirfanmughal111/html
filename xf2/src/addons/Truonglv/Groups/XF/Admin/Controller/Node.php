<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XF\Admin\Controller;

use XF\Tree;
use function in_array;
use XF\Mvc\Reply\View;
use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Forum;
use XF\Mvc\Entity\AbstractCollection;

class Node extends XFCP_Node
{
    public function actionIndex()
    {
        $response = parent::actionIndex();
        if ($response instanceof View) {
            /** @var Tree $nodeTree */
            $nodeTree = $response->getParam('nodeTree');
            /** @var AbstractCollection $nodes */
            $nodes = $nodeTree->getAllData();

            $selectedTab = $this->filter('tab', 'str');

            $groupNodeIds = App::groupRepo()->getGroupNodeIds();
            $nodes = $nodes->filter(function (\XF\Entity\Node $node) use ($groupNodeIds, $selectedTab) {
                $inArray = in_array($node->node_id, $groupNodeIds, true);

                return $selectedTab === 'group_nodes' ? $inArray : $inArray === false;
            });

            $response->setParam('nodeTree', new Tree($nodes, 'parent_node_id'));
            $response->setParam('tlgNodeListTabs', true);
            $response->setParam('tlgSelectedTab', $selectedTab);

            if ($selectedTab === 'group_nodes') {
                $groupForums = $this->finder('Truonglv\Groups:Forum')
                    ->with('Group')
                    ->where('node_id', $nodes->keys())
                    ->fetch();

                $nodeTypeHints = [];
                /** @var Forum $groupForum */
                foreach ($groupForums as $groupForum) {
                    if ($groupForum->Group !== null) {
                        $nodeTypeHints[$groupForum->node_id] = $groupForum->Group->name;
                    }
                }

                $response->setParam('tlgNodeTypeHints', $nodeTypeHints);
            }
        }

        return $response;
    }
}
