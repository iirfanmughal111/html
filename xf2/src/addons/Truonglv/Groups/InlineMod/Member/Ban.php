<?php

namespace Truonglv\Groups\InlineMod\Member;

use XF;
use XF\Http\Request;
use XF\Mvc\Entity\Entity;
use XF\InlineMod\AbstractAction;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Member;
use XF\Mvc\Entity\AbstractCollection;
use Truonglv\Groups\Service\Member\Banning;

class Ban extends AbstractAction
{
    /**
     * @var Group|null
     */
    private $group;

    /**
     * @param Group $group
     * @return void
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @return \XF\Phrase
     */
    public function getTitle()
    {
        return XF::phrase('tlg_ban_members');
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    protected function canApplyToEntity(Entity $entity, array $options, & $error = null)
    {
        if (!$entity instanceof Member) {
            return false;
        }

        return $entity->canBeBanned($error);
    }

    /**
     * @return array
     */
    public function getBaseOptions()
    {
        return [
            'end_date' => 0,
            'type' => 0
        ];
    }

    /**
     * @param AbstractCollection $entities
     * @param Request $request
     * @return array
     */
    public function getFormOptions(AbstractCollection $entities, Request $request)
    {
        $options = $request->filter([
            'end_date' => 'datetime,end',
            'type' => 'uint'
        ]);

        if ($options['type'] <= 0) {
            return ['end_date' => 0];
        }

        return ['end_date' => $options['end_date']];
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @throws \XF\PrintableException
     * @return void
     */
    protected function applyToEntity(Entity $entity, array $options)
    {
        /** @var Banning $banning */
        $banning = $this->app()->service('Truonglv\Groups:Member\Banning', $entity);
        $banning->ban($options['end_date']);
    }

    /**
     * @param AbstractCollection $entities
     * @param \XF\Mvc\Controller $controller
     * @return \XF\Mvc\Reply\View|null
     */
    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        return $controller->view('', 'tlg_inline_mod_member_ban', [
            'members' => $entities,
            'total' => $entities->count(),
            'group' => $this->group
        ]);
    }
}
