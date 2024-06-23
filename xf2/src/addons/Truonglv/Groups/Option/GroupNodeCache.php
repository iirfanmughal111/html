<?php

namespace Truonglv\Groups\Option;

use XF;
use XF\Entity\Option;
use function in_array;
use function var_export;
use function json_encode;
use InvalidArgumentException;
use XF\Option\AbstractOption;
use Truonglv\Groups\Entity\Forum;

class GroupNodeCache extends AbstractOption
{
    const OPTION_ID = 'tl_groups_groupNodeCache';

    /**
     * @param Option $option
     * @param array $htmlParams
     * @return string
     */
    public static function renderOption(Option $option, array $htmlParams)
    {
        if (!XF::$debugMode) {
            return '<span data-value="' . XF::escapeString(json_encode($option->option_value)) . '"></span>';
        }

        return self::OPTION_ID . ' => ' . var_export($option->option_value, true);
    }

    /**
     * @param int $nodeId
     * @return int
     */
    public static function getGroupId($nodeId)
    {
        $value = XF::options()->offsetGet(self::OPTION_ID);

        return isset($value[$nodeId]) ? $value[$nodeId] : 0;
    }

    /**
     * @param array $groupIds
     * @return array
     */
    public static function getNodeIds(array $groupIds)
    {
        $nodeIds = [];
        $value = XF::options()->offsetGet(self::OPTION_ID);

        foreach ($value as $nodeId => $groupId) {
            if (in_array($groupId, $groupIds, true)) {
                $nodeIds[] = $nodeId;
            }
        }

        return $nodeIds;
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public static function rebuildCache()
    {
        $cache = XF::db()->fetchPairs('
            SELECT `node_id`, `group_id`
            FROM `xf_tl_group_forum`
        ');

        $option = self::getOptionInternal();
        $option->option_value = $cache;
        $option->save();

        self::updateGlobalOptions($option);
    }

    /**
     * @param Forum $forum
     * @throws \XF\PrintableException
     * @return void
     */
    public static function onGroupForumSaved(Forum $forum)
    {
        $option = self::getOptionInternal();

        $value = $option->option_value;
        $value[$forum->node_id] = $forum->group_id;

        $option->option_value = $value;
        $option->save();
        self::updateGlobalOptions($option);
    }

    /**
     * @param int $nodeId
     * @throws \XF\PrintableException
     * @return void
     */
    public static function onGroupForumDeleted($nodeId)
    {
        $option = self::getOptionInternal();

        $value = $option->option_value;
        if (isset($value[$nodeId])) {
            unset($value[$nodeId]);

            $option->option_value = $value;
            $option->save();
            self::updateGlobalOptions($option);
        }
    }

    /**
     * @param int $deletedGroupId
     * @throws \XF\PrintableException
     * @return void
     */
    public static function onGroupDeleted($deletedGroupId)
    {
        $option = self::getOptionInternal();

        $value = (array) $option->option_value;
        foreach ($value as $nodeId => $groupId) {
            if ($groupId == $deletedGroupId) {
                unset($value[$nodeId]);
            }
        }

        $option->option_value = $value;
        $option->save();

        self::updateGlobalOptions($option);
    }

    /**
     * @param Option $option
     * @return void
     */
    private static function updateGlobalOptions(Option $option)
    {
        $options = XF::options();
        $options->offsetSet(self::OPTION_ID, $option->option_value);
    }

    /**
     * @return Option
     */
    private static function getOptionInternal()
    {
        /** @var Option|null $option */
        $option = XF::em()->find('XF:Option', self::OPTION_ID);
        if ($option === null) {
            throw new InvalidArgumentException(
                'Missing option (' . self::OPTION_ID . '). '
                . 'Please rebuild add-on to fix missing'
            );
        }

        return $option;
    }
}
