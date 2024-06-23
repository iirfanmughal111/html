<?php

namespace Z61\Classifieds\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Listing extends AbstractHandler
{
    protected function canActionContent(Entity $content, &$error = null)
    {
        /** @var $content \Z61\Classifieds\Entity\Listing*/
        return $content->canApproveUnapprove($error);
    }

    public function getEntityWith()
    {
        $visitor = \XF::visitor();
        return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id, 'User'];
    }

    public function actionApprove(\Z61\Classifieds\Entity\Listing $listing)
    {
        /** @var \Z61\Classifieds\Service\Listing\Approve $approver */
        $approver = \XF::service('Z61\Classifieds:Listing\Approve', $listing);
        $approver->setNotifyRunTime(1);
        $approver->approve();
    }

    public function actionDelete(\Z61\Classifieds\Entity\Listing $listing)
    {
        $this->quickUpdate($listing, 'listing_state', 'deleted');
    }
}