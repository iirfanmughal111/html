<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XF\Repository;

use Truonglv\Groups\App;

/**
 * Class Node
 * @package Truonglv\Groups\XF\Repository
 * @inheritdoc
 */
class Node extends XFCP_Node
{
    /**
     * @param mixed $nodes
     * @return mixed
     */
    public function loadNodeTypeDataForNodes($nodes)
    {
        $nodes = parent::loadNodeTypeDataForNodes($nodes);

        App::groupRepo()->loadGroupsForNodes($nodes);

        return $nodes;
    }

    /**
     * @param \XF\Entity\Node|null $withinNode
     * @param mixed $with
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getFullNodeList(\XF\Entity\Node $withinNode = null, $with = null)
    {
        $nodes = parent::getFullNodeList($withinNode, $with);

        App::groupRepo()->loadGroupsForNodes($nodes);

        return $nodes;
    }
}
