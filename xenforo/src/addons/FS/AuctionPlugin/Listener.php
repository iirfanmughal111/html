<?php

namespace FS\AuctionPlugin;

use XF\Util\Arr;

class Listener
{


    public static function addonPostInstall(\XF\AddOn\AddOn $addOn, \XF\Entity\AddOn $installedAddOn, array $json, array &$stateChanges)
    {
        // $forumService = \xf::app()->service('FS\AuctionPlugin:Auction\ForumAndFields');

        // $node = $forumService->createNode();
        // $forumService->updateOptionsforum($node->node_id);
        // $forumService->createCustomFields($node->node_id);
        // $forumService->permissionRebuild();
    }
}
