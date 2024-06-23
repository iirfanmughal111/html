<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Option;

use XF;
use XF\Entity\Forum;
use XF\Entity\Option;
use function in_array;
use function str_repeat;
use Truonglv\Groups\App;
use XF\PrintableException;
use XF\Option\AbstractOption;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Job\NodeParent;

class Node extends AbstractOption
{
    /**
     * @var array
     */
    public static $allowArchiveNodeTypes = ['Category', 'Forum'];

    /**
     * @param Option $option
     * @param array $htmlParams
     * @return string
     */
    public static function renderArchiveNodeId(\XF\Entity\Option $option, array $htmlParams)
    {
        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = XF::repository('XF:Node');

        $nodeList = $nodeRepo->getFullNodeList();
        $choices = [
            0 => ['_type' => 'option', 'value' => 0, 'label' => XF::phrase('(none)')]
        ];

        foreach ($nodeRepo->createNodeTree($nodeList)->getFlattened() as $entry) {
            /** @var \Truonglv\Groups\XF\Entity\Node $node */
            $node = $entry['record'];

            if ($entry['depth']) {
                $prefix = str_repeat('--', $entry['depth']) . ' ';
            } else {
                $prefix = '';
            }

            $choices[$node->node_id] = [
                'value' => $node->node_id,
                'label' => $prefix . $node->title
            ];

            $choices[$node->node_id]['disabled'] = false;
            if (!in_array($node->node_type_id, static::$allowArchiveNodeTypes, true)) {
                $choices[$node->node_id]['disabled'] = 'disabled';
            }
        }

        return self::getTemplater()->formSelectRow(
            self::getControlOptions($option, $htmlParams),
            $choices,
            self::getRowOptions($option, $htmlParams)
        );
    }

    /**
     * @param mixed $value
     * @param Option $option
     * @return bool
     * @throws PrintableException
     */
    public static function verifyArchiveNodeId(& $value, Option $option)
    {
        if ($value > 0) {
            /** @var \Truonglv\Groups\XF\Entity\Node|null $node */
            $node = XF::em()->find('XF:Node', $value);

            if ($node === null
                || !in_array($node->node_type_id, static::$allowArchiveNodeTypes, true)
            ) {
                throw new PrintableException(XF::phrase('tlg_please_select_valid_archive_node'));
            }

            if (App::isEnabledForums()) {
                $nodeData = $node->getData();
                if ($nodeData instanceof Forum) {
                    /** @var Group|null $group */
                    $group = App::getGroupEntityFromEntity($node);
                    if ($group !== null) {
                        throw new PrintableException(XF::phrase('tlg_please_select_valid_archive_node'));
                    }
                }
            }

            $value = $node->node_id;
        }

        if ($option->getExistingValue('option_value') != $value
            && App::isEnabledForums()
        ) {
            XF::app()
                ->jobManager()
                ->enqueueUnique(
                    'tl_groups.archiveNodeId',
                    NodeParent::class,
                    [
                        'old_node_id' => $option->getExistingValue('option_value'),
                        'new_node_id' => $value
                    ]
                );
        }

        return true;
    }
}
