<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $sale_id
 * @property string $title
 * @property string $sale_state
 * @property bool $email_notify
 * @property string $sale_type
 * @property float $sale_percent
 * @property float $sale_value
 * @property bool $discount_excluded
 * @property bool $allow_auto_discount
 * @property bool $feature_products
 * @property bool $is_recurring
 * @property int $recurring_length_amount
 * @property string $recurring_length_unit
 * @property int $start_date
 * @property int $end_date
 * @property array $other_dates
 * @property array $product_discounts
 * @property int $thread_node_id
 * @property int $thread_prefix_id
 * @property int $discussion_thread_id
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterDescription
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\Forum $ThreadForum
 * @property \XF\Entity\Thread $Discussion
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 */
class Sale extends Entity
{
	/**
	 * @return bool
	 */
	public function canSendModeratorActionAlert(): bool
	{
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function isVisible(): bool
	{
		return $this->sale_state == 'visible';
	}
	
	/**
	 * @return string
	 */
	public function getDescriptionPhraseName(): string
	{
		return 'dbtech_ecommerce_sale_description.' . $this->sale_id;
	}
	
	/**
	 * @return \XF\Phrase
	 */
	public function getDescription(): \XF\Phrase
	{
		return \XF::phrase($this->getDescriptionPhraseName());
	}
	
	/**
	 * @return mixed|null
	 */
	public function getMasterDescriptionPhrase(): ?\XF\Entity\Phrase
	{
		$phrase = $this->MasterDescription;
		if (!$phrase)
		{
			/** @var \XF\Entity\Phrase $phrase */
			$phrase = $this->_em->create('XF:Phrase');
			$phrase->title = $this->_getDeferredValue(function (): string
			{
				return $this->getDescriptionPhraseName();
			}, 'save');
			$phrase->language_id = 0;
			$phrase->addon_id = '';
		}
		
		return $phrase;
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
	 * @param Product $product
	 *
	 * @return int|float
	 */
	public function getApplicableDiscount(Product $product)
	{
		if (!count($this->product_discounts))
		{
			return floatval($this->sale_type == 'percent' ? $this->sale_percent : $this->sale_value);
		}
		
		foreach ($this->product_discounts as $discount)
		{
			if ($discount['product_id'] == $product->product_id)
			{
				return floatval($discount['product_value'] ?: ($this->sale_type == 'percent' ? $this->sale_percent : $this->sale_value));
			}
		}
		
		return 0;
	}
	
	/**
	 * @param Product $product
	 * @param float $cost
	 *
	 * @return float|int|mixed|string
	 */
	public function getDiscountedCost(Product $product, $cost)
	{
		if (!$discount = $this->getApplicableDiscount($product))
		{
			return $cost;
		}
		
		switch ($this->sale_type)
		{
			case 'percent':
				$cost *= (1 - ($discount / 100));
				break;
			
			case 'value':
				$cost = max(0, $cost - $discount);
				break;
		}
		
		return $cost;
	}
	
	/**
	 * @param \XF\Language|null $language
	 *
	 * @return array
	 */
	public function getDiscountedProducts(\XF\Language $language = null): array
	{
		/** @var \XF\Data\Currency $currencyData */
		$currencyData = $this->app()->data('XF:Currency');
		
		if (!count($this->product_discounts))
		{
			$phrase = $language ? $language->renderPhrase('dbtech_ecommerce_all_products') : \XF::phrase('dbtech_ecommerce_all_products');
			
			return [
				$phrase => $this->sale_type == 'percent' ?
					(floatval($this->sale_percent) . '%') :
					$currencyData->languageFormat($this->sale_value, $this->app()->options()->dbtechEcommerceCurrency, $language)
			];
		}
		
		$productDiscounts = [];
		foreach ($this->product_discounts as $key => $discountInfo)
		{
			$productDiscounts[$discountInfo['product_id']] = (
				$this->sale_type == 'percent' ?
				(floatval($discountInfo['product_value'] ?: $this->sale_percent) . '%') :
				$currencyData->languageFormat(($discountInfo['product_value'] ?: $this->sale_value), $this->app()->options()->dbtechEcommerceCurrency, $language)
			);
		}
		
		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = $this->repository('DBTech\eCommerce:Product');
		$productList = $productRepo->findProductsForList()->where('product_id', array_keys($productDiscounts))->fetch();
		
		$products = [];
		
		/** @var Product $product */
		foreach ($productList as $product)
		{
			$products[$product->title] = $productDiscounts[$product->product_id];
		}
		
		return $products;
	}
	
	/**
	 * @return mixed|null|string|string[]
	 */
	public function getExpectedThreadTitle()
	{
		$template = '';
		$options = $this->app()->options();
		
		if ($this->sale_state != 'visible' && $options->dbtechEcommerceContentDeleteThreadAction['update_title'])
		{
			$template = $options->dbtechEcommerceContentDeleteThreadAction['title_template'];
		}
		
		if (!$template)
		{
			$template = $options->dbtechEcommerceSaleThreadTitle;
		}
		
		$threadTitle = strtr($template, [
			'{title}' => $this->title,
		]);
		return $this->app()->stringFormatter()->wholeWordTrim($threadTitle, 100);
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
		
		if ($this->sale_state == 'deleted')
		{
			return false;
		}
		
		$this->sale_state = 'deleted';
		
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
	public function unDelete(\XF\Entity\User $byUser = null): bool
	{
		$byUser = $byUser ?: \XF::visitor();
		
		if ($this->sale_state == 'visible')
		{
			return false;
		}
		
		$this->sale_state = 'visible';
		$this->save();
		
		return true;
	}
	
	/**
	 *
	 */
	public function startJob()
	{
		if (!\XF::options()->dbtechEcommerceSales['enabled'])
		{
			return;
		}
		
		$criteria = [
			'no_empty_email' => true,
			'user_state' => 'valid',
			'is_banned' => 0,
			'Option'	=> [
				'dbtech_ecommerce_email_on_sale' => true
			]
		];
		
		$searcher = $this->app()->searcher('XF:User', $criteria);
		
		$total = $searcher->getFinder()->total();
		if ($total)
		{
			$this->app()->jobManager()->enqueueLater(
				'dbtEcomSaleNow' . $this->sale_id,
				$this->start_date,
				'DBTech\eCommerce:SaleEmail',
				[
					'criteria'  => $criteria,
					'sale_id'   => $this->sale_id,
					'sale_type' => 'now'
				]
			);
		}
	}
	
	/**
	 * @param bool $cancelRebuild
	 */
	public function cancelJobs(bool $cancelRebuild = false)
	{
		$this->app()->jobManager()->cancelUniqueJob('dbtEcomSaleFuture' . $this->sale_id);
		$this->app()->jobManager()->cancelUniqueJob('dbtEcomSaleNow' . $this->sale_id);
		
		if ($cancelRebuild)
		{
			// Only cancel this on delete
			$this->app()->jobManager()->cancelUniqueJob('dbtEcomSaleRebuildStart' . $this->sale_id);
			$this->app()->jobManager()->cancelUniqueJob('dbtEcomSaleRebuildEnd' . $this->sale_id);
		}
	}
	
	/**
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('sale_state', 'visible');
		$approvalChange = $this->isStateChanged('sale_state', 'moderated');
		$deletionChange = $this->isStateChanged('sale_state', 'deleted');
		
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
			
			if ($visibilityChange == 'leave')
			{
				$this->cancelJobs();
			}
			
			if ($this->isChanged('email_notify'))
			{
				if ($this->sale_state == 'visible' && $this->email_notify && $this->end_date > \XF::$time)
				{
					$this->startJob();
				}
				else
				{
					$this->cancelJobs();
				}
			}
			
			if (
				$this->sale_state == 'visible'
				&& $this->email_notify
				&& $this->end_date > \XF::$time
				&& $this->isChanged('start_date')
			) {
				$this->startJob();
			}

			if ($this->isChanged('discussion_thread_id'))
			{
				if ($this->getExistingValue('discussion_thread_id'))
				{
					/** @var \XF\Entity\Thread $oldDiscussion */
					$oldDiscussion = $this->getExistingRelation('Discussion');
					if ($oldDiscussion && $oldDiscussion->discussion_type == 'dbtech_ecommerce_sale')
					{
						// this will set it back to the forum default type
						$oldDiscussion->discussion_type = '';
						$oldDiscussion->save(false, false);
					}
				}

				if (
					$this->discussion_thread_id
					&& $this->Discussion
					&& $this->Discussion->discussion_type === \XF\ThreadType\AbstractHandler::BASIC_THREAD_TYPE
				) {
					$this->Discussion->discussion_type = 'dbtech_ecommerce_sale';
					$this->Discussion->save(false, false);
				}
			}
		}
		
