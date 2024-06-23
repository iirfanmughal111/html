<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups;

use XF;
use XF\Container;
use function count;
use XF\Entity\Forum;
use function explode;
use function array_map;
use function array_diff;
use XF\Entity\OptionGroup;
use Truonglv\Groups\Entity\Group;

class Listener
{
    /**
     * @param \XF\App $app
     * @throws \XF\Db\Exception
     * @return void
     */
    public static function app_setup(\XF\App $app)
    {
        $container = $app->container();

        $container[App::CONTAINER_KEY_CUSTOM_FIELDS] = $app->fromRegistry(
            App::FIELD_REGISTRY_KEY_NAME,
            function (\XF\Container $c) {
                return $c['em']->getRepository('Truonglv\Groups:Field')->rebuildFieldCache();
            },
            function (array $fields) use ($app) {
                $class = $app->extendClass('XF\CustomField\DefinitionSet');

                return new $class($fields);
            }
        );

        $container->factory(
            App::CONTAINER_KEY_MEMBER_ROLE,
            function ($class, array $arguments, Container $container) {
                $class = XF::stringToClass($class, '%s\MemberRole\%s');
                $class = XF::extendClass($class);

                return $container->createObject($class, $arguments);
            }
        );
    }

    /**
     * @param Group $group
     * @return void
     */
    public static function addContentLanguageResponseHeader(Group $group)
    {
        if ($group->language_code !== '' && App::isEnabledLanguage()) {
            $response = XF::app()->response();

            // @docs https://www.w3.org/International/questions/qa-http-and-lang
            $response->header('Content-Language', $group->language_code);
        }
    }

    /**
     * @param \XF\Service\User\ContentChange $changeService
     * @param array $updates
     * @return void
     */
    public static function user_content_change_init(\XF\Service\User\ContentChange $changeService, array & $updates)
    {
        $updates['xf_tl_group'] = [['owner_user_id', 'owner_username']];
        $updates['xf_tl_group_member'] = [['user_id', 'username']];
        $updates['xf_tl_group_event'] = [['user_id', 'username']];
        $updates['xf_tl_group_comment'] = [['user_id', 'username']];
        $updates['xf_tl_group_event_watch'] = [['user_id', 'emptyable' => true]];
        $updates['xf_tl_group_view'] = [['user_id', 'emptyable' => true]];
        $updates['xf_tl_group_post'] = [['user_id', 'username']];
    }

    /**
     * @param \XF\Service\User\DeleteCleanUp $deleteService
     * @param array $deletes
     * @return void
     */
    public static function user_delete_clean_init(\XF\Service\User\DeleteCleanUp $deleteService, array & $deletes)
    {
        $deletes['xf_tl_group'] = 'owner_user_id = ?';
        $deletes['xf_tl_group_member'] = 'user_id = ?';
        $deletes['xf_tl_group_event'] = 'user_id = ?';
        $deletes['xf_tl_group_comment'] = 'user_id = ?';
        $deletes['xf_tl_group_event_watch'] = 'user_id = ?';
        $deletes['xf_tl_group_event_guest'] = 'user_id = ?';
        $deletes['xf_tl_group_view'] = 'user_id = ?';
        $deletes['xf_tl_group_post'] = 'user_id = ?';
        $deletes['xf_tl_group_user_cache'] = 'user_id = ?';
    }

    /**
     * @param \XF\Template\Templater $templater
     * @param string $type
     * @param string $template
     * @param string $name
     * @param array $arguments
     * @param array $globalVars
     * @return void
     */
    public static function templaterMacroPreRenderOMOFB(
        \XF\Template\Templater $templater,
        & $type,
        & $template,
        & $name,
        array & $arguments,
        array & $globalVars
    ) {
        // admin:option_macros:option_form_block
        /** @var OptionGroup|null $optionGroup */
        $optionGroup = $arguments['group'] ?? null;
        if ($optionGroup === null || $optionGroup->group_id !== 'tl_groups') {
            return;
        }

        $template = 'tlg_option_macros';
        $arguments['headers'] = [
            'generalOptions' => [
                'label' => XF::phrase('general_options'),
                'active' => true,
                'minDisplayOrder' => 0,
                'maxDisplayOrder' => 1999
            ],
            'eventOptions' => [
                'label' => XF::phrase('tlg_event_options'),
                'active' => false,
                'minDisplayOrder' => 2000,
                'maxDisplayOrder' => 2999
            ],
            'forumDiscussionOptions' => [
                'label' => XF::phrase('tlg_forum_discussion_options'),
                'active' => false,
                'minDisplayOrder' => 3000,
                'maxDisplayOrder' => 4999
            ],
            'resourceOptions' => [
                'label' => XF::phrase('tlg_resource_options'),
                'active' => false,
                'minDisplayOrder' => 5000,
                'maxDisplayOrder' => 5999
            ]
        ];
    }

    /**
     * @param \XF\SubContainer\Import $container
     * @param Container $parentContainer
     * @param array $importers
     * @return void
     */
    public static function import_importer_class(
        \XF\SubContainer\Import $container,
        \XF\Container $parentContainer,
        array & $importers
    ) {
        $importers[] = 'Truonglv\Groups:Group';
    }

    /**
     * @param string $rule
     * @param array $data
     * @param \XF\Entity\User $user
     * @param mixed $returnValue
     * @return void
     */
    public static function criteriaUser(string $rule, array $data, \XF\Entity\User $user, & $returnValue)
    {
        if ($rule === 'tlg_has_group') {
            $total = XF::finder('Truonglv\Groups:Member')
                ->where('user_id', $user->user_id)
                ->where('member_state', App::MEMBER_STATE_VALID)
                ->total();
            if ($total > 0) {
                $returnValue = true;
            }
        } elseif ($rule === 'tlg_has_manage_group') {
            $total = XF::finder('Truonglv\Groups:Member')
                ->where('user_id', $user->user_id)
                ->where('member_state', App::MEMBER_STATE_VALID)
                ->where('member_role_id', App::memberRoleRepo()->getStaffRoleIds())
                ->total();
            if ($total > 0) {
                $returnValue = true;
            }
        } elseif ($rule === 'tlg_member_of_groups') {
            $groupIds = $data['ids'] ?? '';
            $groupIds = explode(',', $groupIds);
            $groupIds = array_map('intval', $groupIds);
            $groupIds = array_diff($groupIds, [0]);

            if (count($groupIds) > 0) {
                $total = XF::finder('Truonglv\Groups:Member')
                    ->where('user_id', $user->user_id)
                    ->where('member_state', App::MEMBER_STATE_VALID)
                    ->where('group_id', $groupIds)
                    ->total();
                if ($total > 0) {
                    $returnValue = true;
                }
            }
        }
    }

    public static function appPubRenderPage(
        \XF\Pub\App $app,
        array & $params,
        \XF\Mvc\Reply\AbstractReply $reply,
        \XF\Mvc\Renderer\AbstractRenderer $renderer
    ): void {
        if (!isset($params[App::KEY_PAGE_PARAMS_GROUP])) {
            return;
        }

        /** @var Group $group */
        $group = $params[App::KEY_PAGE_PARAMS_GROUP];

        if (isset($params['forum'])) {
            $breadcrumbs = $group->getBreadcrumbs();

            /** @var Forum $forum */
            $forum = $params['forum'];
            $breadcrumbs = array_merge($breadcrumbs, $forum->getBreadcrumbs(true, 'public'));

            $params['breadcrumbs'] = $breadcrumbs;
        }

        // merge all sidebar into sidenav block.
        $params['sideNav'] += $params['sidebar'];
        $params['sidebar'] = [];
    }
}
