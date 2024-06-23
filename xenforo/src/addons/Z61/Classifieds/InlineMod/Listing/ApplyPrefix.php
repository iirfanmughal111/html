<?php

namespace Z61\Classifieds\InlineMod\Listing;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class ApplyPrefix extends AbstractAction
{
    public function getTitle()
    {
        return \XF::phrase('apply_prefix...');
    }

    protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
    {
        /** @var \Z61\Classifieds\Entity\Listing $entity */
        return $entity->canEdit($error);
    }

    protected function applyToEntity(Entity $entity, array $options)
    {
        /** @var \Z61\Classifieds\Entity\Listing $entity */
        if (!$entity->Category->isPrefixValid($options['prefix_id']))
        {
            return;
        }

        /** @var \Z61\Classifieds\Service\Listing\Edit $editor */
        $editor = $this->app()->service('Z61\Classifieds:Listing\Edit', $entity);
        $editor->setPerformValidations(false);
        $editor->setPrefix($options['prefix_id']);
        if ($editor->validate($errors))
        {
            $editor->save();
        }
    }

    public function getBaseOptions()
    {
        return [
            'prefix_id' => null
        ];
    }

    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        $categories = $entities->pluckNamed('Category', 'category_id');
        $prefixIds = [];

        foreach ($categories AS $category)
        {
            $prefixIds = array_merge($prefixIds, array_keys($category->prefix_cache));
        }

        $prefixes = $this->app()->finder('Z61\Classifieds:ListingPrefix')
            ->where('prefix_id', array_unique($prefixIds))
            ->order('materialized_order')
            ->fetch();

        if (!$prefixes->count())
        {
            return $controller->error(\XF::phrase('z61_classifieds_no_prefixes_available_for_selected_categories'));
        }

        $selectedPrefix = 0;
        $prefixCounts = [0 => 0];
        foreach ($entities AS $listing)
        {
            $prefixId = $listing->prefix_id;

            if (!isset($prefixCounts[$prefixId]))
            {
                $prefixCounts[$prefixId] = 1;
            }
            else
            {
                $prefixCounts[$prefixId]++;
            }

            if ($prefixCounts[$prefixId] > $prefixCounts[$selectedPrefix])
            {
                $selectedPrefix = $prefixId;
            }
        }

        $viewParams = [
            'listings' => $entities,
            'prefixes' => $prefixes->groupBy('prefix_group_id'),
            'categoryCount' => count($categories->keys()),
            'selectedPrefix' => $selectedPrefix,
            'total' => count($entities)
        ];
        return $controller->view('Z61\Classifieds:Public:InlineMod\Listing\ApplyPrefix', 'inline_mod_classifieds_listing_apply_prefix', $viewParams);
    }

    public function getFormOptions(AbstractCollection $entities, Request $request)
    {
        return [
            'prefix_id' => $request->filter('prefix_id', 'uint')
        ];
    }
}