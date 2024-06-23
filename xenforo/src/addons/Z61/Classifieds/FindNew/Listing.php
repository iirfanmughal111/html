<?php

namespace Z61\Classifieds\FindNew;


use XF\Entity\FindNew;
use XF\FindNew\AbstractHandler;

class Listing extends AbstractHandler
{
    public function getRoute()
    {
        return 'whats-new/classifieds';
    }

    public function getPageReply(\XF\Mvc\Controller $controller, FindNew $findNew, array $results, $page, $perPage)
    {
        $canInlineMod = false;

        /** @var \Z61\Classifieds\Entity\Listing $listing */
        foreach ($results AS $listing)
        {
            if ($listing->canUseInlineModeration())
            {
                $canInlineMod = true;
                break;
            }
        }

        $viewParams = [
            'findNew' => $findNew,

            'page' => $page,
            'perPage' => $perPage,

            'listings' => $results,
            'canInlineMod' => $canInlineMod
        ];
        

        return $controller->view('Z61\Classifieds:WhatsNew\Listing', 'z61_classifieds_whats_new_listings', $viewParams);
    }

    public function getFiltersFromInput(\XF\Http\Request $request)
    {
        $filters = [];

        $visitor = \XF::visitor();

        $unread = $request->filter('unread', 'bool');
        if ($unread && $visitor->user_id)
        {
            $filters['unread'] = true;
        }

        $watched = $request->filter('watched', 'bool');
        if ($watched && $visitor->user_id)
        {
            $filters['watched'] = true;
        }

        $started = $request->filter('started', 'bool');
        if ($started && $visitor->user_id)
        {
            $filters['started'] = true;
        }


        return $filters;
    }

    public function getDefaultFilters()
    {
        $visitor = \XF::visitor();

        if ($visitor->user_id)
        {
            return ['unread' => true];
        }
        else
        {
            return [];
        }
    }

    public function getResultIds(array $filters, $maxResults)
    {
        $visitor = \XF::visitor();

        /** @var \Z61\Classifieds\Finder\Listing $listingFinder */
        $listingFinder = \XF::finder('Z61\Classifieds:Listing')
            ->with('Category', true)
            ->with('Category.Permissions|' . $visitor->permission_combination_id)
            ->where('listing_state', '<>', 'deleted')
            ->order('listing_date', 'DESC');

        $this->applyFilters($listingFinder, $filters);

        $threads = $listingFinder->fetch($maxResults);
        $threads = $this->filterResults($threads);

        // TODO: consider overfetching or some other permission limits within the query

        return $threads->keys();
    }

    public function getPageResultsEntities(array $ids)
    {
        $visitor = \XF::visitor();

        $ids = array_map('intval', $ids);

        /** @var \Z61\Classifieds\Finder\Listing $listingFinder */
        $listingFinder = \XF::finder('Z61\Classifieds:Listing')
            ->where('listing_id', $ids)
            ->forFullView(true)
            ->with('Category.Permissions|' . $visitor->permission_combination_id);

        return $listingFinder->fetch();
    }

    public function getResultsPerPage()
    {
        return \XF::options()->z61ClassifiedsListingsPerPageList;
    }

    protected function applyFilters(\Z61\Classifieds\Finder\Listing $listingFinder, array $filters)
    {
        $visitor = \XF::visitor();

        if (!empty($filters['unread']))
        {
            $listingFinder->unreadOnly($visitor->user_id);
        }
        else
        {
            $listingFinder->where('listing_date', '>', \XF::$time - (86400 * \XF::options()->readMarkingDataLifetime));
        }

        if (!empty($filters['watched']))
        {
            $listingFinder->watchedOnly($visitor->user_id);
        }

        if (!empty($filters['started']))
        {
            $listingFinder->where('user_id', $visitor->user_id);
        }
    }

}