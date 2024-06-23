<?php


namespace Z61\Classifieds\Notifier\Listing;


class ListingWatch extends AbstractWatch
{
    public function getDefaultWatchNotifyData()
    {
        $listing = $this->listing;

        $finder = $this->app()->finder('Z61\Classifieds:ListingWatch');

        $finder->where('listing_id', $listing->listing_id)
            ->where('User.user_state', '=', 'valid')
            ->where('User.is_banned', '=', 0);

        $activeLimit = $this->app()->options()->watchAlertActiveOnly;
        if (!empty($activeLimit['enabled']))
        {
            $finder->where('User.last_activity', '>=', \XF::$time - 86400 * $activeLimit['days']);
        }

        $notifyData = [];
        foreach ($finder->fetchColumns(['user_id', 'email_subscribe']) AS $watch)
        {
            $notifyData[$watch['user_id']] = [
                'alert' => true,
                'email' => (bool)$watch['email_subscribe']
            ];
        }

        return $notifyData;
    }

    protected function getWatchEmailTemplateName()
    {
        return 'classifieds_watched_classifieds_listing_update';
    }
}