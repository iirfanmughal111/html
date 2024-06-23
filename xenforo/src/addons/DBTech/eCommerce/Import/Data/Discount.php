<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;
use XF\Import\Data\HasDeletionLogTrait;

/**
 * Class Discount
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Discount extends AbstractEmulatedData
{
	use HasDeletionLogTrait;
	
	/**
	 * @var null
	 */
	protected $title = null;
	
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'discount';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Discount';
	}
	
	/**
	 * @param $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * @param $oldId
	 *
	 * @return null|void
	 */
	protected function preSave($oldId)
	{
		if ($this->title === null)
		{
			throw new \LogicException("Must call setTitle with a non-null value to save a discount");
		}
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		$this->insertStateRecord($this->discount_state, \XF::$time);
		
		$this->insertMasterPhrase('dbtech_ecommerce_discount_title.' . $newId, $this->title ?: '');
	}
}