<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Option;

use XF;
use Truonglv\Groups\App;
use XF\Option\AbstractOption;

class DeleteForumAction extends AbstractOption
{
    /**
     * @param \XF\Entity\Option $option
     * @param array $htmlParams
     * @return string
     */
    public static function renderOption(\XF\Entity\Option $option, array $htmlParams)
    {
        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = XF::repository('XF:Node');
        $nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());

        return self::getTemplate('admin:tlg_option_delete_forum_action', $option, $htmlParams, [
            'nodeTree' => $nodeTree
        ]);
    }

    /**
     * @param array $value
     * @param \XF\Entity\Option $option
     * @return bool
     */
    public static function verifyOption(array & $value, \XF\Entity\Option $option)
    {
        if (!App::isEnabledForums()) {
            return true;
        }

        if ($value['type'] == 1) {
            if ($value['node_id'] > 0) {
                /** @var \Truonglv\Groups\XF\Entity\Node|mixed $node */
                $node = XF::em()->find('XF:Node', $value['node_id']);
                $groupId = App::getGroupIdFromEntity($node);
                if ($groupId < 1 && $node->node_type_id === 'Forum') {
                    return true;
                }
            }

            $option->error(XF::phrase('tlg_please_select_valid_archive_node'), $option->option_id);

            return false;
        }

        return true;
    }
}
