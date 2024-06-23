<?php

namespace FS\AuctionPlugin\Pub\Controller;

use XF\Mvc\ParameterBag;

use XF\Pub\Controller\AbstractController;

class AuctionListing extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        $page = 0;
        $perPage = 0;
        $categories = $this->finder('FS\AuctionPlugin:Category');
        $categoryTree = $this->createCategoryTree($categories->fetch());

        if ($this->filter('search', 'uint')) {
            $finder = $this->getSearchFinder();


            if (count($finder->getConditions()) == 0) {
                return $this->error(\XF::phrase('please_complete_required_field'));
            }
        } else if ($params->category_id) {
            $finder = $this->finder('FS\AuctionPlugin:AuctionListing');

            $finder->where('category_id', $params->category_id);
        } else {

            $options = \XF::options();
            $perPage = $options->fs_auction_per_page;

            $page = $params->page;

            $finder = $this->finder('FS\AuctionPlugin:AuctionListing');


            $finder->limitByPage($page, $perPage);
            $finder->order('last_bumping', 'DESC');
        }


        $viewParams = [
            'categories' => $categories,
            'categoryTree' => $categoryTree,
            'listings' => $finder->fetch(),

            'stats' => $this->auctionStatistics(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),
            'totalReturn' => count($finder->fetch()),

            'conditions' => $this->filterSearchConditions(),
        ];

        return $this->view('FS\AuctionPlugin:AuctionListing', 'fs_auctionArchive', $viewParams);
    }

    public function actionViewAuction(ParameterBag $params)
    {
        $auction = $this->Finder('FS\AuctionPlugin:AuctionListing')->whereId($params->auction_id)->fetchOne();
        if (!$auction) {
            return $this->error('data not found');
        }

        $options = \XF::options();
        $dropDownListLimit = $options->fs_auction_dropDown_list_limit;

        $tempBiddings = $this->Finder('FS\AuctionPlugin:Bidding')->where('auction_id', $params->auction_id)->order('bidding_amount', 'DESC');
        $bidding = $tempBiddings->fetch();

        if (isset($auction->Thread) && $auction->Thread->isUnread()) {
            $threadRepo = $this->repository('XF:Thread');
            $threadRepo->markThreadReadByVisitor($auction->Thread);
        }

        $viewParams = [
            'auction' => $auction,
            'bidding' => $bidding,
            'highestBidId' => key(reset($bidding)),

            'dropDownListLimit' => $dropDownListLimit,

        ];

        return $this->view(
            'FS\AuctionPlugin',
            'fs_auction_view_single',
            $viewParams
        );
    }

    protected function getSearchFinder()
    {
        $conditions = $this->filterSearchConditions();

        $node_id = $this->options()->fs_auction_applicable_forum;
        $finder = $this->finder('XF:Thread')->where('node_id', $node_id)->where('auction_end_date', '!=', 0);

        if ($conditions['fs_auction_username'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_auction_username'])->fetchOne();
            if ($User) {
                $finder->where('user_id', $User['user_id']);
            }
        }

        if ($conditions['fs_auction_status'] != 'all') {
            if ($conditions['fs_auction_status'] == '1') {
                $finder->where('auction_end_date', '>=', \XF::$time);
            } else {
                $finder->where('auction_end_date', '<=', \XF::$time);
            }
        }

        $threadIds = $finder->pluckfrom('thread_id')->fetch()->toArray();

        $finder = $this->finder('FS\AuctionPlugin:AuctionListing')->where('thread_id', $threadIds);
        if ($conditions['fs_auction_cat'] != '0') {
            $finder->where('category_id', $conditions['fs_auction_cat']);
        }

        return $finder;
    }

    /**
     * @param null $categories
     * @param int $rootId
     *
     * @return \XF\Tree
     */
    public function createCategoryTree($categories = null, $rootId = 0)
    {
        if ($categories === null) {
            $categories = $this->findCategoryList()->fetch();
        }
        return new \XF\Tree($categories, 'parent_category_id', $rootId);
    }

    public function actionViewType(ParameterBag $params)
    {
        $visitor = \XF::visitor();

        if ($visitor->user_id != 0 && $visitor->layout_type != $params->category_id) {

            $visitor->fastUpdate('layout_type', $params->category_id);
        }

        return $this->redirect(
            $this->getDynamicRedirect($this->buildLink('auction'), false)
        );
    }

    public function actionAddListingChooser()
    {
        /** @var \FS\AuctionPlugin\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        if (!$visitor->canAddAuctions()) {
            return $this->noPermission();
        }
        $this->assertCanonicalUrl($this->buildLink('auction/add-listing-chooser'));

        $finder = $this->finder('FS\AuctionPlugin:Category')->order('category_id', 'DESC')->fetch();


        if ($finder->count() < 0) {
            return $this->error(\XF::phrase('fs_auction_no_categories_exist_at_this_time'));
        }

        $finder = $this->finder('FS\AuctionPlugin:Category')->order('category_id', 'DESC')->fetch();


        $viewParams = [
            'categories' => $finder
        ];

        return $this->view('FS\AuctionPlugin:Auction\AddListingChooser', 'fs_auction_categories_add_listing_chooser', $viewParams);
    }

    public function actionAdd(ParameterBag $params)
    {
        return $this->rerouteController('FS\AuctionPlugin:AuctionListing', 'addListingChooser', $params);
    }

    public function actionRefineSearch(ParameterBag $params)
    {
        $categories = $this->finder('FS\AuctionPlugin:Category')->fetch();

        $viewParams = [
            'conditions' => $this->filterSearchConditions(),
            'categories' => $categories,
        ];

        return $this->view('FS\AuctionPlugin:AuctionListing\RefineSearch', 'fs_auction_search_filter', $viewParams);
    }

    protected function filterSearchConditions()
    {
        return $this->filter([
            'fs_auction_username' => 'str',
            'fs_auction_status' => 'str',
            'fs_auction_cat' => 'str',
        ]);
    }


    protected function auctionStatistics()
    {
        $cat = $this->finder('FS\AuctionPlugin:Category');
        $auctionsFinder = $this->finder('FS\AuctionPlugin:AuctionListing');
        $getExpiredAuctions = $this->finder('FS\AuctionPlugin:AuctionListing');

        $node_id = $this->options()->fs_auction_applicable_forum;
        $ThreadFinder = $this->finder('XF:Thread')->where('node_id', $node_id)->where('auction_end_date', '!=', 0);

        $ThreadFinderExpired = clone $ThreadFinder;


        return [
            'categories' => [
                'title' => \XF::phrase('fs_stats_categories'),
                'count' => $cat->total(),
            ],

            'auctions' => [
                'title' => \XF::phrase('fs_stats_auctions'),
                'count' => $auctionsFinder->total(),
            ],

            'activeAuctions' => [
                'title' => \XF::phrase('fs_stats_auctions_active'),
                'count' => $ThreadFinder->where('auction_end_date', '>', time())->total(),
            ],

            'expiredAuctions' => [
                'title' => \XF::phrase('fs_stats_auctions_expired'),
                'count' => $ThreadFinderExpired->where('auction_end_date', '<', time())->total(),
            ],
        ];
    }

    protected function getCategoryRepo()
    {
        return $this->repository('FS\AuctionPlugin:Category');
    }
}
