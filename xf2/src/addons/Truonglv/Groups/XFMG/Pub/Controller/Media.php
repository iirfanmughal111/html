<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XFMG\Pub\Controller;

use XF;
use Throwable;
use XF\Mvc\Reply\View;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XFMG\Entity\MediaItem;
use XF\Mvc\Reply\Exception;
use Truonglv\Groups\Listener;
use Truonglv\Groups\Entity\Group;

class Media extends XFCP_Media
{
    public function actionView(ParameterBag $params)
    {
        if (!App::isEnabledXenMediaAddOn()) {
            return parent::actionView($params);
        }

        try {
            $response = parent::actionView($params);
        } catch (Throwable $e) {
            if ($e instanceof Exception
                && $e->getReply()->getResponseCode() === 403
            ) {
                /** @var MediaItem|null $mediaItem */
                $mediaItem = $this->em()->find('XFMG:MediaItem', $params->media_id);
                if ($mediaItem !== null
                    && $mediaItem->Album !== null
                ) {
                    $groupId = App::getGroupIdFromEntity($mediaItem->Album);
                    if ($groupId > 0) {
                        /** @var Group $group */
                        $group = $this->em()->find('Truonglv\Groups:Group', $groupId);
                        // the forum belong to group
                        // throw our friendly error
                        throw $this->exception($this->error(XF::phrase('tlg_you_need_become_a_member_of_the_group_x_to_view_the_content', [
                            'title' => $group->name,
                            'url' => $this->app()->router('public')->buildLink('groups', $group)
                        ])));
                    }
                }
            }

            throw $e;
        }

        if ($response instanceof View) {
            /** @var \XFMG\Entity\MediaItem|null $mediaItem */
            $mediaItem = $response->getParam('mediaItem');
            if ($mediaItem === null) {
                return $response;
            }

            /** @var \Truonglv\Groups\XFMG\Entity\Album|null $album */
            $album = $mediaItem->Album;
            if ($album === null) {
                return $response;
            }
            $groupId = App::getGroupIdFromEntity($album);

            if ($groupId <= 0) {
                return $response;
            }

            /** @var Group|null $group */
            $group = $this->finder('Truonglv\Groups:Group')
                ->whereId($groupId)
                ->with('full')
                ->fetchOne();

            if ($group === null) {
                return $response;
            }
            if (!$group->canViewContent($error)) {
                throw $this->exception($this->noPermission($error));
            }

            if ($this->filter('_xfWithData', 'bool') === false) {
                Listener::addContentLanguageResponseHeader($group);
                $this->setSectionContext('tl_groups');
                App::groupRepo()->logView($group);

                $response->setParam('group', $group);
                $response->setTemplateName('tlg_media_view');
            }
        }

        return $response;
    }

    protected function setupAlbumCreate()
    {
        $creator = parent::setupAlbumCreate();

        $groupId = $this->filter('group_id', 'uint');
        if ($groupId > 0 && App::isEnabledXenMediaAddOn()) {
            /** @var Group|null $group */
            $group = App::assertionPlugin($this)->assertGroupViewable($groupId, [], true);
            if ($group !== null) {
                /** @var \Truonglv\Groups\XFMG\Entity\Album $album */
                $album = $creator->getAlbum();
                $album->setTLGGroup($group);

                $entity = $album->getNewGroupAlbum();
                $entity->group_id = $group->group_id;
            }
        }

        return $creator;
    }
}
