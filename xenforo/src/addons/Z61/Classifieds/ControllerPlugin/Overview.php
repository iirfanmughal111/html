<?php

namespace Z61\Classifieds\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;
use Z61\Classifieds\Repository\ListingLocation;

class Overview extends AbstractPlugin
{
    public function getCategoryListData(\Z61\Classifieds\Entity\Category $category = null)
    {
        $categoryRepo = $this->getCategoryRepo();
        $categories = $categoryRepo->getViewableCategories();

        $categoryTree = $categoryRepo->createCategoryTree($categories);
        // echo '<pre>';  
        // var_dump($categoryTree);exit;

   //     $categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

        return [
            'categories' => $categories,
            'categoryTree' => $categoryTree,
          //  'categoryExtras' => $categoryExtras
        ];
    }

    public function getCoreListData(array $sourceCategoryIds, \Z61\Classifieds\Entity\Category $category = null, $fetchOptions = [])
    {
        $listingRepo = $this->getListingRepo();
        $allowOwnPending = /*is_callable([$this->controller, 'hasContentPendingApproval'])
            ? $this->controller->hasContentPendingApproval()
            : */true;

        $listingFinder = $listingRepo->findListingsForOverviewList($sourceCategoryIds, [
            'allowOwnPending' => $allowOwnPending
        ]);

        $filters = $this->getListingFilterInput();

        $this->applyListingFilters($listingFinder, $filters);
        
        if (!empty($fetchOptions['listing_status']))
        {
            $listingFinder->where('listing_status', $fetchOptions['listing_status']);
        }

        if (!empty($fetchOptions['creator_id']))
        {
            $listingFinder->where('user_id', $fetchOptions['creator_id']);
        }
//        else
//        {
//            $listingFinder->whereOr(['listing_status' => 'active'], ['listing_status' => 'sold', 'user_id' => \XF::visitor()->user_id]);
//        }

        $totalListings = $listingFinder->total();

        $page = $this->filterPage();

        if ($category)
        {
            $layoutStyle = $category->layout_type;
        }
        else
        {
            $layoutStyle = $this->options()->z61ClassifiedsListLayout;
        }


        switch($layoutStyle)
        {
            case 'grid_view':
                $perPage = $this->options()->z61ClassifiedsListingsPerPageGrid;
                break;
            case 'list_view':
            default:
                $perPage = $this->options()->z61ClassifiedsListingsPerPageList;
                break;
        }

        $listingFinder->limitByPage($page, $perPage);

        $listings = $listingFinder->fetch()->filterViewable();

        if (!empty($filters['creator_id']))
        {
            $creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
        }
        else
        {
            $creatorFilter = null;
        }

        if (!empty($filters['condition_id']))
        {
            $conditionFilter = $this->em()->find('Z61\Classifieds:Condition', $filters['condition_id']);
        }
        else
        {
            $conditionFilter = null;
        }

        if (!empty($filters['listing_type_id']))
        {
            $listingTypeFilter = $this->em()->find('Z61\Classifieds:ListingType', $filters['listing_type_id']);
        }
        else
        {
            $listingTypeFilter = null;
        }

        if (!empty($filters['address']))
        {
            $addressFilter = $filters['address'];
        }
        else
        {
            $addressFilter = null;
        }

        $canInlineMod = false;
        foreach ($listings AS $listing)
        {
            /** @var \Z61\Classifieds\Entity\Listing $listing */
            if ($listing->canUseInlineModeration())
            {
                $canInlineMod = true;
                break;
            }
        }

        $params = [
            'listings' => $listings,
            'filters' => $filters,
            'creatorFilter' => $creatorFilter,
            'conditionFilter' => $conditionFilter,
            'listingTypeFilter' => $listingTypeFilter,
            'addressFilter' => $addressFilter,
            'canInlineMod' => $canInlineMod,
            'total' => $totalListings,
            'page' => $page,
            'perPage' => $perPage,
        ];

        return $params;
    }

