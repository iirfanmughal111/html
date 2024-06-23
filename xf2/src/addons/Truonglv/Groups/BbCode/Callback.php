<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\BbCode;

use XF;
use Truonglv\Groups\App;
use XF\Template\Templater;
use XF\BbCode\Renderer\Html;
use XF\BbCode\Renderer\ApiHtml;
use Truonglv\Groups\Entity\Group;
use XF\BbCode\Renderer\EmailHtml;
use XF\BbCode\Renderer\SimpleHtml;
use XF\BbCode\Renderer\AbstractRenderer;

class Callback
{
    /**
     * @var array
     */
    public static $entityWith = ['Category', 'Feature'];

    /**
     * @param mixed $tagChildren
     * @param mixed $tagOption
     * @param array $tag
     * @param array $options
     * @param AbstractRenderer $renderer
     * @return string
     */
    public static function renderTagGroup($tagChildren, $tagOption, $tag, array $options, AbstractRenderer $renderer)
    {
        $groupId = (int) $tagOption;
        if ($groupId === 0) {
            return $renderer->renderUnparsedTag($tag, $options);
        }

        if (!App::hasPermission('view')
            || !($renderer instanceof Html)
        ) {
            return static::renderTagSimple($groupId);
        }

        if ($renderer instanceof ApiHtml
            || $renderer instanceof EmailHtml
            || $renderer instanceof SimpleHtml
        ) {
            return static::renderTagSimple($groupId);
        }

        $visitor = XF::visitor();
        if ($visitor->user_id > 0) {
            static::$entityWith[] = 'Views|' . $visitor->user_id;
            static::$entityWith[] = 'Members|' . $visitor->user_id;
        }
        
        /** @var Group|null $group */
        $group = XF::em()->find('Truonglv\Groups:Group', $groupId, static::$entityWith);
        if ($group === null || !$group->canView()) {
            return static::renderTagSimple($tagOption);
        }

        $groups = XF::em()->getBasicCollection([$group->group_id => $group]);
        App::groupRepo()->addMembersIntoGroups($groups);

        /** @var Templater $templater */
        $templater = $renderer->getTemplater();

        return $templater->renderTemplate('public:tlg_bb_code_group', [
            'group' => $group
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public static function renderTagSimple($id): string
    {
        $viewPhrase = XF::phrase('tlg_view_group_x', ['id' => $id]);
        $router = XF::app()->router('public');
        $viewLink = $router->buildLink('groups', ['group_id' => $id]);

        return '<a href="' . htmlspecialchars($viewLink) . '">' . $viewPhrase . '</a>';
    }
}
