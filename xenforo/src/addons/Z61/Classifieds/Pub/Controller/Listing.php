<?php

namespace Z61\Classifieds\Pub\Controller;

use phpDocumentor\Reflection\DocBlock\Tags\Param;
use XF\Entity\User;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;
use Z61\Classifieds\Entity\Condition;
use Z61\Classifieds\Entity\ListingType;
use Z61\Classifieds\Notifier\Listing\FeedbackGiven;
use Z61\Classifieds\Notifier\Listing\Sold;

class Listing extends AbstractController
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
        if ($params->listing_id)
        {
            return $this->rerouteController(__CLASS__, 'view', $params);
        }

        /** @var \Z61\Classifieds\ControllerPlugin\Overview $overviewPlugin */
        $overviewPlugin = $this->plugin('Z61\Classifieds:Overview');

        $categoryParams = $overviewPlugin->getCategoryListData();
        $viewableCategoryIds = $categoryParams['categories']->keys();

        $listParams = $overviewPlugin->getCoreListData($viewableCategoryIds);

        $this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'classifieds');
        $this->assertCanonicalUrl($this->buildLink('classifieds', null, ['page' => $listParams['page']]));

        $viewParams = $categoryParams + $listParams;

        return $this->view('Z61\Classifieds:Overview', 'z61_classifieds_overview', $viewParams);
    }

    public function actionAwaitingPayment(ParameterBag $params)
    {
        if (!\XF::visitor()->canViewAwaitingPayments())
        {
            return $this->noPermission();
        }

        if ($params->listing_id)
        {
            return $this->rerouteController(__CLASS__, 'view', $params);
        }

        /** @var \Z61\Classifieds\ControllerPlugin\Overview $overviewPlugin */
        $overviewPlugin = $this->plugin('Z61\Classifieds:Overview');

        $categoryParams = $overviewPlugin->getCategoryListData();
        $viewableCategoryIds = $categoryParams['categories']->keys();

        $listParams = $overviewPlugin->getCoreListData($viewableCategoryIds, null, [
            'listing_status' => 'awaiting_payment',
            'creator_id' => \XF::visitor()->user_id
        ]);

        $this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'classifieds/awaiting-payment');
        $this->assertCanonicalUrl($this->buildLink('classifieds/awaiting-payment', null, ['page' => $listParams['page']]));

        $viewParams = $categoryParams + $listParams;

        return $this->view('Z61\Classifieds:Overview', 'z61_classifieds_overview', $viewParams);
    }

    public function actionAddListingChooser()
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        if (!$visitor->canAddClassified())
        {
            return $this->noPermission();
        }
        $nodeId = $this->filter('node_id', 'uint');

        $this->assertCanonicalUrl($this->buildLink('classifieds/add-listing-chooser'));

        $categoryRepo = $this->getCategoryRepo();
        $categories = $categoryRepo->getViewableCategoriesWhere(null, null, $nodeId ? ['node_id', $nodeId] : null);

        $canCreateListing = false;

        if ($categories->count() > 0)
        {
            foreach ($categories AS $category)
            {
                if ($category->canAddListing())
                {
                    $canCreateListing = true;
                    break;
                }
            }
        }
        else
        {
            return $this->error(\XF::phrase('z61_classifieds_no_categories_exist_at_this_time'));
        }

        if (!$canCreateListing)
        {
            return $this->noPermission();
        }

        $categoryTree = $categoryRepo->createCategoryTree($categories);
        $categoryTree = $categoryTree->filter(null, function($id, \Z61\Classifieds\Entity\Category $category, $depth, $children, \XF\Tree $tree)
        {
            return ($children || $category->canAddListing());
        });

        $categoryListExtras = $categoryRepo->getCategoryListExtras($categoryTree);

        $viewParams = [
            'categoryTree' => $categoryTree,
            'categoryExtras' => $categoryListExtras
        ];
        return $this->view('Z61\Classifieds:Classifieds\AddListingChooser', 'z61_classifieds_add_listing_chooser', $viewParams);
    }

    public function actionAdd(ParameterBag $params)
    {
        return $this->rerouteController('Z61\Classifieds:Listing', 'addListingChooser', $params);
    }

    public function actionView(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id, $this->getListingExtraWiths());

        $snippet = $this->app->stringFormatter()->wholeWordTrim(
            $listing->content,
           250
        );

        if ($this->options()->z61ClassifiedsAuthorOtherListingsCount && $listing->User)
        {
            $authorOthers = $this->getListingRepo()
                ->findOtherListingsByAuthor($listing)
                ->fetch($this->options()->z61ClassifiedsAuthorOtherListingsCount);
            $authorOthers = $authorOthers->filterViewable();
        }
        else
        {
            $authorOthers = $this->em()->getEmptyCollection();
        }

        $listingRepo = $this->getListingRepo();
        
        $listingRepo->markListingReadByVisitor($listing);
        $listingRepo->logListingView($listing);

        $viewParams = [
            'listing' => $listing,
            'descSnippet' => $snippet,
            'category' => $listing->Category,
            'authorOthers' => $authorOthers,
            'iconError' => $this->filter('icon_error', 'bool')
        ];
        return $this->view('Z61\Classifieds:Listing\View', 'z61_classifieds_listing_view', $viewParams);
    }

    public function actionCoverImage(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id, ['CoverImage']);

        if (!$listing->CoverImage)
        {
            return $this->redirect($this->options()->boardUrl.'/styles/default/z61/classifieds/no_image.png');

        }

        $this->request->set('no_canonical', 1);

        return $this->rerouteController('XF:Attachment', 'index', ['attachment_id' => $listing->CoverImage->attachment_id]);
    }

    public function actionEdit(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canEdit($error))
        {
            return $this->noPermission($error);
        }

        $category = $listing->Category;

        if ($this->isPost())
        {
            $editor = $this->setupListingEdit($listing);
            $editor->checkForSpam();

            if (!$editor->validate($errors))
            {
                return $this->error($errors);
            }

            $editor->save();

            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            if ($category && $category->canUploadAndManageAttachments())
            {
                /** @var \XF\Repository\Attachment $attachmentRepo */
                $attachmentRepo = $this->repository('XF:Attachment');
                $attachmentData = $attachmentRepo->getEditorData('classifieds_listing', $listing);
            }
            else
            {
                $attachmentData = null;
            }

            $viewParams = [
                'listing' => $listing,
                'category' => $category,
                'attachmentData' => $attachmentData,
                'prefixes' => $category->getUsablePrefixes($listing->prefix_id),
                'listingTypes' => $category->listing_types,
                'conditions' => $category->conditions,
            ];
            return $this->view('Z61\Classifieds:Listing\Edit', 'z61_classifieds_listing_edit', $viewParams);
        }
    }

    public function actionDelete(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canDelete('soft', $error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            $type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
            $reason = $this->filter('reason', 'str');

            if (!$listing->canDelete($type, $error))
            {
                return $this->noPermission($error);
            }

            /** @var \Z61\Classifieds\Service\Listing\Delete $deleter */
            $deleter = $this->service('Z61\Classifieds:Listing\Delete', $listing);

            if ($this->filter('author_alert', 'bool'))
            {
                $deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
            }

            $deleter->delete($type, $reason);

            $this->plugin('XF:InlineMod')->clearIdFromCookie('classifieds_listing', $listing->listing_id);

            return $this->redirect($this->buildLink('classifieds/categories', $listing->Category));
        }
        else
        {
            $viewParams = [
                'listing' => $listing,
                'category' => $listing->Category
            ];
            return $this->view('Z61\Classifieds:Listing\Delete', 'z61_classifieds_listing_delete', $viewParams);
        }
    }
    public function actionMove(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canMove($error))
        {
            return $this->noPermission($error);
        }

        $category = $listing->Category;

        if ($this->isPost())
        {
            $targetCategoryId = $this->filter('target_category_id', 'uint');

            /** @var \Z61\Classifieds\Entity\Category $targetCategory */
            $targetCategory = $this->app()->em()->find('Z61\Classifieds:Category', $targetCategoryId);
            if (!$targetCategory || !$targetCategory->canView())
            {
                return $this->error(\XF::phrase('requested_category_not_found'));
            }

            $this->setupListingMove($listing, $targetCategory)->move($targetCategory);

            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            $categoryRepo = $this->getCategoryRepo();
            $categories = $categoryRepo->getViewableCategories();

            $viewParams = [
                'listing' => $listing,
                'category' => $category,
                'prefixes' => $category->getUsablePrefixes(),
                'categoryTree' => $categoryRepo->createCategoryTree($categories)
            ];
            return $this->view('Z61\Classifieds:Listing\Move', 'z61_classifieds_listing_move', $viewParams);
        }
    }

    public function actionPay(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);

        // TODO: perm check
        $profiles = $this->repository('XF:Payment')->getPaymentProfileOptionsData();

        $viewParams = [
            'listing' => $listing,
            'profiles' => $profiles,
        ];
        return $this->view(
            'Z61\Classifieds:Listing\Pay',
            'z61_classifieds_listing_pay',
            $viewParams
        );
    }

    public function actionExtra(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->hasExtraInfoTab())
        {
            return $this->redirect($this->buildLink('classifieds', $listing));
        }

        $viewParams = [
            'listing' => $listing,
            'category' => $listing->Category
        ];
        return $this->view('Z61\Classifieds:Listing\Extra', 'z61_classifieds_listing_extra', $viewParams);
    }

