<?php

namespace Z61\Classifieds\XF\Pub\Controller;


class Watched extends XFCP_Watched
{
    public function actionClassifiedsCategories()
    {
        $this->setSectionContext('classifieds');

        $watchedFinder = $this->finder('Z61\Classifieds:CategoryWatch');
        $watchedCategories = $watchedFinder->where('user_id', \XF::visitor()->user_id)
            ->keyedBy('category_id')
            ->fetch();

        /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
        $categoryRepo = $this->repository('Z61\Classifieds:Category');
        $categories = $categoryRepo->getViewableCategories();
        $categoryTree = $categoryRepo->createCategoryTree($categories);
        $categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

        $viewParams = [
            'watchedCategories' => $watchedCategories,
            'categoryTree' => $categoryTree,
            'categoryExtras' => $categoryExtras
        ];
        return $this->view(
            'Z61\Classifieds:Watched\Categories',
            'z61_classifieds_watched_listing_categories',
            $viewParams
        );
    }

    public function actionClassifiedsCategoriesUpdate()
    {
        $this->assertPostOnly();
        $this->setSectionContext('classifieds');

        /** @var \Z61\Classifieds\Repository\CategoryWatch $watchRepo */
        $watchRepo = $this->repository('Z61\Classifieds:CategoryWatch');

        $inputAction = $this->filter('watch_action', 'str');
        $action = $this->getCategoryWatchActionConfig($inputAction, $config);

        if ($action)
        {
            $visitor = \XF::visitor();

            $ids = $this->filter('ids', 'array-uint');
            $categories = $this->em()->findByIds('Z61\Classifieds:Category', $ids);

            /** @var \Z61\Classifieds\Entity\Category $category */
            foreach ($categories AS $category)
            {
                $watchRepo->setWatchState($category, $visitor, $action, $config);
            }
        }

        return $this->redirect(
            $this->getDynamicRedirect($this->buildLink('watched/classifieds-categories'))
        );
    }

    protected function getCategoryWatchActionConfig($inputAction, array &$config = null)
    {
        $config = [];

        $parts = explode(':', $inputAction, 2);

        $inputAction = $parts[0];
        $boolSwitch = isset($parts[1]) ? ($parts[1] == 'on') : false;

        switch ($inputAction)
        {
            case 'send_email':
            case 'send_alert':
            case 'include_children':
                $config = [$inputAction => $boolSwitch];
                return 'update';

            case 'delete':
                return 'delete';

            default:
                return null;
        }
    }

    public function actionClassifieds()
    {
        $this->setSectionContext('classifieds');

        $page = $this->filterPage();
        $perPage = 20;

        /** @var \Z61\Classifieds\ControllerPlugin\Overview $listingPlugin */
        $listingPlugin = $this->plugin('Z61\Classifieds:Overview');
        $categoryParams = $listingPlugin->getCategoryListData();

        $categoryIds = $categoryParams['categories']->keys();

        /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
        $listingRepo = $this->repository('Z61\Classifieds:Listing');
        $finder = $listingRepo->findListingsForWatchedList($categoryIds);

        $total = $finder->total();
        $listings = $finder->limitByPage($page, $perPage)->fetch();

        $viewParams = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'listings' => $listings->filterViewable()
        ];

        return $this->view(
            'Z61\Classifieds:Watched\Listings',
            'z61_classifieds_watched_listings',
            $viewParams
        );
    }

    public function actionClassifiedsManage()
    {
        $this->setSectionContext('classifieds');

        if (!$state = $this->filter('state', 'str'))
        {
            return $this->redirect($this->buildLink('watched/classifieds'));
        }

        if ($this->isPost())
        {
            /** @var \Z61\Classifieds\Repository\ListingWatch $listingWatchRepo */
            $listingWatchRepo = $this->repository('Z61\Classifieds:ListingWatch');

            if ($action = $this->getListingWatchAction($state))
            {
                $listingWatchRepo->setWatchStateForAll(\XF::visitor(), $action);
            }

            return $this->redirect($this->buildLink('watched/classifieds'));
        }
        else
        {
            $viewParams = [
                'state' => $state
            ];
            return $this->view('Z61\Classifieds:Watched\ListingsManage', 'z61_classifieds_watched_listings_manage', $viewParams);
        }
    }

    public function actionClassifiedsUpdate()
    {
        $this->assertPostOnly();
        $this->setSectionContext('classifieds');

        /** @var \Z61\Classifieds\Repository\ListingWatch $watchRepo */
        $watchRepo = $this->repository('Z61\Classifieds:ListingWatch');

        $inputAction = $this->filter('watch_action', 'str');
        $action = $this->getListingWatchAction($inputAction);

        if ($action)
        {
            $ids = $this->filter('ids', 'array-uint');
            $listings = $this->em()->findByIds('Z61\Classifieds:Listing', $ids);
            $visitor = \XF::visitor();

            /** @var \Z61\Classifieds\Entity\Listing $listing */
            foreach ($listings AS $listing)
            {
                $watchRepo->setWatchState($listing, $visitor, $action);
            }
        }

        return $this->redirect(
            $this->getDynamicRedirect($this->buildLink('watched/classifieds'))
        );
    }

    protected function getListingWatchAction($inputAction)
    {
        $config = [];

        switch ($inputAction)
        {
            case 'email_subscribe:on':
                return 'update';

            case 'email_subscribe:off':
                return 'update';

            case 'delete':
                return 'delete';
            default:
                return null;
        }
    }
}