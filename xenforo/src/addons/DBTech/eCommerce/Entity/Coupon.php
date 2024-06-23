<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $coupon_id
 * @property string $coupon_state
 * @property string $coupon_code
 * @property string $coupon_type
 * @property float $coupon_percent
 * @property float $coupon_value
 * @property bool $discount_excluded
 * @property bool $allow_auto_discount
 * @property int $start_date
 * @property int $expiry_date
 * @property int $remaining_uses
 * @property int $minimum_products
 * @property int $maximum_products
 * @property float $minimum_cart_value
 * @property float $maximum_cart_value
 * @property array $product_discounts
 *
 * GETTERS
 * @property \XF\Phrase $title
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductCouponValue[] $Discounts
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\CouponLog[] $LogEntries
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\PermissionCacheContent[] $Permissions
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 */
class Coupon extends Entity
{
	/**
	 * @return string
	 */
	public function getTitlePhraseName(): string
	{
		return 'dbtech_ecommerce_coupon_title.' . $this->coupon_id;
	}

	/**
	 * @return \XF\Phrase
	 */
	public function getTitle(): \XF\Phrase
	{
		return \XF::phrase($this->getTitlePhraseName());
	}

	/**
	 * @return mixed|null|Entity
	 */
	public function getMasterTitlePhrase()
	{
		$phrase = $this->MasterTitle;
		if (!$phrase)
		{
			/** @var \XF\Entity\Phrase $phrase */
			$phrase = $this->_em->create('XF:Phrase');
			$phrase->title = $this->_getDeferredValue(function (): string
			{
				return $this->getTitlePhraseName();
			}, 'save');
			$phrase->language_id = 0;
			$phrase->addon_id = '';
		}

		return $phrase;
	}
	