//    public function actionFeature(ParameterBag $params)
//    {
//        $listing = $this->assertViewableListing($params->listing_id);
//
//        if ($this->isPost())
//        {
//
//        }
//        else
//        {
//            // TODO: perm check
//            // TODO: only show profiles available in cat
//            $profiles = $this->repository('XF:Payment')->getPaymentProfileOptionsData();
//
//            $viewParams = [
//                'listing' => $listing,
//                'profiles' => $profiles,
//            ];
//            return $this->view(
//                'Z61\Classifieds:Listing\Feature',
//                'z61_classifieds_listing_feature',
//                $viewParams
//            );
//        }
//    }

//    public function actionFeaturePurchase(ParameterBag $params)
//    {
//        $listing = $this->finder('Z61\Classifieds:Listing')->where('listing_id', $params->listing_id)->fetchOne();
//        $view = $this->view('Z61\Classifieds:Listing\FeaturePurchase', 'z61_classifieds_feature_purchase', compact('listing'));
//        return $this->addListingWrapperParams($view, 'feature');
//    }

    public function actionFilters()
    {
        /** @var \Z61\Classifieds\ControllerPlugin\Overview $overviewPlugin */
        $overviewPlugin = $this->plugin('Z61\Classifieds:Overview');

        return $overviewPlugin->actionFilters();
    }

    public function actionIp(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        $breadcrumbs = $listing->getBreadcrumbs();

        /** @var \XF\ControllerPlugin\Ip $ipPlugin */
        $ipPlugin = $this->plugin('XF:Ip');
        return $ipPlugin->actionIp($listing, $breadcrumbs);
    }

    public function actionReact(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canReact($error))
        {
            return $this->noPermission($error);
        }

        /** @var \XF\ControllerPlugin\Reaction $reactPlugin */
        $reactPlugin = $this->plugin('XF:Reaction');
        return $reactPlugin->actionReactSimple($listing, 'classifieds');
    }

    public function actionBookmark(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        /** @var \XF\ControllerPlugin\Bookmark $bookmarkPlugin */
        $bookmarkPlugin = $this->plugin('XF:Bookmark');
        return $bookmarkPlugin->actionBookmark(
            $listing, $this->buildLink('classifieds/bookmark', $listing)
        );
    }

    public function actionReactions(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);

        $breadcrumbs = $listing->getBreadcrumbs();
        $title = \XF::phrase('z61_classifieds_members_who_reacted_to_x', ['title' => $listing->title]);

        /** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
        $reactionPlugin = $this->plugin('XF:Reaction');
        return $reactionPlugin->actionReactions(
            $listing,
            'classifieds/reactions',
            $title, $breadcrumbs
        );
    }

    public function actionReport(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canReport($error))
        {
            return $this->noPermission($error);
        }

        /** @var \XF\ControllerPlugin\Report $reportPlugin */
        $reportPlugin = $this->plugin('XF:Report');
        return $reportPlugin->actionReport(
            'classifieds_listing', $listing,
            $this->buildLink('classifieds/report', $listing),
            $this->buildLink('classifieds', $listing)
        );
    }

    public function actionTags(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canEditTags($error))
        {
            return $this->noPermission($error);
        }

        /** @var \XF\Service\Tag\Changer $tagger */
        $tagger = $this->service('XF:Tag\Changer', 'classifieds_listing', $listing);

        if ($this->isPost())
        {
            $tagger->setEditableTags($this->filter('tags', 'str'));
            if ($tagger->hasErrors())
            {
                return $this->error($tagger->getErrors());
            }

            $tagger->save();

            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            $grouped = $tagger->getExistingTagsByEditability();

            $viewParams = [
                'listing' => $listing,
                'category' => $listing->Category,
                'editableTags' => $grouped['editable'],
                'uneditableTags' => $grouped['uneditable']
            ];
            return $this->view('Z61\Classifieds:Listing\Tags', 'z61_classifieds_listing_tags', $viewParams);
        }
    }

    public function actionPrefixes(ParameterBag $params)
    {
        $this->assertPostOnly();

        $categoryId = $this->filter('val', 'uint');

        /** @var \Z61\Classifieds\Entity\Category $category */
        $category = $this->em()->find('Z61\Classifieds:Category', $categoryId,
            'Permissions|' . \XF::visitor()->permission_combination_id
        );
        if (!$category)
        {
            return $this->notFound(\XF::phrase('requested_category_not_found'));
        }

        if (!$category->canView($error))
        {
            return $this->noPermission($error);
        }

        $viewParams = [
            'category' => $category,
            'prefixes' => $category->getUsablePrefixes()
        ];
        return $this->view('Z61\Classifieds:Category\Prefixes', 'z61_classifieds_category_prefixes', $viewParams);
    }

    public function actionPreview(ParameterBag $params)
    {
        $this->assertPostOnly();

        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canEdit($error))
        {
            return $this->noPermission($error);
        }

        $editor = $this->setupListingEdit($listing);

        if (!$editor->validate($errors))
        {
            return $this->error($errors);
        }


        $attachments = [];
        $tempHash = $this->filter('attachment_hash', 'str');

        $category = $listing->Category;
        if ($category && $category->canUploadAndManageAttachments())
        {
            /** @var \XF\Repository\Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentData = $attachmentRepo->getEditorData('classifieds_listing', $listing, $tempHash);
            $attachments = $attachmentData['attachments'];
        }

        return $this->plugin('XF:BbCodePreview')->actionPreview(
            $listing->content, 'classifieds_listing', $listing->User, $attachments, $listing->canViewAttachments()
        );
    }

    public function actionWatch(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return $this->noPermission();
        }

        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canWatch($error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            if ($this->filter('stop', 'bool'))
            {
                $newState = 'delete';
            }
            else if ($this->filter('email_subscribe', 'bool'))
            {
                $newState = 'watch_email';
            }
            else
            {
                $newState = 'watch_no_email';
            }

            $watchRepo = $this->repository('Z61\Classifieds:ListingWatch');
            $watchRepo->setWatchState($listing, $visitor, $newState);

            $redirect = $this->redirect($this->buildLink('classifieds', $listing));
            $redirect->setJsonParam('switchKey', $newState == 'delete' ? 'watch' : 'unwatch');
            return $redirect;
        }
        else
        {
            $viewParams = [
                'listing' => $listing,
                'isWatched' => !empty($listing->Watch[$visitor->user_id]),
            ];
            return $this->view('Z61\Classifieds:Listing\Watch', 'z61_classifieds_listing_watch', $viewParams);
        }
    }

    public function actionApprove(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canApproveUnapprove($error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            /** @var \Z61\Classifieds\Service\Listing\Approve $approver */
            $approver = \XF::service('Z61\Classifieds:Listing\Approve', $listing);
            $approver->setNotifyRunTime(1); // may be a lot happening
            $approver->approve();

            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            $viewParams = [
                'listing' => $listing,
                'category' => $listing->Category
            ];
            return $this->view('Z61\Classifieds:Listing\Approve', 'z61_classifieds_listing_approve', $viewParams);
        }
    }

    public function actionClose(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);

        if ($listing->canClose())
        {
            $listing->fastUpdate('listing_open', false);
            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            return $this->noPermission();
        }
    }

    public function actionOpen(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);

        if ($listing->canOpenListing())
        {
            $listing->fastUpdate('listing_open', true);
            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            return $this->noPermission();
        }
    }

    public function actionClearSold(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);

        if (!$listing->canClearSold())
        {
            return $this->noPermission();
        }
        if ($this->isPost())
        {
            $data = [
                'listing_status' => 'active',
                'sold_user_id' => null,
                'sold_username' => null
            ];

            $listing->fastUpdate($data);

            return $this->redirect($this->buildLink('classifieds', $listing), \XF::phrase('z61_classifieds_listing_sold_status_cleared'));
        }
        else
        {
            $viewParams = [
              'listing' => $listing
            ];
            return $this->view('Z61\Classifieds:Listing\ClearSold', 'z61_classifieds_listing_clear_sold', $viewParams);
        }
    }

    public function actionMarkSold(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);

        if (!$listing->canMarkSoldOwnListing())
        {
            return $this->noPermission();
        }

        if ($this->isPost())
        {
            $requireSoldUser = $listing->Category->require_sold_user;
            /** @var User $soldUser */
            $soldUser = $this->em()->findOne('XF:User', ['username' => $this->filter('username', 'str')]);
            if (!$soldUser && $requireSoldUser)
            {
                return $this->error(\XF::phrase('requested_user_not_found'));
            }

            if ($soldUser && $listing->user_id == $soldUser->user_id)
            {
                return $this->error(\XF::phrase('z61_classifieds_you_cannot_sell_an_item_to_yourself'));
            }
            $updateData = [
                'listing_status' => 'sold'
            ];

            if($soldUser)
            {
                $updateData += [
                    'sold_user_id' => $soldUser->user_id,
                    'sold_username' => $soldUser->username
                ];
            }

            $listing->fastUpdate($updateData);

            if ($soldUser)
            {
                /** @var Sold $notifier */
                $notifier = $this->app->notifier('Z61\Classifieds:Listing\Sold', $listing);
                $notifier->sendAlert($soldUser);
            }
            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            return $this->view('Z61\Classifieds:Listing\MarkSold', 'z61_classifieds_listing_mark_sold', [
               'listing' => $listing
            ]);
        }
    }

    public function actionContact(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);

        if (!$listing->canContactOwner())
        {
            return $this->noPermission();
        }

        $user = $listing->User;
        $category = $listing->Category;

        $conversation = $category->contact_conversation && $listing->contact_conversation_enable;
        $email = $category->contact_email && $listing->contact_email_enable;
        $custom = $category->contact_custom && !empty($listing->contact_custom);

        $conversationTitle = \XF::phrase('z61_classifieds_re_x', ['listingTitle' => $listing->title]);
        if ($conversation && !$email && !$custom)
        {
            return $this->redirect($this->buildLink('conversations/add', null, [
                'to' => $listing->username,
                'title' => $conversationTitle
            ]), '');
        }

        $viewParams = [
            'listing' => $listing,
            'user' => $user,
            'conversationTitle' => $conversationTitle
        ];

        $viewParams += compact('conversation', 'email', 'custom');

        return $this->view('Z61\Classifieds:Listing\Contact', 'z61_classifieds_listing_contact', $viewParams);
    }

    public function actionMarkRead()
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return $this->noPermission();
        }

        $markDate = $this->filter('date', 'uint');
        if (!$markDate)
        {
            $markDate = \XF::$time;
        }

        if ($this->isPost())
        {
            $categoryRepo = $this->getCategoryRepo();
            $listingRepo = $this->getListingRepo();

            $categoryList = $categoryRepo->getViewableCategories();
            $categoryIds = $categoryList->keys();

            $listingRepo->markListingsReadByVisitor($categoryIds, $markDate);

            return $this->redirect(
                $this->buildLink('classifieds'),
                \XF::phrase('z61_classifieds_all_listings_marked_as_read')
            );
        }
        else
        {
            $viewParams = [
                'date' => $markDate
            ];
            return $this->view('Z61\Classifieds:Listing\MarkRead', 'z61_classifieds_listing_mark_read', $viewParams);
        }
    }

    public function actionReassign(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canReassign($error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            $user = $this->em()->findOne('XF:User', ['username' => $this->filter('username', 'str')]);
            if (!$user)
            {
                return $this->error(\XF::phrase('requested_user_not_found'));
            }

            $canTargetView = \XF::asVisitor($user, function() use ($listing)
            {
                return $listing->canView();
            });
            if (!$canTargetView)
            {
                return $this->error(\XF::phrase('z61_new_owner_must_be_able_to_view_this_resource'));
            }

            /** @var \Z61\Classifieds\Service\Listing\Reassign $reassigner */
            $reassigner = $this->service('Z61\Classifieds:Listing\Reassign', $listing);

            if ($this->filter('alert', 'bool'))
            {
                $reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
            }

            $reassigner->reassignTo($user);

            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            $viewParams = [
                'listing' => $listing,
                'category' => $listing->Category
            ];
            return $this->view('Z61\Classifieds:Listing\Reassign', 'z61_classifieds_listing_reassign', $viewParams);
        }
    }

    public function actionSetCoverImage(ParameterBag $params)
    {
        $listing = $this->assertViewableListing($params->listing_id);
        if (!$listing->canSetCoverImage($error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            $coverImageId = $this->filter('attachment_id', 'int');

            $listing->cover_image_id = $coverImageId;
            $listing->save();

            return $this->redirect($this->buildLink('classifieds', $listing));
        }
        else
        {
            $viewParams = [
                'listing' => $listing,
                'category' => $listing->Category
            ];
            return $this->view('Z61\Classifieds:Listing\CoverImage', 'z61_classifieds_listing_cover_image', $viewParams);
        }
    }

    public function actionGiveFeedback(ParameterBag $params)
    {
        /** @var \Z61\Classifieds\Entity\Listing $listing */
        $listing = $this->assertViewableListing($params->listing_id);

        if (!$listing->canGiveFeedback())
        {
            return $this->noPermission();
        }

        if ($this->isPost())
        {
            $visitor = \XF::visitor();

            $input = $this->filter([
                'role' => 'str',
                'rating' => 'str',
                'feedback' => 'str',
            ]);

            $input['listing_id'] = $listing->listing_id;

            if ($visitor->user_id == $listing->user_id)
            {
                $toUser = $listing->SoldUser;
            }
            else
            {
                $toUser = $listing->User;
            }

            $input['from_user_id'] = $visitor->user_id;
            $input['from_username'] = $visitor->username;
            $input['to_user_id'] = $toUser->user_id;
            $input['to_username'] = $toUser->username;

            /** @var \Z61\Classifieds\Entity\Feedback $entity */
            $entity = \XF::em()->create('Z61\Classifieds:Feedback');
            $entity->bulkSet($input);
            $entity->save();

            /** @var FeedbackGiven $notifier */
            $notifier = $this->app->notifier('Z61\Classifieds:Listing\FeedbackGiven', $entity);
            $notifier->sendAlert($toUser);

            return $this->redirect(
                $this->buildLink('members/feedback', $toUser)
            );
        }

        return $this->view('Z61\Classifeds:Listing\Feedback', 'z61_classifieds_give_feedback', [
           'listing' => $listing
        ]);
    }

    protected function addListingWrapperParams(View $view, $selected)
    {
        $view->setParam('pageSelected', $selected);
        return $view;
    }

    /**
     * @param \Z61\Classifieds\Entity\Listing $listing
     * @param \Z61\Classifieds\Entity\Category $category
     *
     * @return \Z61\Classifieds\Service\Listing\Move
     */
    protected function setupListingMove(\Z61\Classifieds\Entity\Listing $listing, \Z61\Classifieds\Entity\Category $category)
    {
        $options = $this->filter([
            'notify_watchers' => 'bool',
            'author_alert' => 'bool',
            'author_alert_reason' => 'str',
            'prefix_id' => 'uint'
        ]);

        /** @var \Z61\Classifieds\Service\Listing\Move $mover */
        $mover = $this->service('Z61\Classifieds:Listing\Move', $listing);

        if ($options['author_alert'])
        {
            $mover->setSendAlert(true, $options['author_alert_reason']);
        }

        if ($options['notify_watchers'])
        {
            $mover->setNotifyWatchers();
        }

        if ($options['prefix_id'] !== null)
        {
            $mover->setPrefix($options['prefix_id']);
        }

        $mover->addExtraSetup(function($listing, $category)
        {
            $listing->title = $this->filter('title', 'str');
        });

        return $mover;
    }

    /**
     * @param \Z61\Classifieds\Entity\Listing $listing
     *
     * @return \Z61\Classifieds\Service\Listing\Edit
     */
    protected function setupListingEdit(\Z61\Classifieds\Entity\Listing $listing)
    {
        $title =  $this->filter('title', 'str');
        $content = $this->plugin('XF:Editor')->fromInput('message');

        /** @var \Z61\Classifieds\Service\Listing\Edit $editor */
        $editor = $this->service('Z61\Classifieds:Listing\Edit', $listing);

        $editor->setListingContent($title, $content);

        $prefixId = $this->filter('prefix_id', 'uint');
        if ($prefixId && $listing->Category->isPrefixUsable($prefixId))
        {
            $editor->setPrefix($prefixId);
        }

        $listingTypeId = $this->filter('listing_type_id', 'uint');
        /** @var ListingType $listingType */
        $listingType = $this->finder('Z61\Classifieds:ListingType')->where('listing_type_id', $listingTypeId)->fetchOne();

        if (!empty($listingType))
        {
            $editor->setType($listingType);
        }

        $conditionId = $this->filter('condition_id', 'uint');
        /** @var Condition $condition */
        $condition = $this->finder('Z61\Classifieds:Condition')->where('condition_id', $conditionId)->fetchOne();

        if (!empty($condition))
        {
            $editor->setCondition($condition);
        }

        $customFields = $this->filter('custom_fields', 'array');
        $editor->setCustomFields($customFields);

        if ($listing->canShowPrice())
        {
            $purchaseFields = $this->filter([
                'price' => 'num',
                'currency' => 'str',
            ]);
            $editor->setPrice($purchaseFields['price'], $purchaseFields['currency']);
        }

        if ($listing->Category->location_enable)
        {
            $editor->setLocation($this->filter('listing_location', 'str'));
        }

        if ($listing->Category->canUploadAndManageAttachments())
        {
            $editor->setListingAttachmentHash($this->filter('attachment_hash', 'str'));
        }

        if ($this->filter('author_alert', 'bool') && $listing->canSendModeratorActionAlert())
        {
            $editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
        }

        if ($listing->canEditTags())
        {
            $editor->setTags($this->filter('tags', 'str'));
        }

        $editor->setContactOptions(
            $this->filter('contact_conversation_enable', 'bool'),
            $this->filter('contact_email_enable', 'bool')
        );

        if ($listing->Category->contact_email || $listing->Category->contact_custom)
        {
            $editor->setContactInfo(
                $this->filter('contact_email', 'str'),
                $this->filter('contact_custom', 'str')
            );
        }

        return $editor;
    }



    public static function getActivityDetails(array $activities)
    {
        return self::getActivityDetailsForContent(
            $activities, \XF::phrase('z61_classifieds_viewing_listing'), 'listing_id',
            function(array $ids)
            {
                $listings = \XF::em()->findByIds(
                    'Z61\Classifieds:Listing',
                    $ids,
                    ['Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id]
                );

                $router = \XF::app()->router('public');
                $data = [];

                foreach ($listings->filterViewable() AS $id => $listing)
                {
                    $data[$id] = [
                        'title' => $listing->title,
                        'url' => $router->buildLink('classifieds', $listing)
                    ];
                }

                return $data;
            },
            \XF::phrase('z61_classifieds_viewing_listings')
        );
    }

    protected function getListingExtraWiths()
    {
        $extraWith = ['Featured'];
        $userId = \XF::visitor()->user_id;
        if ($userId)
        {
            $extraWith[] = 'Watch|' . $userId;
        }

        return $extraWith;
    }

    /**
     * @return \Z61\Classifieds\Repository\Listing
     */
    protected function getListingRepo()
    {
        return $this->repository('Z61\Classifieds:Listing');
    }

    /**
     * @return \Z61\Classifieds\Repository\Category
     */
    protected function getCategoryRepo()
    {
        return $this->repository('Z61\Classifieds:Category');
    }

    /**
     * @return \XF\Repository\Node
     */
    protected function getNodeRepo()
    {
        return $this->repository('XF:Node');
    }
}