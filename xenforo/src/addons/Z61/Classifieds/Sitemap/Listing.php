<?php

namespace Z61\Classifieds\Sitemap;

use XF\Sitemap\AbstractHandler;
use XF\Sitemap\Entry;

class Listing extends AbstractHandler
{
    public function getRecords($start)
    {
        $user = \XF::visitor();

        $ids = $this->getIds('xf_z61_classifieds_listing', 'listing_id', $start);

        $finder = $this->app->finder('Z61\Classifieds:Listing');
        $articles = $finder
            ->where('listing_id', $ids)
            ->with(['Category', 'Category.Permissions|' . $user->permission_combination_id])
            ->order('listing_id')
            ->fetch();

        return $articles;
    }

    public function getEntry($record)
    {
        $url = $this->app->router('public')->buildLink('canonical:classifieds', $record);
        return Entry::create($url, [
            'lastmod' => $record->last_edit_date ?: $record->listing_date
        ]);
    }

    public function isIncluded($record)
    {
        /** @var $record \Z61\Classifieds\Entity\Listing*/
        if (!$record->isVisible())
        {
            return false;
        }
        return $record->canView();
    }
}