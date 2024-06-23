<?php

namespace Z61\Classifieds\Widget;


use XF\Widget\AbstractWidget;
use Z61\Classifieds\Entity\Listing;

class NearbyListings extends AbstractWidget
{
    protected $defaultOptions = [
        'limit' => 5,
        'distance' => 5,
        'distance_unit' => 'km'
    ];

    protected function getDefaultTemplateParams($context)
    {
        $params = parent::getDefaultTemplateParams($context);
        if ($context == 'options')
        {
            $categoryRepo = $this->app->repository('Z61\Classifieds:Category');
            $params['categoryTree'] = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
        }
        return $params;
    }

    public function render()
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        if (!method_exists($visitor, 'canViewClassifieds') || !$visitor->canViewClassifieds())
        {
            return '';
        }

        /** @var Listing $listing */
        $listing = $this->contextParams['listing'];

        if (!$listing || empty($listing->location_lat) || empty($listing->location_long))
        {
            return '';
        }
        $options = $this->options;
        $limit = $options['limit'];

        /** @var \Z61\Classifieds\Finder\Listing $finder */
        $finder = $this->finder('Z61\Classifieds:Listing');
        $finder
            ->withinDistance($options['distance'], $options['distance_unit'], $listing->location_lat, $listing->location_long)
            ->where('listing_id', '<>', $listing->listing_id)
            ->where('listing_state', 'visible')
            ->where('listing_status', 'active')
            ->with('User')
            ->with('Category.Permissions|' . $visitor->permission_combination_id)
            ->order('listing_date', 'desc');

        if ($options['style'] == 'full')
        {
            $finder->forFullView(true);
        }

        $listings = $finder->fetch(max($limit * 2, 10));

        /** @var \Z61\Classifieds\Entity\Listing $listing */
        foreach ($listings AS $listingId => $listing)
        {
            if (!$listing->canView() || $visitor->isIgnoring($listing->user_id))
            {
                unset($listings[$listingId]);
            }
        }

        $total = $listings->count();
        $listings = $listings->slice(0, $limit, true);

        $router = $this->app->router('public');
        $link = $router->buildLink('classifieds');

        $viewParams = [
            'title' => $this->getTitle(),
            'link' => $link,
            'listings' => $listings,
            'style' => $options['style'],
            'hasMore' => $total > $listings->count()
        ];
        return $this->renderer('z61_classifieds_widget_nearby_listings', $viewParams);
    }

    public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
    {
        $options = $request->filter([
            'limit' => 'uint',
            'distance' => 'uint',
            'distance_unit' => 'str',
        ]);

        if ($options['limit'] < 1)
        {
            $options['limit'] = 1;
        }

        if ($options['distance'] < 1)
        {
            $options['distance'] = 5;
        }

        return true;
    }
}