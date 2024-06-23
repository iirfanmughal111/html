<?php

namespace Z61\Classifieds\Widget;

use XF\Widget\AbstractWidget;

class LatestListings extends AbstractWidget
{
    protected $defaultOptions = [
        'limit' => 5,
        'style' => 'simple',
        'category_ids' => []
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

        $options = $this->options;
        $limit = $options['limit'];
        $categoryIds = $options['category_ids'];

        /** @var \Z61\Classifieds\Finder\Listing $finder */
        $finder = $this->finder('Z61\Classifieds:Listing');
        $finder
            ->where('listing_state', 'visible')
            ->where('listing_status', 'active')
            ->with('User')
            ->with('Category.Permissions|' . $visitor->permission_combination_id)
            ->order('listing_date', 'desc');

        if ($categoryIds && !in_array(0, $categoryIds))
        {
            $finder->where('category_id', $categoryIds);
        }

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
        $link = $router->buildLink('whats-new/classifieds', null, ['skip' => 1]);

        $viewParams = [
            'title' => $this->getTitle(),
            'link' => $link,
            'listings' => $listings,
            'style' => $options['style'],
            'hasMore' => $total > $listings->count()
        ];
        return $this->renderer('z61_classifieds_widget_new_listings', $viewParams);
    }

    public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
    {
        $options = $request->filter([
            'limit' => 'uint',
            'style' => 'str',
            'category_ids' => 'array-uint'
        ]);
        if (in_array(0, $options['category_ids']))
        {
            $options['category_ids'] = [0];
        }
        if ($options['limit'] < 1)
        {
            $options['limit'] = 1;
        }

        return true;
    }
}