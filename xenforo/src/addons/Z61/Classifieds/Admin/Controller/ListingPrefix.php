<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractPrefix;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;

class ListingPrefix extends AbstractPrefix
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('classifieds');
    }

    protected function getClassIdentifier()
    {
        return 'Z61\Classifieds:ListingPrefix';
    }

    protected function getLinkPrefix()
    {
        return 'classifieds/prefixes';
    }

    protected function getTemplatePrefix()
    {
        return 'z61_classifieds_listing_prefix';
    }

    protected function getCategoryParams(\Z61\Classifieds\Entity\ListingPrefix $prefix)
    {
        /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
        $categoryRepo = \XF::repository('Z61\Classifieds:Category');
        $categoryTree = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());

        return [
            'categoryTree' => $categoryTree,
        ];
    }

    protected function prefixAddEditResponse(\XF\Entity\AbstractPrefix $prefix)
    {
        $reply = parent::prefixAddEditResponse($prefix);

        if ($reply instanceof \XF\Mvc\Reply\View)
        {
            $reply->setParams($this->getCategoryParams($prefix));
        }

        return $reply;
    }

    protected function quickSetAdditionalData(FormAction $form, ArrayCollection $prefixes)
    {
        $input = $this->filter([
            'apply_category_ids' => 'bool',
            'category_ids' => 'array-uint'
        ]);

        if ($input['apply_category_ids'])
        {
            $form->complete(function() use($prefixes, $input)
            {
                $mapRepo = $this->getCategoryPrefixRepo();

                foreach ($prefixes AS $prefix)
                {
                    $mapRepo->updatePrefixAssociations($prefix, $input['category_ids']);
                }
            });
        }

        return $form;
    }

    public function actionQuickSet()
    {
        $reply = parent::actionQuickSet();

        if ($reply instanceof \XF\Mvc\Reply\View)
        {
            if ($reply->getTemplateName() == $this->getTemplatePrefix() . '_quickset_editor')
            {
                $reply->setParams($this->getCategoryParams($reply->getParam('prefix')));
            }
        }

        return $reply;
    }

    protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractPrefix $prefix)
    {
        $categoryIds = $this->filter('category_ids', 'array-uint');

        $form->complete(function() use($prefix, $categoryIds)
        {
            $this->getCategoryPrefixRepo()->updatePrefixAssociations($prefix, $categoryIds);
        });

        return $form;
    }

    /**
     * @return \Z61\Classifieds\Repository\CategoryPrefix
     */
    protected function getCategoryPrefixRepo()
    {
        return $this->repository('Z61\Classifieds:CategoryPrefix');
    }
}