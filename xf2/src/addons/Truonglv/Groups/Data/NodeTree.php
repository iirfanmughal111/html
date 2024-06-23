<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Data;

use XF;
use function array_column;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\XF\Entity\Node;

class NodeTree
{
    /**
     * @param Group $group
     * @return \XF\Tree
     */
    public function forumTree(Group $group)
    {
        $archiveNodeId = (int) XF::options()->tl_groups_archiveNodeId;
        /** @var Node|null $withinNode */
        $withinNode = null;

        if ($archiveNodeId > 0) {
            /** @var Node|null $withinNode */
            $withinNode = XF::em()->find('XF:Node', $archiveNodeId);
        }

        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = XF::repository('XF:Node');
        $nodeIds = XF::finder('Truonglv\Groups:Forum')
            ->where('group_id', $group->group_id)
            ->fetchColumns('node_id');

        $nodes = $nodeRepo->findNodesForList($withinNode)
                ->where('node_type_id', 'Forum')
                ->whereIds(array_column($nodeIds, 'node_id'))
                ->fetch();

        $nodes = $nodeRepo->loadNodeTypeDataForNodes($nodes);

        return $nodeRepo->createNodeTree($nodes, $withinNode !== null ? $withinNode->node_id : 0);
    }
}
