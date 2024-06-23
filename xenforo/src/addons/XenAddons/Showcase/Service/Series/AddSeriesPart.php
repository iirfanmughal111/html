<?php

namespace XenAddons\Showcase\Service\Series;

use XenAddons\Showcase\Entity\SeriesItem;

class AddSeriesPart extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\SeriesItem
	 */
	protected $series;
	
	/**
	 * @var \XenAddons\Showcase\Entity\SeriesPart
	 */
	protected $seriesPart;
	
	/**
	 * @var \XenAddons\Showcase\Service\SeriesPart\Preparer
	 */
	protected $seriePartPreparer;

	public function __construct(\XF\App $app, SeriesItem $series)
	{
		parent::__construct($app);

		$this->series = $series;
		
		$this->seriesPart = $this->setUpSeriesPart();
	}

	protected function setUpSeriesPart()
	{
		$series = $this->series;
		
		$seriesPart = $this->em()->create('XenAddons\Showcase:SeriesPart');
		$seriesPart->series_id = $series->series_id;
		$seriesPart->user_id = \XF::visitor()->user_id;
		
		$this->seriesPart = $seriesPart;
		
		$this->seriesPartPreparer = $this->service('XenAddons\Showcase:SeriesPart\Preparer', $this->seriesPart);		
		
		return $seriesPart;
	}

	public function getSeries()
	{
		return $this->series;
	}
	
	public function getSeriesPart()
	{
		return $this->seriesPart;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}
	
	public function setItemId($itemId)
	{
		$this->seriesPart->item_id = $itemId;
	}
	
	public function checkForSpam()
	{
		$this->seriesPartPreparer->checkForSpam();
	}
	
	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$visitor = \XF::visitor();
		
		$seriesPart = $this->seriesPart;
		$series = $this->seriesPart->Series;
		$item = $this->seriesPart->Item;
		
		$seriesPart->preSave();
		$errors = $seriesPart->getErrors();

		if (!$series)
		{
			$errors['series_404'] = \XF::phrase('xa_sc_requested_series_not_found');
			return $errors;
		}
		
		if (!$item)
		{
			$errors['item_404'] = \XF::phrase('xa_sc_requested_item_not_found');
			return $errors;
		}
		
		if ($item->isInSeries())
		{
			$errors['item_in_series'] = \XF::phrase('xa_sc_requested_item_aleady_associated_with_series');
			return $errors;			
		}
		
		if (!$item->isVisible())
		{
			$errors['item_not_visible'] = \XF::phrase('xa_sc_only_visible_state_items_can_be_added_to_a_series', ['state' => $item->item_state]);
			return $errors;	
		}
		
		if (
			$visitor->user_id == $seriesPart->Series->user_id
			&& $visitor->user_id == $seriesPart->Item->user_id 
		)
		{
			// do nothing as a series owner can add their own items to a series
		}
		else 
		{
			// check to see if the viewing user has the moderator permission to add any item to any series. 
			if ($seriesPart->Series->canAddItemToAnySeries())
			{
				// do nothing as Viewing User has permission to add any item to any series
			}
			else 
			{
				// Check if is a community series and if the viewing user is the item author. 
				if (
					$seriesPart->Series->isCommunitySeries()
					&& $seriesPart->Item->user_id == $visitor->user_id
				)
				{
					// do nothing as the Series is a Community Series, the Viewing User is the Item Author and the Viewing User has permission to add their own items to any community series 
				}
				else 
				{	// If it gets to this point, we need to throw an error as The Viewing User is not the Author of the Item and does not have permission to add any item to any series
					$errors['author_no_match'] = \XF::phrase('xa_sc_you_do_not_have_permission_to_add_this_item_to_this_series');					
				}
			}	
		}

		return $errors;
	}

	protected function _save()
	{
		$seriesPart = $this->seriesPart;
		$visitor = \XF::visitor();
		
		$seriesPart->save(true, false);
		
		$this->seriesPartPreparer->afterUpdate();

		return $seriesPart;
	}
	
	public function sendNotifications()
	{
		if ($this->seriesPart->Item->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\SeriesPart\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:SeriesPart\Notifier', $this->seriesPart);
			$notifier->notifyAndEnqueue(3);
		}
	}	
}