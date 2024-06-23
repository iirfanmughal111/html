<?php

namespace Z61\Classifieds\Service\Listing;

use XF\Service\AbstractNotifier;
use Z61\Classifieds\Entity\Listing;

class Notify extends AbstractNotifier
{
    /**
     * @var Listing
     */
    protected $listing;

    public function __construct(\XF\App $app, Listing $listing)
    {
        parent::__construct($app);

        $this->listing = $listing;
    }

    protected function getExtraJobData()
    {
        return [
            'listingId' => $this->listing->listing_id
        ];
    }

    protected function loadNotifiers()
    {
        return [
            'mention' => $this->app->notifier('Z61\Classifieds:Listing\Mention', $this->listing),
            'listingWatch' => $this->app->notifier('Z61\Classifieds:Listing\ListingWatch', $this->listing),
            'categoryWatch' => $this->app->notifier('Z61\Classifieds:Listing\CategoryWatch', $this->listing),
        ];
    }

    protected function loadExtraUserData(array $users)
    {
        $permCombinationIds = [];
        foreach ($users AS $user)
        {
            $id = $user->permission_combination_id;
            $permCombinationIds[$id] = $id;
        }

        $this->app->permissionCache()->cacheMultipleContentPermsForContent(
            $permCombinationIds,
            'classifieds_category', $this->listing->category_id
        );
    }

    protected function canUserViewContent(\XF\Entity\User $user)
    {
        return \XF::asVisitor(
            $user,
            function() { return $this->listing->canView(); }
        );
    }

    public function skipUsersWatchingCategory(\Z61\Classifieds\Entity\Category $category)
    {
        $checkCategories = array_keys($category->breadcrumb_data);
        $checkCategories[] = $category->category_id;

        $db = $this->db();

        $watchers = $db->fetchAll("
			SELECT user_id, send_alert, send_email
			FROM xf_z61_classifieds_category_watch
			WHERE category_id IN (" . $db->quote($checkCategories) . ")
				AND (category_id = ? OR include_children > 0)
				AND (send_alert = 1 OR send_email = 1)
		", $category->category_id);

        foreach ($watchers AS $watcher)
        {
            if ($watcher['send_alert'])
            {
                $this->setUserAsAlerted($watcher['user_id']);
            }
            if ($watcher['send_email'])
            {
                $this->setUserAsEmailed($watcher['user_id']);
            }
        }
    }

}