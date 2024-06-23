<?php

namespace Z61\Classifieds\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Error;
use XF\PrintableException;
use Z61\Classifieds\Entity\Condition;
use Z61\Classifieds\Entity\ListingType;
use Z61\Classifieds\Entity\Package;

class Category extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();

        if (!$visitor->canViewClassifieds($error))
        {
            throw $this->exception($this->noPermission($error));
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $category = $this->assertViewableCategory($params->category_id);

        /** @var \Z61\Classifieds\ControllerPlugin\Overview $overviewPlugin */
        $overviewPlugin = $this->plugin('Z61\Classifieds:Overview');

        $categoryParams = $overviewPlugin->getCategoryListData($category);

        /** @var \XF\Tree $categoryTree */
        $categoryTree = $categoryParams['categoryTree'];
        $descendants = $categoryTree->getDescendants($category->category_id);

        $sourceCategoryIds = array_keys($descendants);
        $sourceCategoryIds[] = $category->category_id;

        // for any contextual widget
        $category->cacheViewableDescendents($descendants);

        $listParams = $overviewPlugin->getCoreListData($sourceCategoryIds);

        $this->assertValidPage(
            $listParams['page'],
            $listParams['perPage'],
            $listParams['total'],
            'classifieds/categories',
            $category
        );
        $this->assertCanonicalUrl($this->buildLink(
            'classifieds/categories',
            $category,
            ['page' => $listParams['page']]
        ));

        $viewParams = [
            'category' => $category,
            'pendingApproval' => $this->filter('pending_approval', 'bool'),
        ];
        $viewParams += $categoryParams + $listParams;

        return $this->view('Z61\Classifieds:Category\View', 'z61_classifieds_category_view', $viewParams);
    }

    public function actionFilters(ParameterBag $params)
    {
        $category = $this->assertViewableCategory($params->category_id);

        /** @var \Z61\Classifieds\ControllerPlugin\Overview $overviewPlugin */
        $overviewPlugin = $this->plugin('Z61\Classifieds:Overview');

        return $overviewPlugin->actionFilters($category);
    }

    public function actionAdd(ParameterBag $params)
    {
        $category = $this->assertViewableCategory($params->category_id);

        if (!$category->canAddListing($error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            $creator = $this->setupListingCreate($category);
            $creator->checkForSpam();

            if (!$creator->validate($errors))
            {
                return $this->error($errors);
            }

            /** @var \Z61\Classifieds\Entity\Listing $listing */
            $listing = $creator->save();
            $this->finalizeListingCreate($creator);

            if ($listing->Package->cost_amount > 0.00)
            {
                $this->request->set('listing_id', $listing->listing_id);

                return $this->rerouteController('Z61\Classifieds:Listing', 'pay', [
                    'listing_id' => $listing->listing_id
                ]);
            }

            if (!$listing->canView())
            {
                return $this->redirect($this->buildLink('classifieds/categories', $category, [
                    'pending_approval' => 1,
                ]));
            }
            else
            {
                return $this->redirect($this->buildLink('classifieds', $listing));
            }
        }
        else
        {
            /** @var \XF\Repository\Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');

            $draft = $category->draft_listing;

            if ($category->canUploadAndManageAttachments())
            {
                $attachmentData = $attachmentRepo->getEditorData('classifieds_listing', $category, $draft->attachment_hash);
            }
            else
            {
                $attachmentData = null;
            }

            $listing = $category->getNewListing();

            $listing->title = $draft->title ?: '';

            $viewParams = [
                'category' => $category,
                'listing' => $listing,
                'attachmentData' => $attachmentData,
                'prefixes' => $category->getUsablePrefixes(),
                'listingTypes' => $category->listing_types,
                'conditions' => $category->conditions,
                'packages' => $category->packages
            ];
            return $this->view(
                'Z61\Classifieds:Category\Add',
                'z61_classifieds_category_add_listing',
                $viewParams
            );
        }
    }

    /**
     * @param \Z61\Classifieds\Entity\Category $category
     *
     * @return \Z61\Classifieds\Service\Listing\Creator
     */
    protected function setupListingCreate(\Z61\Classifieds\Entity\Category $category)
    {
        $title = $this->filter('title', 'str');
        $content = $this->plugin('XF:Editor')->fromInput('message');

        /** @var \Z61\Classifieds\Service\Listing\Creator $creator */
        $creator = $this->service('Z61\Classifieds:Listing\Creator', $category);

        $creator->setListingContent($title, $content);

        $listingTypeId = $this->filter('listing_type_id', 'uint');
        /** @var ListingType $listingType */
        $listingType = $this->finder('Z61\Classifieds:ListingType')->where('listing_type_id', $listingTypeId)->fetchOne();

        if (!empty($listingType))
        {
            $creator->setType($listingType);
        }

        $conditionId = $this->filter('condition_id', 'uint');
        /** @var Condition $condition */
        $condition = $this->finder('Z61\Classifieds:Condition')->where('condition_id', $conditionId)->fetchOne();
        if (!empty($condition))
        {
            $creator->setCondition($condition);
        }

        $packageId = $this->filter('package_id', 'uint');
        /** @var Package $package */
        $package = $this->finder('Z61\Classifieds:Package')->where('package_id', $packageId)->fetchOne();
        if (!empty($package))
        {
            $creator->setPackage($package);
        }

        $prefixId = $this->filter('prefix_id', 'uint');
        if ($prefixId && $category->isPrefixUsable($prefixId))
        {
            $creator->setPrefix($prefixId);
        }

        if ($category->allow_paid)
        {
            $priceInput = $this->filter([
                'price' => 'str',
                'currency' => 'str'
            ]);

            $creator->setPrice($priceInput['price'], $priceInput['currency']);
        }
        
        if ($category->location_enable)
        {
            $creator->setLocation($this->filter('listing_location', 'str'));
        }

        if ($category->canEditTags())
        {
            $creator->setTags($this->filter('tags', 'str'));
        }

        if ($category->canUploadAndManageAttachments())
        {
            $creator->setListingAttachmentHash($this->filter('attachment_hash', 'str'));
        }

        $creator->setContactOptions(
            $this->filter('contact_conversation_enable', 'bool'),
            $this->filter('contact_email_enable', 'bool')
        );

        if ($category->contact_email || $category->contact_custom)
        {
            $creator->setContactInfo(
                !empty($contactOptions['email']) ? $this->filter('contact_email', 'str') : '',
                !empty($contactOptions['custom']) ? $this->filter('contact_custom', 'str') : ''
            );
        }

        $setOptions = $this->filter('_xfSet', 'array-bool');
        if ($setOptions)
        {
            $listing = $creator->getListing();

            if (isset($setOptions['listing_open']) && $listing->canLockUnlock())
            {
                $creator->setListingOpen($this->filter('listing_open', 'bool'));
            }
        }

        $customFields = $this->filter('custom_fields', 'array');
        $creator->setCustomFields($customFields);

        return $creator;
    }

    protected function finalizeListingCreate(\Z61\Classifieds\Service\Listing\Creator $creator)
    {
        $creator->sendNotifications();

        $listing = $creator->getListing();

        if (\XF::visitor()->user_id)
        {
            $creator->getCategory()->draft_listing->delete();

            if ($listing->listing_state == 'moderated')
            {
                $this->session()->setHasContentPendingApproval();
            }
        }
    }

    public function actionDraft(ParameterBag $params)
    {
        $this->assertPostOnly();

        $category = $this->assertViewableCategory($params->category_id);

        if (!$category->canAddListing($error))
        {
            return $this->noPermission($error);
        }

        /** @var \Z61\Classifieds\Service\Listing\Creator $creator */
        $creator = $this->setupListingCreate($category);
        $listing = $creator->getListing();

        $extraData = [
            'prefix_id' => $listing->prefix_id,
            'title' => $listing->title,
            'price' => $this->filter('price', 'str'),
            'tags' => $this->filter('tags', 'str'),
            'attachment_hash' => $this->filter('attachment_hash', 'str'),
            'custom_fields' => $listing->custom_fields->getFieldValues(),
            'package_id' => $this->filter('package_id', 'int'),
            'listing_type_id' => $this->filter('listing_type_id', 'int'),
            'condition_id' => $this->filter('condition_id', 'int'),
            'listing_location' => $this->filter('listing_location', 'str'),
            'contact_email_enable' => $this->filter('contact_email_enable', 'bool'),
            'contact_conversation_enable' => $this->filter('contact_conversation_enable', 'bool'),
            'contact_custom' => $this->filter('contact_custom', 'str'),
            'contact_email' => $this->filter('contact_email', 'str')
        ];

        /** @var \XF\ControllerPlugin\Draft $draftPlugin */
        $draftPlugin = $this->plugin('XF:Draft');
        return $draftPlugin->actionDraftMessage($category->draft_listing, $extraData);
    }

    public function actionWatch(ParameterBag $params)
    {
        $category = $this->assertViewableCategory($params->category_id);
        if (!$category->canWatch($error))
        {
            return $this->noPermission($error);
        }

        $visitor = \XF::visitor();

        if ($this->isPost())
        {
            if ($this->filter('stop', 'bool'))
            {
                $action = 'delete';
                $config = [];
            }
            else
            {
                $action = 'watch';
                $config = $this->filter([
                    'notify_on' => 'str',
                    'send_alert' => 'bool',
                    'send_email' => 'bool',
                    'include_children' => 'bool'
                ]);
            }

            /** @var \Z61\Classifieds\Repository\CategoryWatch $watchRepo */
            $watchRepo = $this->repository('Z61\Classifieds:CategoryWatch');
            $watchRepo->setWatchState($category, $visitor, $action, $config);

            $redirect = $this->redirect($this->buildLink('classifieds/categories', $category));
            $redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
            return $redirect;
        }
        else
        {
            $viewParams = [
                'category' => $category,
                'isWatched' => !empty($category->Watch[$visitor->user_id])
            ];
            return $this->view('Z61\Classifieds:Category\Watch', 'z61_classifieds_category_watch', $viewParams);
        }
    }

    public function actionMarkViewed(ParameterBag $params)
    {
        // TODO: Implement
    }

    public static function getActivityDetails(array $activities)
    {
        return self::getActivityDetailsForContent(
            $activities, \XF::phrase('z61_classifieds_viewing_listing_category'), 'category_id',
            function(array $ids)
            {
                $categories = \XF::em()->findByIds(
                    'Z61\Classifieds:Category',
                    $ids,
                    ['Permissions|' . \XF::visitor()->permission_combination_id]
                );

                $router = \XF::app()->router('public');
                $data = [];

                foreach ($categories->filterViewable() AS $id => $category)
                {
                    $data[$id] = [
                        'title' => $category->title,
                        'url' => $router->buildLink('classifieds/categories', $category)
                    ];
                }

                return $data;
            }
        );
    }


    /**
     * @param $categoryId
     * @param array $extraWith
     * @return \Z61\Classifieds\Entity\Category
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableCategory($categoryId = null, array $extraWith = [])
    {
        $visitor = \XF::visitor();

        $finder = $this->em()->getFinder('Z61\Classifieds:Category');
        $finder->where('category_id', $categoryId);

        /** @var \Z61\Classifieds\Entity\Category $category */
        $category = $finder->fetchOne();

        if (!$category)
        {
            throw $this->exception($this->notFound(\XF::phrase('z61_classifieds_requested_category_not_found')));
        }

        if (!$category->canView())
        {
            return $this->noPermission();
        }

        return $category;
    }
}