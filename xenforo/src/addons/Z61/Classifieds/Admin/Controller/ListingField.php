<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractField;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class ListingField extends AbstractField
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('classifieds');
    }

    protected function getClassIdentifier()
    {
        return 'Z61\Classifieds:ListingField';
    }

    protected function getLinkPrefix()
    {
        return 'classifieds/fields';
    }

    protected function getTemplatePrefix()
    {
        return 'z61_classifieds_listing_field';
    }

    protected function fieldAddEditResponse(\XF\Entity\AbstractField $field)
    {
        $reply = parent::fieldAddEditResponse($field);

        if ($reply instanceof \XF\Mvc\Reply\View)
        {
            /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
            $categoryRepo = $this->repository('Z61\Classifieds:Category');

            $categories = $categoryRepo->findCategoryList()->fetch();
            $categoryTree = $categoryRepo->createCategoryTree($categories);

            /** @var \XF\Mvc\Entity\ArrayCollection $fieldAssociations */
            $fieldAssociations = $field->getRelationOrDefault('CategoryFields', false);

            $reply->setParams([
                'categoryTree' => $categoryTree,
                'categoryIds' => $fieldAssociations->pluckNamed('category_id')
            ]);
        }

        return $reply;
    }

    protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractField $field)
    {
        $categoryIds = $this->filter('category_ids', 'array-uint');

        /** @var \Z61\Classifieds\Entity\ListingField $field */
        $form->complete(function() use ($field, $categoryIds)
        {
            /** @var \Z61\Classifieds\Repository\CategoryField $repo */
            $repo = $this->repository('Z61\Classifieds:CategoryField');
            $repo->updateFieldAssociations($field, $categoryIds);
        });

        return $form;
    }
}