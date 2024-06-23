<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Admin\Controller;

use XF\Mvc\FormAction;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractField;

class Field extends AbstractField
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission(App::PERMISSION_ADMIN_MANAGE_GROUPS);
    }

    /**
     * @return string
     */
    protected function getTemplatePrefix()
    {
        return 'tlg_field';
    }

    /**
     * @return string
     */
    protected function getLinkPrefix()
    {
        return 'group-fields';
    }

    /**
     * @return string
     */
    protected function getClassIdentifier()
    {
        return 'Truonglv\Groups:Field';
    }

    /**
     * @param \XF\Entity\AbstractField $field
     * @return \XF\Mvc\Reply\View
     */
    protected function fieldAddEditResponse(\XF\Entity\AbstractField $field)
    {
        $reply = parent::fieldAddEditResponse($field);

        if ($reply instanceof \XF\Mvc\Reply\View) {
            /** @var \Truonglv\Groups\Repository\Category $categoryRepo */
            $categoryRepo = $this->repository('Truonglv\Groups:Category');

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

    /**
     * @param FormAction $form
     * @param \XF\Entity\AbstractField $field
     * @return FormAction
     */
    protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractField $field)
    {
        $categoryIds = $this->filter('category_ids', 'array-uint');

        /** @var \Truonglv\Groups\Entity\Field $field */
        $form->complete(function () use ($field, $categoryIds) {
            /** @var \Truonglv\Groups\Repository\CategoryField $repo */
            $repo = $this->repository('Truonglv\Groups:CategoryField');
            $repo->updateFieldAssociations($field, $categoryIds);
        });

        return $form;
    }
}
