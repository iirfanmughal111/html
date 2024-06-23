<?php

namespace Z61\Classifieds\Service\Listing;

use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Category;
use Z61\Classifieds\Entity\Listing;

class Feature extends AbstractService
{
    /**
     * @var Listing
     */
    protected $listing;

    /**
     * @var Category
     */
    protected $category;

    /** @var bool */
    protected $paid;

    public function __construct(\XF\App $app, Listing $listing, $paid = false)
    {
        parent::__construct($app);
        if (is_null($listing))
        {
            throw new \Exception('Listing cannot be null');
        }

        $this->listing = $listing;
        $this->category = $listing->Category;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function feature()
    {
        $db = $this->db();
        $db->beginTransaction();

        $affected = $db->insert('xf_z61_classifieds_listing_feature', [
            'listing_id' => $this->listing->listing_id,
            'user_id' => \XF::visitor()->user_id,

            'date' => \XF::$time,
        ], false, 'date = VALUES(date)');

        if ($affected == 1)
        {
            // insert
            $this->onNewFeature();
        }

        $db->commit();
    }

    protected function onNewFeature()
    {
        if ($this->listing->isVisible())
        {
            $category = $this->listing->Category;
            if ($category)
            {
                $category->featured_count++;
                $category->save();
            }
        }

        $this->app->logger()->logModeratorAction('classifieds_listing', $this->listing, 'feature');
    }

    public function unfeature()
    {
        $db = $this->db();
        $db->beginTransaction();

        $affected = $db->delete('xf_z61_classifieds_listing_feature', 'listing_id = ?', $this->listing->listing_id);
        if ($affected)
        {
            $this->onUnfeature();
        }

        $db->commit();
    }

    protected function onUnfeature()
    {
        if ($this->listing->isVisible())
        {
            $category = $this->category;
            if ($category)
            {
                $category->featured_count--;
                $category->save();
            }
        }

        $this->app->logger()->logModeratorAction('classifieds_listing', $this->listing, 'unfeature');
    }
}