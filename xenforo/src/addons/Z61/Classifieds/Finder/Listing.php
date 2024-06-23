<?php

namespace Z61\Classifieds\Finder;

use XF\Mvc\Entity\Finder;

class Listing extends Finder
{
    public function applyGlobalVisibilityChecks($allowOwnPending = false)
    {
        $visitor = \XF::visitor();
        $conditions = [];
        $viewableStates = ['visible'];

        if ($visitor->hasPermission('classifieds', 'viewDeleted'))
        {
            $viewableStates[] = 'deleted';

            $this->with('DeletionLog');
        }

        if ($visitor->hasPermission('classifieds', 'viewModerated'))
        {
            $viewableStates[] = 'moderated';
        }

        if ($visitor->user_id && $allowOwnPending)
        {
            $conditions[] = [
                'listing_state' => 'moderated',
                'user_id' => $visitor->user_id
            ];

	        $conditions[] = [
		        'listing_status' => 'awaiting_payment',
		        'user_id' => $visitor->user_id
	        ];

	        $conditions[] = [
		        'listing_status' => 'sold',
		        'user_id' => $visitor->user_id
	        ];
        }

        $conditions[] = ['listing_state', $viewableStates];

        $this->whereOr($conditions);

        return $this;
    }

    public function applyVisibilityChecksInCategory(\Z61\Classifieds\Entity\Category $category, $allowOwnPending = false)
    {
        $conditions = [];
        $viewableStates = ['visible'];

        if ($category->canViewDeletedListings())
        {
            $viewableStates[] = 'deleted';

            $this->with('DeletionLog');
        }

        $visitor = \XF::visitor();
        if ($category->canViewModeratedListings())
        {
            $viewableStates[] = 'moderated';
        }
        else if ($visitor->user_id && $allowOwnPending)
        {
            $conditions[] = [
                'listing_state' => 'moderated',
                'user_id' => $visitor->user_id
            ];
        }

        $conditions[] = ['listing_state', $viewableStates];

        $this->whereOr($conditions);

        return $this;
    }

    public function watchedOnly($userId = null)
    {
        if ($userId === null)
        {
            $userId = \XF::visitor()->user_id;
        }
        if (!$userId)
        {
            // no user, just ignore
            return $this;
        }

        $this->whereOr(
            ['Watch|' . $userId . '.user_id', '!=', null],
            ['Category.Watch|' . $userId . '.user_id', '!=', null]
        );

        return $this;
    }

    public function forFullView($includeCategory = true)
    {
        $visitor = \XF::visitor();

        $this->with('User');

        if ($visitor->user_id)
        {
            $this->with('Watch|' . $visitor->user_id);
        }

        if ($includeCategory)
        {
            $this->with(['Category']);

            if ($visitor->user_id)
            {
                $this->with('Category.Watch|' . $visitor->user_id);
            }
        }

        return $this;
    }

    public function useDefaultOrder()
    {
        $defaultOrder = $this->app()->options()->z61ClassifiedsListDefaultOrder ?: 'expiration_date';
        $defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

        $this->setDefaultOrder($defaultOrder, $defaultDir);

        return $this;
    }

    public function skipIgnored(\XF\Entity\User $user = null)
    {
        if (!$user)
        {
            $user = \XF::visitor();
        }

        if (!$user->user_id)
        {
            return $this;
        }

        if ($user->Profile && $user->Profile->ignored)
        {
            $this->where('user_id', '<>', array_keys($user->Profile->ignored));
        }

        return $this;
    }

    public function unreadOnly($userId = null)
    {
        if ($userId === null)
        {
            $userId = \XF::visitor()->user_id;
        }
        if (!$userId)
        {
            // no user, no read tracking
            return $this;
        }

        $threadReadExpression = $this->expression(
            '%s > COALESCE(%s, 0)',
            'listing_date',
            'Read|' . $userId . '.listing_read_date'
        );

        $cateoryReadExpression = $this->expression(
            '%s > COALESCE(%s, 0)',
            'Category.last_listing_date',
            'Category.Read|' . $userId . '.category_read_date'
        );

        /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
        $listingRepo = $this->em->getRepository('Z61\Classifieds:Listing');

        $this->where('listing_date', '>', $listingRepo->getReadMarkingCutOff())
            ->where($threadReadExpression)
            ->where($cateoryReadExpression);

        return $this;
    }

    public function withinDistance($within, $unit, $latitude, $longitude)
    {
        $unit = $unit ? 6371 : 3959;

        $this->whereSql("( $unit * acos(
            cos( radians( $latitude ) )
            * cos( radians( `xf_z61_classifieds_listing`.`location_lat` ) )
            * cos( radians( `xf_z61_classifieds_listing`.`location_long` ) - radians( $longitude ) )
            + sin( radians( $latitude ) )
            * sin( radians( `xf_z61_classifieds_listing`.`location_lat` ) )
        )) < $within ");

        return $this;
    }

    // bless up: https://stackoverflow.com/questions/12439801/
    protected function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earthRadius * $c;

        return $d;
    }
}