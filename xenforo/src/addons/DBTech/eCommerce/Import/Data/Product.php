<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;
use XF\Import\Data\HasDeletionLogTrait;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Product extends AbstractEmulatedData
{
	use HasDeletionLogTrait;
	
	/**
	 * @var null
	 */
	protected $tagline = null;
	
	/**
	 * @var null
	 */
	protected $description = null;
	
	/**
	 * @var array
	 */
	protected $versions = [];
	
	
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'product';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Product';
	}
	
	/**
	 * @param $tagline
	 */
	public function setTagline($tagline)
	{
		$this->tagline = $tagline;
	}
	
	/**
	 * @param $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	/**
	 * @param $choice
	 * @param $text
	 */
	public function addVersion($choice, $text)
	{
		$this->versions[$choice] = $text;
	}
	
	/**
	 * @param $oldId
	 *
	 * @return null|void
	 */
	protected function preSave($oldId)
	{
		$this->forceNotEmpty('title', $oldId);
		
		if ($this->tagline === null)
		{
			throw new \LogicException("Must call setTagline with a non-null value to save a product");
		}
		
		if ($this->description === null)
		{
			throw new \LogicException("Must call setDescription with a non-null value to save a product");
		}
		
		if ($this->versions)
		{
			$this->product_versions = $this->versions;
		}
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		$this->insertStateRecord($this->product_state, $this->creation_date);
		
		$this->insertMasterPhrase('dbtech_ecommerce_product_tag.' . $newId, $this->tagline ?: '');
		$this->insertMasterPhrase('dbtech_ecommerce_product_desc.' . $newId, $this->description ?: '');
		
		if ($this->product_versions)
		{
			foreach ($this->product_versions as $key => $version)
			{
				$this->insertMasterPhrase('dbtech_ecommerce_product_version.' . $newId . '_' . $key, $version ?: '');
			}
		}
	}
}