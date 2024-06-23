<?php


namespace Z61\Classifieds\Notifier\Listing;


class CategoryWatch extends AbstractWatch
{
    public function getDefaultWatchNotifyData()
    {
        $listing = $this->listing;
        $category = $listing->Category;

        $checkCategories = array_keys($category->breadcrumb_data);
        $checkCategories[] = $category->category_id;

        // Look at any records watching this category or any parent. This will match if the user is watching
        // a parent category with include_children > 0 or if they're watching this category (first whereOr condition).
        $finder = $this->app()->finder('Z61\Classifieds:CategoryWatch');
        $finder->where('category_id', $checkCategories)
            ->where('User.user_state', '=', 'valid')
            ->where('User.is_banned', '=', 0)
            ->whereOr(
                ['include_children', '>', 0],
                ['category_id', $category->category_id]
            )
            ->whereOr(
                ['send_alert', '>', 0],
                ['send_email', '>', 0]
            );

        if ($this->actionType == 'update')
        {
            $finder->where('notify_on', 'update');
        }
        else
        {
            $finder->where('notify_on', 'classifieds_listing');
        }

        $activeLimit = $this->app()->options()->watchAlertActiveOnly;
        if (!empty($activeLimit['enabled']))
        {
            $finder->where('User.last_activity', '>=', \XF::$time - 86400 * $activeLimit['days']);
        }

        $notifyData = [];
        foreach ($finder->fetchColumns(['user_id', 'send_alert', 'send_email']) AS $watch)
        {
            $notifyData[$watch['user_id']] = [
                'alert' => (bool)$watch['send_alert'],
                'email' => (bool)$watch['send_email']
            ];
        }

        return $notifyData;
    }

    protected function getWatchEmailTemplateName()
    {
        return 'z61_classifieds_watched_category_listing';
    }
}