	/**
	 * @param string $permission
	 * @return mixed
	 */
	public function hasPermission(string $permission): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor->hasOption('hasDbEcommerce') && $visitor->hasDbtechEcommerceCouponPermission($this->coupon_id, $permission);
	}
	
	/**
	 * @return bool
	 */
	public function isVisible(): bool
	{
		return ($this->coupon_state == 'visible');
	}
	
	/**
	 * @return bool
	 */
	public function canUse(): bool
	{
		return (
			\XF::options()->dbtechEcommerceCoupons['enabled']
			&& $this->isVisible()
			&& $this->hasPermission('useCoupons')
			&& (
				$this->remaining_uses == -1
				|| $this->remaining_uses >= 1
			)
			&& $this->start_date <= \XF::$time
			&& $this->expiry_date > \XF::$time
		);
	}
	
	/**
	 * @param Product $product
	 *
	 * @return bool
	 */
	public function isApplicable(Product $product): bool
	{
		if (!count($this->product_discounts))
		{
			return true;
		}
		
		foreach ($this->product_discounts as $discount)
		{
			if ($discount['product_id'] == $product->product_id)
			{
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * @param bool $forDisplay
	 * @param \XF\Language|null $language
	 *
	 * @return float
	 */
	public function getBaseDiscount(bool $forDisplay = true, ?\XF\Language $language = null)
	{
		if ($forDisplay)
		{
			if ($this->coupon_type == 'percent')
			{
				return $this->coupon_percent . '%';
			}
			
			/** @var \XF\Data\Currency $currencyData */
			$currencyData = $this->app()->data('XF:Currency');
			return $currencyData->languageFormat($this->coupon_value, $this->app()->options()->dbtechEcommerceCurrency, $language);
		}
		
		return $this->coupon_type == 'percent' ? $this->coupon_percent : $this->coupon_value;
	}
	
	/**
	 * @param Product $product
	 *
	 * @return float
	 */
	public function getApplicableDiscount(Product $product)
	{
		if (!count($this->product_discounts))
		{
			return $this->coupon_type == 'percent' ? $this->coupon_percent : $this->coupon_value;
		}
		
		foreach ($this->product_discounts as $discount)
		{
			if ($discount['product_id'] == $product->product_id)
			{
				return $discount['product_value'] != 0.00 ? $discount['product_value'] : $this->getBaseDiscount(false);
			}
		}
		
		return 0.00;
	}
	
	/**
	 * @param Product $product
	 * @param float $cost
	 *
	 * @return float
	 */
	public function getDiscountedCost(Product $product, float $cost): float
	{
		if (!$discount = $this->getApplicableDiscount($product))
		{
			return $cost;
		}
		
		switch ($this->coupon_type)
		{
			case 'percent':
				$cost *= (1 - ($discount / 100));
				break;
			
			case 'value':
				$cost -= $discount;
				break;
		}
		
		return max(0, $cost);
	}
	
	/**
	 * @param \XF\Language|null $language
	 *
	 * @return array
	 */
	public function getDiscountedProducts(?\XF\Language $language = null): array
	{
		/** @var \XF\Data\Currency $currencyData */
		$currencyData = $this->app()->data('XF:Currency');
		
		if (!count($this->product_discounts))
		{
			$phrase = $language ? $language->renderPhrase('dbtech_ecommerce_all_products') : \XF::phrase('dbtech_ecommerce_all_products');
			
			return [
				$phrase => $this->coupon_type == 'percent' ?
					($this->coupon_percent . '%') :
					$currencyData->languageFormat($this->coupon_value, $this->app()->options()->dbtechEcommerceCurrency, $language)
			];
		}
		
		$productDiscounts = [];
		foreach ($this->product_discounts as $key => $discountInfo)
		{
			$productDiscounts[$discountInfo['product_id']] = (
				$this->coupon_type == 'percent' ?
				(($discountInfo['product_value'] ?: $this->coupon_percent) . '%') :
				$currencyData->languageFormat(($discountInfo['product_value'] ?: $this->coupon_value), $this->app()->options()->dbtechEcommerceCurrency, $language)
			);
		}
		
		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = $this->repository('DBTech\eCommerce:Product');
		$productList = $productRepo->findProductsForList()->where('product_id', array_keys($productDiscounts))->fetch();
		
		$products = [];
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		foreach ($productList as $product)
		{
			$products[$product->title] = $productDiscounts[$product->product_id];
		}
		
		return $products;
	}
	
	/**
	 * @param string $reason
	 * @param \XF\Entity\User|null $byUser
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function softDelete(string $reason = '', ?\XF\Entity\User $byUser = null): bool
	{
		$byUser = $byUser ?: \XF::visitor();
		
		if ($this->coupon_state == 'deleted')
		{
			return false;
		}
		
		$this->coupon_state = 'deleted';
		
		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;
		
		$this->save();
		
		return true;
	}
	
	/**
	 * @param \XF\Entity\User|null $byUser
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function unDelete(?\XF\Entity\User $byUser = null): bool
	{
		$byUser = $byUser ?: \XF::visitor();
		
		if ($this->coupon_state == 'visible')
		{
			return false;
		}
		
		$this->coupon_state = 'visible';
		$this->save();
		
		return true;
	}
	
	/**
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('coupon_state', 'visible');
		$approvalChange = $this->isStateChanged('coupon_state', 'moderated');
		$deletionChange = $this->isStateChanged('coupon_state', 'deleted');
		
		if ($this->isUpdate())
		{
			if ($deletionChange == 'leave' && $this->DeletionLog)
			{
				$this->DeletionLog->delete();
			}
			
			if ($approvalChange == 'leave' && $this->ApprovalQueue)
			{
				$this->ApprovalQueue->delete();
			}
		}
		
		if ($approvalChange == 'enter')
		{
			/** @var \XF\Entity\ApprovalQueue $approvalQueue */
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->start_date;
			$approvalQueue->save();
		}
		elseif ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}
		
		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('dbtech_ecommerce_coupon', $this);
		}
	}

	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \XF\Db\Exception
	 */
	protected function _postDelete()
	{
		if ($this->coupon_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->coupon_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('dbtech_ecommerce_coupon', $this, 'delete_hard');
		}
		
		/** @var \XF\Entity\Phrase $titlePhrase */
		$titlePhrase = $this->MasterTitle;
		if ($titlePhrase)
		{
			$titlePhrase->delete();
		}
		
		$db = $this->db();
		$db->delete('xf_dbtech_ecommerce_coupon_log', 'coupon_id = ?', $this->coupon_id);
		$db->query('UPDATE xf_dbtech_ecommerce_order SET coupon_id = 0 WHERE coupon_id = ?', $this->coupon_id);
		$db->query('UPDATE xf_dbtech_ecommerce_order_item SET coupon_id = 0 WHERE coupon_id = ?', $this->coupon_id);
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_coupon';
		$structure->shortName = 'DBTech\eCommerce:Coupon';
		$structure->contentType = 'dbtech_ecommerce_coupon';
		$structure->primaryKey = 'coupon_id';
		$structure->columns = [
			'coupon_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'coupon_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'deleted']
			],
			'coupon_code' => ['type' => self::STR, 'maxLength' => 25],
			'coupon_type' => ['type' => self::STR, 'default' => 'percent',
				'allowedValues' => ['percent', 'value']
			],
			'coupon_percent' => ['type' => self::FLOAT, 'min' => 0, 'max' => 100, 'default' => 15],
			'coupon_value' => ['type' => self::FLOAT, 'min' => 0, 'default' => 15],
			'discount_excluded' => ['type' => self::BOOL, 'default' => false],
			'allow_auto_discount' => ['type' => self::BOOL, 'default' => true],
			'start_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'expiry_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'remaining_uses' => ['type' => self::INT, 'default' => -1],
			'minimum_products' => ['type' => self::UINT, 'default' => 0],
			'maximum_products' => ['type' => self::UINT, 'default' => 0],
			'minimum_cart_value' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0],
			'maximum_cart_value' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0],
			'product_discounts' => ['type' => self::JSON_ARRAY, 'default' => []]
		];
		$structure->behaviors = [
			'XF:PermissionRebuildable' => [
				'permissionContentType' => $structure->contentType
			]
		];
		$structure->getters = [
			'title' => true
		];
		$structure->relations = [
			'MasterTitle' => [
				'entity' => 'XF:Phrase',
				'type' => self::TO_ONE,
				'conditions' => [
					['language_id', '=', 0],
					['title', '=', 'dbtech_ecommerce_coupon_title.', '$coupon_id']
				],
				'cascadeDelete' => true
			],
			'Discounts' => [
				'entity' => 'DBTech\eCommerce:ProductCouponValue',
				'type' => self::TO_MANY,
				'conditions' => 'coupon_id',
				'primary' => true,
				'key' => 'product_id',
				'cascadeDelete' => true
			],
			'LogEntries' => [
				'entity' => 'DBTech\eCommerce:CouponLog',
				'type' => self::TO_MANY,
				'conditions' => 'coupon_id'
			],
			'Permissions' => [
				'entity' => 'XF:PermissionCacheContent',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_coupon'],
					['content_id', '=', '$coupon_id']
				],
				'key' => 'permission_combination_id',
				'proxy' => true
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_coupon'],
					['content_id', '=', '$coupon_id']
				],
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_coupon'],
					['content_id', '=', '$coupon_id']
				],
				'primary' => true
			]
		];
		$structure->options = [
			'log_moderator' => false
		];

		return $structure;
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Coupon|\XF\Mvc\Entity\Repository
	 */
	protected function getCouponRepo()
	{
		return $this->repository('DBTech\eCommerce:Coupon');
	}
}