<?php


namespace Z61\Classifieds\Repository;


use XF\Mvc\Entity\Repository;

class Listing extends Repository
{
    public function findOtherListingsByAuthor(\Z61\Classifieds\Entity\Listing $listing)
    {
        /** @var \Z61\Classifieds\Finder\Listing $listingFinder */
        $listingFinder = $this->finder('Z61\Classifieds:Listing');

        $listingFinder
            ->with(['User', 'Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id])
            ->where('listing_state', 'visible')
            ->where('user_id', $listing->user_id)
            ->where('listing_id', '<>', $listing->listing_id);

        $listingFinder->setDefaultOrder('listing_date', 'desc');

        return $listingFinder;
    }

    public function findListingsForOverviewList(array $viewableCategoryIds = null, array $limits = [])
    {
        $limits = array_replace([
            'visibility' => true,
            'allowOwnPending' => true
        ], $limits);

        /** @var \Z61\Classifieds\Finder\Listing $listingFinder */
        $listingFinder = $this->finder('Z61\Classifieds:Listing');

        if (is_array($viewableCategoryIds))
        {
            $listingFinder->where('category_id', $viewableCategoryIds);
        }
        else
        {
            $listingFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
        }

        $listingFinder
            ->forFullView(true)
            ->useDefaultOrder();

        if ($limits['visibility'])
        {
            $listingFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
        }

        return $listingFinder;
    }

    public function findListingsByUser($userId, array $viewableCategoryIds = null, array $limits = [])
    {
        /** @var \Z61\Classifieds\Finder\Listing $listingFinder */
        $listingFinder = $this->finder('Z61\Classifieds:Listing');

        $listingFinder->where('user_id', $userId)
            ->forFullView(true)
            ->setDefaultOrder('expiration_date', 'desc');

        if (is_array($viewableCategoryIds))
        {
            // if we have viewable category IDs, we likely have those permissions
            $listingFinder->where('category_id', $viewableCategoryIds);
        }
        else
        {
            $listingFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
        }

        $limits = array_replace([
            'visibility' => true,
            'allowOwnPending' => $userId == \XF::visitor()->user_id
        ], $limits);

        if ($limits['visibility'])
        {
            $listingFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
        }

        return $listingFinder;
    }

    public function findListingsForWatchedList($categoryIds = null, $userId = null, array $limits = [])
    {
        $limits['categoryIds'] = $categoryIds;
        $limits['visibility'] = false;

        $finder = $this->findListingsForOverviewList($limits);

        if ($userId === null)
        {
            $userId = \XF::visitor()->user_id;
        }
        $userId = intval($userId);

        $finder
            ->with('Watch|' . $userId, true)
            ->with('Read|' . $userId)
            ->where('listing_state', 'visible')
            ->setDefaultOrder('listing_date', 'DESC');

        return $finder;
    }

    public function findListingForThread(\XF\Entity\Thread $thread)
    {
        /** @var \Z61\Classifieds\Finder\Listing $finder */
        $finder = $this->finder('Z61\Classifieds:Listing');

        $finder->where('discussion_thread_id', $thread->thread_id)
            ->forFullView()
            ->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);

        return $finder;
    }

    public function findFeaturedListings(array $viewableCategoryIds = null)
    {
        /** @var \Z61\Classifieds\Finder\Listing $listingFinder */
        $listingFinder = $this->finder('Z61\Classifieds:Listing');

        if (is_array($viewableCategoryIds))
        {
            $listingFinder->where('category_id', $viewableCategoryIds);
        }
        else
        {
            $listingFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
        }

        $listingFinder
            ->with('Featured', true)
            ->where('Featured.expiration_date', '<', \XF::$time)
            ->where('listing_state', 'visible')
            ->forFullView(true)
            ->setDefaultOrder($listingFinder->expression('RAND()'));

        return $listingFinder;
    }

    public function expireListingsPastExpiration($cutOff = null)
    {
        if (is_null($cutOff))
        {
            $cutOff = \XF::$time;
        }

        $listings = $this->finder('Z61\Classifieds:Listing')
                    ->with('Category')
                    ->where('expiration_date', '<', $cutOff)
                    ->where('listing_open', 1)->fetch();

        if ($listings->count())
        {
            $db = $this->db();

            $db->beginTransaction();
            /** @var \Z61\Classifieds\Entity\Listing $listing */
            foreach($listings as $listing)
            {
                if ($listing->Category->exclude_expired)
                {
                    $listing->addCascadedSave($listing->Category);
                    $listing->Category->listingRemoved($listing);
                }
                $listing->listing_open = false;
                $listing->listing_status = 'expired';
                $listing->save();
            }

            $db->commit();
        }
    }

    public function sendModeratorActionAlert(
        \Z61\Classifieds\Entity\Listing $listing, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null
    )
    {
        if (!$forceUser)
        {
            if (!$listing->user_id || !$listing->User)
            {
                return false;
            }

            $forceUser = $listing->User;
        }

        $extra = array_merge([
            'title' => $listing->title,
            'prefix_id' => $listing->prefix_id,
            'link' => $this->app()->router('public')->buildLink('nopath:classifieds', $listing),
            'reason' => $reason
        ], $extra);

        /** @var \XF\Repository\UserAlert $alertRepo */
        $alertRepo = $this->repository('XF:UserAlert');
        $alertRepo->alert(
            $forceUser,
            0, '',
            'classifieds_listing', $forceUser->user_id,
            "{$action}", $extra
        );

        return true;
    }

    public function getAvailableCurrencies(\Z61\Classifieds\Entity\Listing $listing = null)
    {
        $listingCurrencies = preg_split('/\s/', $this->options()->z61ClassifiedsListingCurrencies, -1, PREG_SPLIT_NO_EMPTY);
        $currencyData = $this->app()->data('XF:Currency')->getCurrencyData();
        $output = [];

        foreach ($listingCurrencies AS $currency)
        {
            $currency = utf8_strtoupper(utf8_substr($currency, 0, 3));
            if (isset($currencyData[$currency]))
            {
                $output[$currency] = $currencyData[$currency];
            }
            else
            {
                $output[$currency] = [
                    'code' => $currency,
                    'symbol' => $currency,
                    'precision' => 2,
                    'phrase' => 'currency.n_a'
                ];
            }
        }

        if ($listing && $listing->currency)
        {
            $currency = utf8_strtoupper(utf8_substr($listing->currency, 0, 3));
            if (isset($currencyData[$currency]))
            {
                $output[$currency] = $currencyData[$currency];
            }
            else
            {
                $output[$currency] = [
                    'code' => $currency,
                    'symbol' => $currency,
                    'precision' => 2,
                    'phrase' => 'currency.n_a'
                ];
            }
        }

        return $output;
    }

    public function logListingView(\Z61\Classifieds\Entity\Listing $listing)
    {
        $this->db()->query("
			INSERT INTO xf_z61_classifieds_listing_view
				(listing_id, total)
			VALUES
				(? , 1)
			ON DUPLICATE KEY UPDATE
				total = total + 1
		", $listing->listing_id);
    }

    public function batchUpdateListingViews()
    {
        $db = $this->db();
        $db->query("
			UPDATE xf_z61_classifieds_listing AS listing
			INNER JOIN xf_z61_classifieds_listing_view AS v ON (listing.listing_id = v.listing_id)
			SET listing.view_count = listing.view_count + v.total
		");
        $db->emptyTable('xf_z61_classifieds_listing_view');
    }

    public function unfeatureListing(\Z61\Classifieds\Entity\Listing $listing)
    {
        if ($listing->Featured)
        {
            $listing->Featured->delete();
        }
    }

    public function markListingsReadByVisitor($categoryIds = null, $newRead = null)
    {
        $finder = $this->findListingsForOverviewList($categoryIds)
            ->unreadOnly();

        $listings = $finder->fetch();

        foreach ($listings AS $listing)
        {
            $this->markListingReadByVisitor($listing, $newRead);
        }
    }

    public function markListingReadByVisitor(\Z61\Classifieds\Entity\Listing $listing, $newRead = null)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if ($newRead === null)
        {
            $newRead = max(\XF::$time, $listing->listing_date);
        }

        $cutOff = $this->getReadMarkingCutOff();
        if ($newRead <= $cutOff)
        {
            return false;
        }

        $readDate = $listing->getVisitorReadDate();
        if ($newRead <= $readDate)
        {
            return false;
        }

        $this->db()->insert('xf_z61_classifieds_listing_read', [
            'listing_id' => $listing->listing_id,
            'user_id' => $visitor->user_id,
            'listing_read_date' => $newRead
        ], false, 'listing_read_date = VALUES(listing_read_date)');

        if ($newRead < $listing->listing_date)
        {
            return false;
        }

        if ($listing->Category && !$this->countUnreadListingsInCategory($listing->Category))
        {
            /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
            $categoryRepo = $this->repository('Z61\Classifieds:Category');
            $categoryRepo->markCategoryReadByVisitor($listing->Category);
        }

        return true;
    }

    public function getReadMarkingCutOff()
    {
        return \XF::$time - $this->options()->readMarkingDataLifetime * 86400;
    }

    public function countUnreadListingsInCategory(\Z61\Classifieds\Entity\Category $category)
    {
        $visitor = \XF::visitor();
        $userId = $visitor->user_id;
        if (!$userId)
        {
            return 0;
        }

        $read = $category->Read[$userId];
        $cutOff = $this->getReadMarkingCutOff();

        $readDate = $read ? max($read->category_read_date, $cutOff) : $cutOff;

        /** @var \Z61\Classifieds\Finder\Listing $finder */
        $finder = $this->finder('Z61\Classifieds:Listing');
        $finder
            ->where('category_id', $category->category_id)
            ->where('listing_date', '>', $readDate)
            ->where('listing_state', 'visible')
            ->whereOr(
                ["Read|{$userId}.listing_id", null],
                [$finder->expression('%s > %s', 'listing_date', "Read|{$userId}.listing_read_date")]
            )
            ->skipIgnored();

        return $finder->total();
    }

    public function getUserListingCount($userId)
    {
        return $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_z61_classifieds_listing
			WHERE user_id = ?
				AND listing_state = 'visible'
		", $userId);
    }
}