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
use XF\Mvc\Reply\Exception;
use Truonglv\Groups\Listener;
use Truonglv\Groups\Entity\Group;

class Album extends XFCP_Album
{
    public function actionView(ParameterBag $params)
    {
        if (!App::isEnabledXenMediaAddOn()) {
            return parent::actionView($params);
        }

        try {
            $response = parent::actionView($params);
        } catch (Throwable $e) {
            if ($e instanceof Exception && $e->getReply()->getResponseCode() === 403) {
                /** @var \Truonglv\Groups\XFMG\Entity\Album $album */
                $album = $this->em()->find('XFMG:Album', $params->album_id);
                $groupId = App::getGroupIdFromEntity($album);

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

            throw $e;
        }

        if ($response instanceof View) {
            /** @var \Truonglv\Groups\XFMG\Entity\Album|null $album */
            $album = $response->getParam('album');
            if ($album === null) {
                return $response;
            }

            $groupId = App::getGroupIdFromEntity($album);
            if ($groupId <= 0) {
                return $response;
            }

            /** @var Group|null $group */
            $group = $this->finder('Truonglv\Groups:Group')
                ->with('full')
                ->whereId($groupId)
                ->fetchOne();
            if ($group === null) {
                return $response;
            }
            $album->setTLGGroup($group);

            Listener::addContentLanguageResponseHeader($group);
            $this->setSectionContext('tl_groups');

            App::groupRepo()->logView($group);

            $response->setParam('group', $group);
            $response->setTemplateName('tlg_media_album_view');
        }

        return $response;
    }

    public function actionFilters(ParameterBag $params)
    {
        $response = parent::actionFilters($params);
        if (!App::isEnabledXenMediaAddOn()) {
            return $response;
        }

        $appendGroupParam = function ($url, $groupId) {
            if (strpos($url, '?') === false) {
                $url .= '?group_id=' . urlencode($groupId);
            } else {
                $url .= '&group_id=' . urlencode($groupId);
            }

            return $url;
        };

        if ($params['album_id'] <= 0) {
            $groupId = $this->filter('group_id', 'uint');
            if ($groupId <= 0) {
                return $response;
            }

            if ($response instanceof View) {
                $formAction = $appendGroupParam($response->getParam('action'), $groupId);
                $response->setParam('action', $formAction);
            } elseif ($this->filter('apply', 'bool') === true) {
                /** @var \XFMG\ControllerPlugin\AlbumList $albumListPlugin */
                $albumListPlugin = $this->plugin('XFMG:AlbumList');

                $response = $this->redirect($this->buildLink(
                    'groups/media',
                    ['group_id' => $groupId],
                    $albumListPlugin->getFilterInput()
                ));
            }
        }

        return $response;
    }

    public function actionCreate()
    {
        $groupId = $this->filter('group_id', 'uint');
        if ($groupId <= 0 || !App::isEnabledXenMediaAddOn()) {
            return parent::actionCreate();
        }

        /** @var Group $group */
        $group = $this->assertViewableRecord('Truonglv\Groups:Group', $groupId, 'full');
        if (!$group->canAddAlbums()) {
            return parent::actionCreate();
        }

        App::$createAlbumInGroup = $group;

        $response = parent::actionCreate();
        if ($response instanceof View) {
            $response->setParam('group', $group);

            $attachmentData = $response->getParam('attachmentData');
            $attachmentData['context']['tlg_group_id'] = $group->group_id;
            $response->setParam('attachmentData', $attachmentData);

            $response->setTemplateName('tlg_media_album_create');
        }

        App::$createAlbumInGroup = null;

        return $response;
    }

    public function actionFind()
    {
        $q = $this->filter('q', 'str', ['no-trim']);

        if ($q !== '' && utf8_strlen($q) >= 2) {
            $albumFinder = $this->finder('XFMG:Album');

            $albums = $albumFinder
                ->where('title', 'like', $albumFinder->escapeLike($q, '?%'))
                ->where('album_state', 'visible')
                ->where('view_privacy', 'public')
                ->fetch(10);
        } else {
            $albums = [];
            $q = '';
        }

        $viewParams = [
            'q' => $q,
            'albums' => $albums
        ];

        $view = $this->view('Truonglv\Groups:Album\Find', '', $viewParams);
        $view->setJsonParam('q', $q);

        $results = [];
        /** @var \Truonglv\Groups\XFMG\Entity\Album $album */
        foreach ($albums as $album) {
            $results[] = [
                'id' => $album->album_id,
                'text' => $album->album_id . ',' . $album->title,
                'q' => $q,
            ];
        }

        $view->setJsonParam('results', $results);

        return $view;
    }

    protected function assertViewableAlbum($albumId, array $extraWith = [])
    {
        if (App::isEnabledXenMediaAddOn()) {
            $extraWith[] = 'GroupAlbum';
        }

        return parent::assertViewableAlbum($albumId, $extraWith);
    }
}