		if ($this->discussion_thread_id)
		{
			$newThreadTitle = $this->getExpectedThreadTitle();
			if (
				$this->Discussion
				&& $this->Discussion->discussion_type == 'dbtech_ecommerce_sale'
				&& $newThreadTitle != $this->Discussion->title
			) {
				$this->Discussion->title = $newThreadTitle;
				$this->Discussion->saveIfChanged($saved, false, false);
			}
		}
		
		if ($visibilityChange == 'enter' && $this->email_notify && $this->end_date > \XF::$time)
		{
			$this->startJob();
		}
		elseif ($approvalChange == 'enter')
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
			$this->app()->logger()->logModeratorChanges('dbtech_ecommerce_sale', $this);
		}
		
		if ($this->end_date > \XF::$time)
		{
			$this->app()->jobManager()->enqueueLater(
				'dbtEcomSaleRebuildEnd' . $this->sale_id,
				$this->end_date,
				'DBTech\eCommerce:SaleRebuild'
			);
		}
		else
		{
			// Just to be safe
			$this->app()->jobManager()->cancelUniqueJob('dbtEcomSaleRebuildEnd' . $this->sale_id);
		}
		
		if ($this->start_date > \XF::$time)
		{
			$this->app()->jobManager()->enqueueLater(
				'dbtEcomSaleRebuildStart' . $this->sale_id,
				$this->start_date,
				'DBTech\eCommerce:SaleRebuild'
			);
		}
		else
		{
			// Just to be safe
			$this->app()->jobManager()->cancelUniqueJob('dbtEcomSaleRebuildStart' . $this->sale_id);
		}
	}
	
	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _postDelete()
	{
		if ($this->sale_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->sale_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('dbtech_ecommerce_sale', $this, 'delete_hard');
		}
		
		$this->cancelJobs(true);
	}
		
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_sale';
		$structure->shortName = 'DBTech\eCommerce:Sale';
		$structure->primaryKey = 'sale_id';
		$structure->columns = [
			'sale_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'title' => ['type' => self::STR, 'maxLength' => 100,
				'required' => 'please_enter_valid_title'
			],
			'sale_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted']
			],
			'email_notify' => ['type' => self::BOOL, 'default' => true],
			'sale_type' => ['type' => self::STR, 'default' => 'percent',
				'allowedValues' => ['percent', 'value']
			],
			'sale_percent' => ['type' => self::FLOAT, 'min' => 0, 'max' => 100, 'default' => 0.00],
			'sale_value' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'discount_excluded' => ['type' => self::BOOL, 'default' => false],
			'allow_auto_discount' => ['type' => self::BOOL, 'default' => true],
			'feature_products' => ['type' => self::BOOL, 'default' => false],
			'is_recurring' => ['type' => self::BOOL, 'default' => false],
			'recurring_length_amount' => ['type' => self::UINT, 'max' => 255, 'default' => 0],
			'recurring_length_unit' => ['type' => self::STR, 'default' => '',
									  'allowedValues' => ['day', 'month', 'year', '']
			],
			'start_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'end_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'other_dates' => ['type' => self::JSON_ARRAY, 'default' => []],
			'product_discounts' => ['type' => self::JSON_ARRAY, 'default' => []],
			'thread_node_id' => ['type' => self::UINT, 'default' => 0],
			'thread_prefix_id' => ['type' => self::UINT, 'default' => 0],
			'discussion_thread_id' => ['type' => self::UINT, 'default' => 0]
		];
		$structure->behaviors = [];
		$structure->getters = [
//			'title' => true
		];
		$structure->relations = [
			/*
			'MasterTitle' => [
				'entity' => 'XF:Phrase',
				'type' => self::TO_ONE,
				'conditions' => [
					['language_id', '=', 0],
					['title', '=', 'dbtech_ecommerce_sale_title.', '$sale_id']
				],
				'cascadeDelete' => true
			],
			*/
			'MasterDescription' => [
				'entity' => 'XF:Phrase',
				'type' => self::TO_ONE,
				'conditions' => [
					['language_id', '=', 0],
					['title', '=', 'dbtech_ecommerce_sale_description.', '$sale_id']
				],
				'cascadeDelete' => true
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_sale'],
					['content_id', '=', '$sale_id']
				],
				'primary' => true
			],
			'ThreadForum' => [
				'entity' => 'XF:Forum',
				'type' => self::TO_ONE,
				'conditions' => [
					['node_id', '=', '$thread_node_id']
				],
				'primary' => true,
				'with' => 'Node'
			],
			'Discussion' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => [
					['thread_id', '=', '$discussion_thread_id']
				],
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_sale'],
					['content_id', '=', '$sale_id']
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
	 * @return \DBTech\eCommerce\Repository\Sale|\XF\Mvc\Entity\Repository
	 */
	protected function getSaleRepo()
	{
		return $this->repository('DBTech\eCommerce:Sale');
	}
}