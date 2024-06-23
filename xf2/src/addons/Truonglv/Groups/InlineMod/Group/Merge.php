<?php

namespace Truonglv\Groups\InlineMod\Group;

use XF;
use LogicException;
use XF\Http\Request;
use XF\Mvc\Entity\Entity;
use InvalidArgumentException;
use XF\InlineMod\AbstractAction;
use Truonglv\Groups\Entity\Group;
use XF\Mvc\Entity\AbstractCollection;
use Truonglv\Groups\Service\Group\Merger;

class Merge extends AbstractAction
{
    /**
     * @return \XF\Phrase
     */
    public function getTitle()
    {
        return XF::phrase('tlg_merge_groups');
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    protected function canApplyToEntity(Entity $entity, array $options, & $error = null)
    {
        if ($entity instanceof Group) {
            return $entity->canMerge($error);
        }

        return false;
    }

    /**
     * @param AbstractCollection $entities
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    protected function canApplyInternal(AbstractCollection $entities, array $options, & $error)
    {
        $result = parent::canApplyInternal($entities, $options, $error);
        if ($result) {
            if ($options['target_group_id'] > 0) {
                if (!isset($entities[$options['target_group_id']])) {
                    return false;
                }
            }

            if ($entities->count() < 2) {
                return false;
            }
        }

        return $result;
    }

    /**
     * @param AbstractCollection $entities
     * @param array $options
     * @throws \XF\PrintableException
     * @return void
     */
    protected function applyInternal(AbstractCollection $entities, array $options)
    {
        if ($options['target_group_id'] <= 0) {
            throw new InvalidArgumentException('No target group selected');
        }

        $source = $entities->toArray();
        $target = $entities[$options['target_group_id']];
        unset($source[$options['target_group_id']]);

        /** @var Merger $merger */
        $merger = $this->app()->service('Truonglv\Groups:Group\Merger', $target);
        $merger->setAlertType($options['alert_type']);
        $merger->merge($source);

        $this->returnUrl = $this->app()->router()->buildLink('groups', $target);
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @return void
     */
    protected function applyToEntity(Entity $entity, array $options)
    {
        throw new LogicException('applyToEntity should not be called on thread merging');
    }

    /**
     * @return array
     */
    public function getBaseOptions()
    {
        return [
            'alert_type' => 'admin',
            'target_group_id' => 0
        ];
    }

    /**
     * @param AbstractCollection $entities
     * @param \XF\Mvc\Controller $controller
     * @return \XF\Mvc\Reply\View|null
     */
    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        return $controller->view(
            '',
            'tlg_inline_mod_group_merge',
            [
                'entities' => $entities,
                'first' => $entities->first(),
                'total' => $entities->count()
            ]
        );
    }

    /**
     * @param AbstractCollection $entities
     * @param Request $request
     * @return array
     */
    public function getFormOptions(AbstractCollection $entities, Request $request)
    {
        $options = $request->filter([
            'target_group_id' => 'uint',
            'alert_type' => 'str'
        ]);

        return $options;
    }
}
