<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\InlineMod\Group;

use XF;
use function count;
use XF\Http\Request;
use XF\Mvc\Entity\Entity;
use XF\InlineMod\AbstractAction;
use Truonglv\Groups\Entity\Group;
use XF\Mvc\Entity\AbstractCollection;

class Delete extends AbstractAction
{
    /**
     * @return \XF\Phrase
     */
    public function getTitle()
    {
        return XF::phrase('tlg_delete_groups');
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @throws \XF\PrintableException
     * @return void
     */
    protected function applyToEntity(Entity $entity, array $options)
    {
        /** @var \Truonglv\Groups\Service\Deleter $deleter */
        $deleter = XF::service('Truonglv\Groups:Deleter', $entity);
        $deleter->setStateField('group_state')
                ->delete($options['type'], $options['reason']);
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    protected function canApplyToEntity(Entity $entity, array $options, & $error = null)
    {
        if (!($entity instanceof Group)) {
            return false;
        }

        return $entity->canDelete($options['type'], $error);
    }

    /**
     * @return array
     */
    public function getBaseOptions()
    {
        return [
            'type' => 'soft',
            'reason' => ''
        ];
    }

    /**
     * @param AbstractCollection $entities
     * @param Request $request
     * @return array
     */
    public function getFormOptions(AbstractCollection $entities, Request $request)
    {
        $options = [
            'type' => $request->filter('type', 'str'),
            'reason' => $request->filter('reason', 'str')
        ];

        if ($options['type'] === '') {
            $options['type'] = 'soft';
        }

        return $options;
    }

    /**
     * @param AbstractCollection $entities
     * @param \XF\Mvc\Controller $controller
     * @return \XF\Mvc\Reply\View|null
     */
    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        $viewParams = [
            'groups' => $entities,
            'total' => count($entities),
            'canHardDelete' => $this->canApply($entities, ['type' => 'hard'])
        ];

        return $controller->view(
            '',
            'tlg_inline_mod_group_delete',
            $viewParams
        );
    }
}
