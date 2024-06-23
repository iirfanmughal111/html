<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ApprovalQueue;

use XF;
use XF\Mvc\Entity\Entity;
use XF\ApprovalQueue\AbstractHandler;

class Group extends AbstractHandler
{
    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['User', 'Category'];
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_approval_item_group';
    }

    /**
     * @param Entity $content
     * @param mixed $error
     * @return bool
     */
    protected function canActionContent(Entity $content, & $error = null)
    {
        if ($content instanceof \Truonglv\Groups\Entity\Group) {
            return $content->canApproveUnapprove($error);
        }

        return false;
    }

    /**
     * @param Entity $entity
     * @throws \XF\PrintableException
     * @return void
     */
    public function actionApprove(Entity $entity)
    {
        /** @var \Truonglv\Groups\Service\Group\Approver $approver */
        $approver = XF::service('Truonglv\Groups:Group\Approver', $entity);
        $approver->approve();
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function actionDelete(Entity $entity)
    {
        $this->quickUpdate($entity, 'group_state', 'deleted');
    }
}
