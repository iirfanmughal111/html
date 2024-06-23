<?php

namespace Z61\Classifieds\InlineMod\Listing;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class Delete extends AbstractAction
{
    public function getTitle()
    {
        return \XF::phrase('z61_classifieds_delete_listings...');
    }

    protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
    {
        /** @var \Z61\Classifieds\Entity\Listing $entity */
        return $entity->canDelete($options['type'], $error);
    }

    protected function applyToEntity(Entity $entity, array $options)
    {
        /** @var \Z61\Classifieds\Service\Listing\Delete $deleter */
        $deleter = $this->app()->service('Z61\Classifieds:Listing\Delete', $entity);

        if ($options['alert'])
        {
            $deleter->setSendAlert(true, $options['alert_reason']);
        }

        $deleter->delete($options['type'], $options['reason']);

        if ($options['type'] == 'hard')
        {
            $this->returnUrl = $this->app()->router()->buildLink('classifieds/categories', $entity->Category);
        }
    }

    public function getBaseOptions()
    {
        return [
            'type' => 'soft',
            'reason' => '',
            'alert' => false,
            'alert_reason' => ''
        ];
    }

    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        $viewParams = [
            'listings' => $entities,
            'total' => count($entities),
            'canHardDelete' => $this->canApply($entities, ['type' => 'hard'])
        ];
        return $controller->view('Z61\Classifieds:Public:InlineMod\Listing\Delete', 'inline_mod_classifieds_listing_delete', $viewParams);
    }

    public function getFormOptions(AbstractCollection $entities, Request $request)
    {
        return [
            'type' => $request->filter('hard_delete', 'bool') ? 'hard' : 'soft',
            'reason' => $request->filter('reason', 'str'),
            'alert' => $request->filter('author_alert', 'bool'),
            'alert_reason' => $request->filter('author_alert_reason', 'str')
        ];
    }
}