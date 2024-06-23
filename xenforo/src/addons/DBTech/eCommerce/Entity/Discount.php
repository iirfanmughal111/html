<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $discount_id
 * @property string $discount_state
 * @property float $discount_threshold
 * @property float $discount_percent
 *
 * GETTERS
 * @property \XF\Phrase $title
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 */
class Discount extends Entity
{
	/**
	 * @return bool
	 */
	public function canView(): bool
	{
		return (
			$this->isVisible()
//			&& $this->hasPermission('useCoupons')
		);
	}
	
	/**
	 * @return string
	 */
	public function getTitlePhraseName(): string
	{
		return 'dbtech_ecommerce_discount_title.' . $this->discount_id;
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
		return $visitor->hasOption('hasDbEcommerce') && $visitor->hasDbtechEcommerceDiscountPermission($this->discount_id, $permission);
	}

	/**
	 * @return bool
	 */
	public function isVisible(): bool
	{
		return ($this->discount_state == 'visible');
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

		if ($this->discount_state == 'deleted')
		{
			return false;
		}

		$this->discount_state = 'deleted';

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

		if ($this->discount_state == 'visible')
		{
			return false;
		}

		$this->discount_state = 'visible';
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
		$visibilityChange = $this->isStateChanged('discount_state', 'visible');
		$approvalChange = $this->isStateChanged('discount_state', 'moderated');
		$deletionChange = $this->isStateChanged('discount_state', 'deleted');

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
			$approvalQueue->content_date = \XF::$time;
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
			$this->app()->logger()->logModeratorChanges('dbtech_ecommerce_discount', $this);
		}
	}
	
	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function _postDelete()
	{
		if ($this->discount_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}

		if ($this->discount_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('dbtech_ecommerce_discount', $this, 'delete_hard');
		}

		/** @var \XF\Entity\Phrase $titlePhrase */
		$titlePhrase = $this->MasterTitle;
		if ($titlePhrase)
		{
			$titlePhrase->delete();
		}
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_discount';
		$structure->shortName = 'DBTech\eCommerce:Discount';
		$structure->contentType = 'dbtech_ecommerce_discount';
		$structure->primaryKey = 'discount_id';
		$structure->columns = [
			'discount_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'discount_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'deleted']
			],
			'discount_threshold' => ['type' => self::FLOAT, 'required' => true],
			'discount_percent' => ['type' => self::FLOAT, 'required' => true, 'min' => 0, 'max' => 100],
		];
		$structure->behaviors = [
			/*
			'XF:PermissionRebuildable' => [
				'permissionContentType' => $structure->contentType
			]
			*/
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
					['title', '=', 'dbtech_ecommerce_discount_title.', '$discount_id']
				],
				'cascadeDelete' => true
			],
			/*
			'Permissions' => [
				'entity' => 'XF:PermissionCacheContent',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_discount'],
					['content_id', '=', '$discount_id']
				],
				'key' => 'permission_combination_id',
				'proxy' => true
			],
			*/
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_discount'],
					['content_id', '=', '$discount_id']
				],
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_discount'],
					['content_id', '=', '$discount_id']
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
	 * @return \DBTech\eCommerce\Repository\Discount|\XF\Mvc\Entity\Repository
	 */
	protected function getDiscountRepo()
	{
		return $this->repository('DBTech\eCommerce:Discount');
	}
}