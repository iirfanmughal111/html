<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\PrintableException;
use XF\Util\Arr;

class Series extends Repository
{
	public function findSeriesForSeriesList(array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => true // False if you don't want authors to see their moderated series
		], $limits);
		
		/** @var \XenAddons\Showcase\Finder\SeriesItem $seriesFinder */
		$seriesFinder = $this->finder('XenAddons\Showcase:SeriesItem');

		$seriesFinder
			->with('full')
			->useDefaultOrder();

		if ($limits['visibility'])
		{
			$seriesFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
		}
		
		return $seriesFinder;
	}

	public function findFeaturedSeries()
	{
		/** @var \XenAddons\Showcase\Finder\SeriesItem $seriesFinder */
		$seriesFinder = $this->finder('XenAddons\Showcase:SeriesItem');

		$seriesFinder
			->with('Featured', true)
			->where('series_state', 'visible')
			->with('full')
			->setDefaultOrder($seriesFinder->expression('RAND()'));

		return $seriesFinder;
	}

	public function findSeriesForWatchedList($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		$userId = intval($userId);

		/** @var \XenAddons\Showcase\Finder\SeriesItem $seriesFinder */
		$seriesFinder = $this->finder('XenAddons\Showcase:SeriesItem');

		$seriesFinder
			->with('full')
			->with('Watch|' . $userId, true)
			->where('series_state', 'visible')
			->setDefaultOrder('last_part_date', 'desc');

		return $seriesFinder;
	}
	
	// currently only used by the add item to series function!
	public function findSeriesForSelectList($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		$userId = intval($userId);
	
		/** @var \XenAddons\Showcase\Finder\SeriesItem $seriesFinder */
		$seriesFinder = $this->finder('XenAddons\Showcase:SeriesItem');
	
		$seriesFinder
			->where('series_state', 'visible')
			->where('user_id', $userId)
			->setDefaultOrder('last_part_date', 'desc');
	
		return $seriesFinder;
	}
	
	// currently only used by the add item to series function!
	public function findCommunitySeriesForSelectList()
	{
		/** @var \XenAddons\Showcase\Finder\SeriesItem $seriesFinder */
		$seriesFinder = $this->finder('XenAddons\Showcase:SeriesItem');
	
		$seriesFinder
			->where('series_state', 'visible')
			->where('community_series', '=', 1)
			->setDefaultOrder('last_part_date', 'desc');
	
		return $seriesFinder;
	}	
	
	public function findCommunityContributors(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		$userIds = $this->db()->fetchAllColumn("
			SELECT DISTINCT user_id
			FROM xf_xa_sc_series_part
			WHERE series_id = ?
		", $series->series_id);
	
		/** @var \XF\Finder\User $userFinder */
		$userFinder = $this->finder('XF:User');
	
		$userFinder
			->where('user_id', $userIds);
	
		return $userFinder;
	}
	
	public function getSeriesAttachmentConstraints()
	{
		$options = $this->options();
	
		return [
			'extensions' => Arr::stringToArray($options->xaScAllowedFileExtensions),  // TODO xaScSeriesAllowedFileExtensions
			'size' => $options->xaScItemAttachmentMaxFileSize * 1024,  // TODO xaScSeriesAttachmentMaxFileSize
			'width' => $options->attachmentMaxDimensions['width'],
			'height' => $options->attachmentMaxDimensions['height']
		];
	}
	
	public function logSeriesView(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		$this->db()->query("
			-- XFDB=noForceAllWrite	
			INSERT INTO xf_xa_sc_series_view
				(series_id, total)
			VALUES
				(? , 1)
			ON DUPLICATE KEY UPDATE
				total = total + 1
		", $series->series_id);
	}
	
	public function batchUpdateSeriesViews()
	{
		$db = $this->db();
		$db->query("
			UPDATE xf_xa_sc_series AS series
			INNER JOIN xf_xa_sc_series_view AS series_view ON (series.series_id = series_view.series_id)
			SET series.view_count = series.view_count + series_view.total
		");
		$db->emptyTable('xf_xa_sc_series_view');
	}
	
	public function sendModeratorActionAlert(
		\XenAddons\Showcase\Entity\SeriesItem $series, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null
	)
	{
		if (!$forceUser)
		{
			if (!$series->user_id || !$series->User)
			{
				return false;
			}

			$forceUser = $series->User;
		}

		$extra = array_merge([
			'title' => $series->title,
			'link' => $this->app()->router('public')->buildLink('nopath:showcase/series', $series),
			'reason' => $reason
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_series_{$action}", $extra,
			['dependsOnAddOnId' => 'XenAddons/Showcase']
		);

		return true;
	}
	
	/**
	 * @param $url
	 * @param null $type
	 * @param null $error
	 *
	 * @return null|\XF\Mvc\Entity\Entity
	 */
	public function getSeriesFromUrl($url, $type = null, &$error = null)
	{
		$routePath = $this->app()->request()->getRoutePathFromUrl($url);
		$routeMatch = $this->app()->router($type)->routeToController($routePath);
		$params = $routeMatch->getParameterBag();
	
		if (!$params->series_id)
		{
			$error = \XF::phrase('xa_sc_no_series_id_could_be_found_from_that_url');
			return null;
		}
	
		$series = $this->app()->find('XenAddons\Showcase:SeriesItem', $params->series_id);
		if (!$series)
		{
			$error = \XF::phrase('xa_sc_no_series_could_be_found_with_id_x', ['series_id' => $params->series_id]);
			return null;
		}
	
		return $series;
	}

	public function getUserSeriesCount($userId)
	{
		return $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_series
			WHERE user_id = ?
		", $userId);
	}
}