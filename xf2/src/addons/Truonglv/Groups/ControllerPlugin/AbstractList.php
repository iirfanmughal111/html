<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ControllerPlugin;

use LogicException;
use function get_class;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use InvalidArgumentException;
use Truonglv\Groups\Entity\Event;
use Truonglv\Groups\Entity\Group;
use XF\ControllerPlugin\AbstractPlugin;

abstract class AbstractList extends AbstractPlugin
{
    /**
     * @param string $viewName
     * @param string $contentType
     * @param Entity $entity
     * @param string $formAction
     * @param string $redirectUrl
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionTags($viewName, $contentType, Entity $entity, $formAction, $redirectUrl)
    {
        if ($entity instanceof Group) {
            $group = $entity;
        } elseif ($entity instanceof Event) {
            $group = $entity->Group;
        } else {
            throw new InvalidArgumentException('Unknown entity (' . get_class($entity) . ')');
        }

        $error = null;
        if (!$entity->canEditTags($error)) {
            throw $this->exception($this->noPermission($error));
        }

        /** @var \XF\Service\Tag\Changer $tagger */
        $tagger = $this->app->service('XF:Tag\Changer', $contentType, $entity);

        if ($this->isPost()) {
            $tagger->setEditableTags($this->filter('tags', 'str'));
            if ($tagger->hasErrors()) {
                throw $this->exception($this->error($tagger->getErrors()));
            }

            $tagger->save();

            return $this->redirect($redirectUrl);
        }

        $grouped = $tagger->getExistingTagsByEditability();

        return $this->view($viewName, 'tlg_helper_edit_tags', [
            'group' => $group,
            'formAction' => $formAction,
            'editableTags' => $grouped['editable'],
            'uneditableTags' => $grouped['uneditable']
        ]);
    }

    /**
     * @param Entity|null $entity
     * @return mixed
     */
    public function actionFilters(Entity $entity = null)
    {
        $filters = $this->getFilterInput();
        if ($this->filter('apply', 'bool') === true) {
            return $this->apply($filters, $entity);
        }

        return $this->getFilterForm($filters, $entity);
    }

    /**
     * @param array $filters
     * @param Entity|null $entity
     * @return mixed
     */
    protected function getFilterForm(array $filters, Entity $entity = null)
    {
        throw new LogicException('Must be implement by child.');
    }

    /**
     * @param Finder $finder
     * @param array $filters
     * @return void
     */
    protected function applyFilters(Finder $finder, array $filters)
    {
        $sorts = $this->getAvailableSorts();
        if (isset($filters['order']) && isset($sorts[$filters['order']])) {
            $finder->resetOrder();
            $finder->order($sorts[$filters['order']], $filters['direction']);
        }
    }

    /**
     * @param array $filters
     * @param Entity|null $entity
     * @return mixed
     */
    protected function apply(array $filters, Entity $entity = null)
    {
        throw new LogicException('Must be implement by child.');
    }

    /**
     * @return array
     */
    protected function getFilterInput()
    {
        throw new LogicException('Must be implement by child.');
    }

    /**
     * @return array
     */
    protected function getAvailableSorts()
    {
        throw new LogicException('Must be implement by child.');
    }
}
