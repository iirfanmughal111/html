<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\InlineMod;

use XF;
use XF\Mvc\Entity\Entity;
use InvalidArgumentException;
use XF\InlineMod\AbstractHandler;

class Group extends AbstractHandler
{
    /**
     * @return \XF\InlineMod\AbstractAction[]
     */
    public function getPossibleActions()
    {
        $actions = [];

        $actions['delete'] = $this->getActionHandler('Truonglv\Groups:Group\Delete');

        $actions['undelete'] = $this->getSimpleActionHandler(
            XF::phrase('tlg_undelete_groups'),
            'canUndelete',
            function (Entity $entity) {
                if (!($entity instanceof \Truonglv\Groups\Entity\Group)) {
                    throw new InvalidArgumentException('Invalid entity provided.');
                }

                if ($entity->group_state == 'deleted') {
                    $entity->group_state = 'visible';
                    $entity->save();
                }
            }
        );

        $actions['approve'] = $this->getSimpleActionHandler(
            XF::phrase('tlg_approve_groups'),
            'canApproveUnapprove',
            function (Entity $entity) {
                /** @var \Truonglv\Groups\Service\Group\Approver $approver */
                $approver = XF::service('Truonglv\Groups:Group\Approver', $entity);
                $approver->approve();
            }
        );

        $actions['unapprove'] = $this->getSimpleActionHandler(
            XF::phrase('tlg_unapprove_groups'),
            'canApproveUnapprove',
            function (Entity $entity) {
                /** @var \Truonglv\Groups\Service\Group\Approver $approver */
                $approver = XF::service('Truonglv\Groups:Group\Approver', $entity);
                $approver->unapprove();
            }
        );

        $actions['move'] = $this->getActionHandler('Truonglv\Groups:Group\Move');

        $actions['merge'] = $this->getActionHandler('Truonglv\Groups:Group\Merge');

        return $actions;
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['Category'];
    }
}
