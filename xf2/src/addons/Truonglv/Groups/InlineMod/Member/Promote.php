<?php

namespace Truonglv\Groups\InlineMod\Member;

use XF;
use XF\Http\Request;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\InlineMod\AbstractAction;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Member;
use XF\Mvc\Entity\AbstractCollection;
use Truonglv\Groups\Service\Member\Promoter;

class Promote extends AbstractAction
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
        return XF::phrase('tlg_promote_members');
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

        return $entity->canBePromote($error);
    }

    /**
     * @return array
     */
    public function getBaseOptions()
    {
        return [
            'member_role_id' => ''
        ];
    }

    /**
     * @param AbstractCollection $entities
     * @param Request $request
     * @return array
     */
    public function getFormOptions(AbstractCollection $entities, Request $request)
    {
        return $request->filter([
            'member_role_id' => 'str'
        ]);
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @return void
     */
    protected function applyToEntity(Entity $entity, array $options)
    {
        /** @var Promoter $promoter */
        $promoter = $this->app()->service('Truonglv\Groups:Member\Promoter', $entity);
        $promoter->setMemberRoleId($options['member_role_id']);
        $promoter->promote();
    }

    /**
     * @param AbstractCollection $entities
     * @param \XF\Mvc\Controller $controller
     * @return \XF\Mvc\Reply\View|null
     */
    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        return $controller->view('', 'tlg_inline_mod_member_promote', [
            'members' => $entities,
            'total' => $entities->count(),
            'memberRoles' => App::memberRoleRepo()->getAllMemberRoles(),
            'group' => $this->group
        ]);
    }
}
