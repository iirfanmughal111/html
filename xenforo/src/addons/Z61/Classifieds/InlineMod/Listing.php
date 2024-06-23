<?php

namespace Z61\Classifieds\InlineMod;

use XF\InlineMod\AbstractHandler;
use XF\Mvc\Entity\Entity;
use Z61\Classifieds\Service\Listing\Feature;

class Listing extends AbstractHandler
{
    public function getPossibleActions()
    {
        $actions = [];

        $actions['delete'] = $this->getActionHandler('Z61\Classifieds:Listing\Delete');

        $actions['undelete'] = $this->getSimpleActionHandler(
            \XF::phrase('z61_classifieds_undelete_listings'),
            'canUndelete',
            function(Entity $entity)
            {
                /** @var \Z61\Classifieds\Entity\Listing $entity */
                if ($entity->listing_state == 'deleted')
                {
                    $entity->listing_state = 'visible';
                    $entity->save();
                }
            }
        );

        $actions['approve'] = $this->getSimpleActionHandler(
            \XF::phrase('z61_classifieds_approve_listings'),
            'canApproveUnapprove',
            function(Entity $entity)
            {
                /** @var \Z61\Classifieds\Entity\Listing $entity */
                if ($entity->listing_state == 'moderated')
                {
                    /** @var \Z61\Classifieds\Service\Listing\Approve $approver */
                    $approver = \XF::service('Z61\Classifieds:Listing\Approve', $entity);
                    $approver->setNotifyRunTime(1); // may be a lot happening
                    $approver->approve();
                }
            }
        );

        $actions['unapprove'] = $this->getSimpleActionHandler(
            \XF::phrase('z61_classifieds_unapprove_listings'),
            'canApproveUnapprove',
            function(Entity $entity)
            {
                /** @var \Z61\Classifieds\Entity\Listing $entity */
                if ($entity->listing_state == 'visible')
                {
                    $entity->listing_state = 'moderated';
                    $entity->save();
                }
            }
        );

        $actions['feature'] = $this->getSimpleActionHandler(
            \XF::phrase('z61_classifieds_feature_listings'),
            'canFeatureUnfeature',
            function(Entity $entity)
            {
                /** @var Feature $featurer */
                $featurer = $this->app->service('Z61\Classifieds:Listing\Feature', $entity);
                $featurer->feature();
            }
        );

        $actions['unfeature'] = $this->getSimpleActionHandler(
            \XF::phrase('z61_classifieds_unfeature_listings'),
            'canFeatureUnfeature',
            function(Entity $entity)
            {
                /** @var Feature $featurer */
                $featurer = $this->app->service('Z61\Classifieds:Listing\Feature', $entity);
                $featurer->unfeature();
            }
        );

        $actions['close'] = $this->getSimpleActionHandler(
            \XF::phrase('z61_classifieds_close_listings'),
            'canClose',

            function(Entity $entity)
            {
                $entity->listing_open = false;
                $entity->save();
            }
        );

        $actions['open'] = $this->getSimpleActionHandler(
            \XF::phrase('z61_classifieds_open_listings'),
            'canOpen',

            function(Entity $entity)
            {
                $entity->listing_open = true;
                $entity->save();
            }
        );

        $actions['reassign'] = $this->getActionHandler('Z61\Classifieds:Listing\Reassign');
        $actions['move'] = $this->getActionHandler('Z61\Classifieds:Listing\Move');
        $actions['apply_prefix'] = $this->getActionHandler('Z61\Classifieds:Listing\ApplyPrefix');

        return $actions;
    }

    public function getEntityWith()
    {
        $visitor = \XF::visitor();

        return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
    }
}