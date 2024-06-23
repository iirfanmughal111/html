<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;
use XF\Option\Forum;

class Category extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('classifieds');
    }

    /**
     * @return \Z61\Classifieds\ControllerPlugin\CategoryTree
     */
    protected function getCategoryTreePlugin()
    {
        return $this->plugin('Z61\Classifieds:CategoryTree');
    }

    public function actionIndex()
    {
        return $this->getCategoryTreePlugin()->actionList([
            'permissionContentType' => 'z61_classifieds_category'
        ]);
    }

    protected function categoryAddEdit(\Z61\Classifieds\Entity\Category $category)
    {
        $categoryRepo = $this->getCategoryRepo();

        $categoryTree = $categoryRepo->createCategoryTree();

        if ($category->thread_prefix_id && $category->Forum)
        {
            $threadPrefixes = $category->Forum->getPrefixesGrouped();
        }
        else
        {
            $threadPrefixes = [];
        }

        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = \XF::repository('XF:Node');

        $choices = $nodeRepo->getNodeOptionsData(true, 'Forum');
        $choices = array_map(function($v) {
            $v['label'] = \XF::escapeString($v['label']);
            return $v;
        }, $choices);

        /** @var \Z61\Classifieds\Repository\ListingPrefix $prefixRepo */
        $prefixRepo = $this->repository('Z61\Classifieds:ListingPrefix');
        $availablePrefixes = $prefixRepo->findPrefixesForList()->fetch();
        $availablePrefixes = $availablePrefixes->pluckNamed('title', 'prefix_id');

        /** @var \Z61\Classifieds\Repository\ListingField $fieldRepo */
        $fieldRepo = $this->repository('Z61\Classifieds:ListingField');
        $availableFields = $fieldRepo->findFieldsForList()->fetch();
        $availableFields = $availableFields->pluckNamed('title', 'field_id');

        $paymentRepo = $this->repository('XF:Payment');
        $paymentProfiles = $paymentRepo->findPaymentProfilesForList()->fetch();

        $listingTypes = $this->finder('Z61\Classifieds:ListingType')
            ->order('display_order', 'asc')
            ->fetch();

        $conditions = $this->finder('Z61\Classifieds:Condition')
            ->where('active', 1)
            ->order('display_order', 'asc')
            ->fetch();

        $packages = $this->finder('Z61\Classifieds:Package')
            ->where('active', 1)
            ->order('display_order', 'asc')
            ->fetch();

        $viewParams = [
            'category' => $category,
            'categoryTree' => $categoryTree,
            'nodes' => $choices,
            'threadPrefixes' => $threadPrefixes,
            'availableFields' => $availableFields,
            'availablePrefixes' => $availablePrefixes,
            'profiles' => $paymentProfiles,
            'listingTypes' => $listingTypes,
            'conditions' => $conditions,
            'packages' => $packages
        ];
        return $this->view('Z61\Classifieds:Category\Edit', 'z61_classifieds_category_edit', $viewParams);
    }

    public function actionEdit(ParameterBag $params)
    {
        $category = $this->assertCategoryExists($params->category_id);
        return $this->categoryAddEdit($category);
    }

    public function actionAdd()
    {
        $parentCategoryId = $this->filter('parent_category_id', 'uint');

        /** @var \Z61\Classifieds\Entity\Category $category */
        $category = $this->em()->create('Z61\Classifieds:Category');
        $category->parent_category_id = $parentCategoryId;

        return $this->categoryAddEdit($category);
    }

    protected function categorySaveProcess(\Z61\Classifieds\Entity\Category $category)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'title' => 'str',
            'description' => 'str',
            'parent_category_id' => 'uint',
            'display_order' => 'uint',
            'node_id' => 'uint',
            'moderate_listings' => 'bool',
            'thread_prefix_id' => 'uint',
            'allow_paid' => 'bool',
            'paid_feature_enable' => 'bool',
            'paid_feature_days' => 'uint',
            'payment_profile_ids' => 'array-uint',
            'listing_type_ids' => 'array-uint',
            'condition_ids' => 'array-uint',
            'package_ids' => 'array-uint',
            'contact_conversation' => 'bool',
            'contact_email' => 'bool',
            'contact_custom' => 'bool',
            'price' => 'str',
            'currency' => 'str',
            'require_listing_image' => 'bool',
            'layout_type' => 'str',
            'location_enable' => 'bool',
            'require_sold_user' => 'bool',
            'replace_forum_action_button' => 'bool',
            'phrase_listing_type' => 'str',
            'phrase_listing_condition' => 'str',
            'phrase_listing_price' => 'str',
        ]);

        $category->listing_template = $this->plugin('XF:Editor')->fromInput('listing_template');

        $form->basicEntitySave($category, $input);

        $prefixIds = $this->filter('available_prefixes', 'array-uint');
        $form->complete(function() use ($category, $prefixIds)
        {
            /** @var \Z61\Classifieds\Repository\CategoryPrefix $repo */
            $repo = $this->repository('Z61\Classifieds:CategoryPrefix');
            $repo->updateContentAssociations($category->category_id, $prefixIds);
        });

        $fieldIds = $this->filter('available_fields', 'array-str');
        $form->complete(function() use ($category, $fieldIds)
        {
            /** @var \Z61\Classifieds\Repository\CategoryField $repo */
            $repo = $this->repository('Z61\Classifieds:CategoryField');
            $repo->updateContentAssociations($category->category_id, $fieldIds);
        });

        return $form;
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->category_id)
        {
            $category = $this->assertCategoryExists($params->category_id);
        }
        else
        {
            $category = $this->em()->create('Z61\Classifieds:Category');
        }

        $this->categorySaveProcess($category)->run();

        return $this->redirect($this->buildLink('classifieds/categories') . $this->buildLinkHash($category->category_id));
    }

    public function actionDelete(ParameterBag $params)
    {
        return $this->getCategoryTreePlugin()->actionDelete($params);
    }

    public function actionSort()
    {
        return $this->getCategoryTreePlugin()->actionSort();
    }

    public function actionPermissions(ParameterBag $params)
    {
        return $this->getCategoryPermissionPlugin()->actionList($params);
    }

    public function actionPermissionsEdit(ParameterBag $params)
    {
        return $this->getCategoryPermissionPlugin()->actionEdit($params);
    }

    public function actionPermissionsSave(ParameterBag $params)
    {
        return $this->getCategoryPermissionPlugin()->actionSave($params);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Z61\Classifieds\Entity\Category
     */
    protected function assertCategoryExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Z61\Classifieds:Category', $id, $with, $phraseKey);
    }

    /**
     * @return \Z61\Classifieds\ControllerPlugin\CategoryPermission
     */
    protected function getCategoryPermissionPlugin()
    {
        /** @var \Z61\Classifieds\ControllerPlugin\CategoryPermission $plugin */
        $plugin = $this->plugin('Z61\Classifieds:CategoryPermission');
        $plugin->setFormatters('Z61\Classifieds:Category\Permission%s', 'z61_classifieds_category_permission_%s');
        $plugin->setRoutePrefix('classifieds/categories/permissions');

        return $plugin;
    }

    /**
     * @return \Z61\Classifieds\Repository\Category
     */
    protected function getCategoryRepo()
    {
        return $this->repository('Z61\Classifieds:Category');
    }
}