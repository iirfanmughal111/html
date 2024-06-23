<?php

namespace Z61\Classifieds\Spam\Cleaner;

use XF\Spam\Cleaner\AbstractHandler;

class Listing extends AbstractHandler
{
    public function cleanUp(array &$log, &$error = null)
    {
        $app = \XF::app();

        $listings = $app->finder('Z61\Classifieds:Listing')
            ->where('user_id', $this->user->user_id)
            ->fetch();

        if ($listings->count())
        {
            $submitter = $app->container('spam.contentSubmitter');
            $submitter->submitSpam('listing', $listings->keys());

            $deleteType = $app->options()->spamMessageAction == 'delete' ? 'hard' : 'soft';

            $log['listing'] = [
                'deleteType' => $deleteType,
                'listingIds' => []
            ];

            foreach ($listings AS $listingId => $listing)
            {
                $log['listing']['listingIds'][] = $listingId;

                /** @var \Z61\Classifieds\Entity\Listing $listing */
                $listing->setOption('log_moderator', false);
                if ($deleteType == 'soft')
                {
                    $listing->softDelete();
                }
                else
                {
                    $listing->delete();
                }
            }
        }

        return true;
    }

    public function restore(array $log, &$error = null)
    {
        if ($log['deleteType'] == 'soft')
        {
            $listings = \XF::app()->finder('Z61\Classifieds:Listing')
                ->where('listing_id', $log['listingIds'])
                ->fetch();

            foreach ($listings AS $listing)
            {
                /** @var \Z61\Classifieds\Entity\Listing $listing */
                $listing->setOption('log_moderator', false);
                $listing->listing_state = 'visible';
                $listing->save();
            }
        }

        return true;
    }
}