    public function actionFilters(\Z61\Classifieds\Entity\Category $category = null)
    {
        $filters = $this->getListingFilterInput();

        if ($this->filter('apply', 'bool'))
        {
            return $this->redirect($this->buildLink(
                $category ? 'classifieds/categories' : 'classifieds',
                $category,
                $filters
            ));
        }

        if (!empty($filters['creator_id']))
        {
            $creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
        }
        else
        {
            $creatorFilter = null;
        }

        if (!empty($filters['condition_id']))
        {
            $conditionFilter = $this->em()->find('Z61\Classifieds:Condition', $filters['condition_id']);
        }
        else
        {
            $conditionFilter = null;
        }

        if (!empty($filters['listing_type_id']))
        {
            $listingTypeFilter = $this->em()->find('Z61\Classifieds:ListingType', $filters['listing_type_id']);
        }
        else
        {
            $listingTypeFilter = null;
        }

        if ($category == null)
        {
            $conditions = $this->finder('Z61\Classifieds:Condition')->fetch();
            $listingTypes = $this->finder('Z61\Classifieds:ListingType')->fetch();
        }
        else
        {
            $conditions = $category->conditions;
            $listingTypes = $category->listing_types;
        }

        $applicableCategories = $this->getCategoryRepo()->getViewableCategories($category);
        $applicableCategoryIds = $applicableCategories->keys();
        if ($category)
        {
            $applicableCategoryIds[] = $category->category_id;
        }

        $availablePrefixIds = $this->repository('Z61\Classifieds:CategoryPrefix')->getPrefixIdsInContent($applicableCategoryIds);
        $prefixes = $this->repository('Z61\Classifieds:ListingPrefix')->findPrefixesForList()
            ->where('prefix_id', $availablePrefixIds)
            ->fetch();


        $defaultOrder = $this->options()->z61ClassifiedsListDefaultOrder ?: 'expiration_date';
        $defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

        if (empty($filters['order']))
        {
            $filters['order'] = $defaultOrder;
        }
        if (empty($filters['direction']))
        {
            $filters['direction'] = $defaultDir;
        }

        $viewParams = [
            'category' => $category,
            'prefixesGrouped' => $prefixes->groupBy('prefix_group_id'),
            'filters' => $filters,
            'creatorFilter' => $creatorFilter,
            'conditionFilter' => $conditionFilter,
            'listingTypeFilter' => $listingTypeFilter,
            'listingTypes' => $listingTypes,
            'conditions' => $conditions,
        ];
        return $this->view('Z61\Classifieds:Filters', 'z61_classifieds_filters', $viewParams);
    }

    public function applyListingFilters(\Z61\Classifieds\Finder\Listing $listingFinder, array $filters)
    {
        if (!empty($filters['prefix_id']))
        {
            $listingFinder->where('prefix_id', intval($filters['prefix_id']));
        }

        if (!empty($filters['creator_id']))
        {
            $listingFinder->where('user_id', intval($filters['creator_id']));
        }

        if (!empty($filters['condition_id']))
        {
            $listingFinder->where('condition_id', intval($filters['condition_id']));
        }

        if (!empty($filters['listing_type_id']))
        {
            $listingFinder->where('listing_type_id', intval($filters['listing_type_id']));
        }

        if (!empty($filters['address']))
        {
            $listingFinder->withinDistance(
                $filters['address']['distance'],
                $filters['address']['distance_unit'],
                $filters['address']['latitude'],
                $filters['address']['longitude']
            );
        }

        $sorts = $this->getAvailableListingSorts();

        if (!empty($filters['order']) && isset($sorts[$filters['order']]))
        {
            $listingFinder->order($sorts[$filters['order']], $filters['direction']);
        }
        // else the default order has already been applied
    }

    public function getListingFilterInput()
    {
        $filters = [];

        $input = $this->filter([
            'prefix_id' => 'uint',
            'creator' => 'str',
            'creator_id' => 'uint',
            'order' => 'str',
            'direction' => 'str',
            'price' => 'str',
            'condition_id' => 'uint',
            'listing_type_id' => 'uint',
            'address' => 'str',
            'distance' => 'uint',
            'distance_unit' => 'str'
        ]);

        if ($input['prefix_id'])
        {
            $filters['prefix_id'] = $input['prefix_id'];
        }

        if ($input['condition_id'])
        {
            $filters['condition_id'] = $input['condition_id'];
        }

        if ($input['listing_type_id'])
        {
            $filters['listing_type_id'] = $input['listing_type_id'];
        }

        if ($input['creator_id'])
        {
            $filters['creator_id'] = $input['creator_id'];
        }
        else if ($input['creator'])
        {
            $user = $this->em()->findOne('XF:User', ['username' => $input['creator']]);
            if ($user)
            {
                $filters['creator_id'] = $user->user_id;
            }
        }

        if ($input['address'])
        {
            $eventLocation = $input['address'];

            /** @var ListingLocation $locationRepo */
            $locationRepo = $this->repository('Z61\Classifieds:ListingLocation');

            list($response, $status) = $locationRepo->getLocationDataForAddress($eventLocation);

            if ($response)
            {
                if ($status == 'OK')
                {
                    $filters['address'] = [
                        'distance' => $input['distance'],
                        'distance_unit' => $input['distance_unit'],
                        'latitude' => $response->results[0]->geometry->location->lat,
                        'longitude' => $response->results[0]->geometry->location->lng,
                        'formatted' => $response->results[0]->formatted_address,
                    ];
                }
            }
        }

        $sorts = $this->getAvailableListingSorts();

        if ($input['order'] && isset($sorts[$input['order']]))
        {
            if (!in_array($input['direction'], ['asc', 'desc']))
            {
                $input['direction'] = 'desc';
            }

            $defaultOrder = $this->options()->z61ClassifiedsListDefaultOrder ?: 'expiration_date';
            $defaultDir = $defaultOrder == 'expiration_date' ? 'asc' : 'desc';

            if ($input['order'] != $defaultOrder || $input['direction'] != $defaultDir)
            {
                $filters['order'] = $input['order'];
                $filters['direction'] = $input['direction'];
            }
        }
// Address is in filters at this point, so wtf
        return $filters;
    }

    public function getAvailableListingSorts()
    {
        // maps [name of sort] => field in/relative to Listing entity
        return [
            'last_edit_date' => 'last_edit_date',
            'expiration_date' => 'expiration_date',
            'listing_date' => 'listing_date',
            'title' => 'title',
            'price' => 'price'
        ];
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
}