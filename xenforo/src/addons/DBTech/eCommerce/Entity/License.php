<?php

namespace DBTech\eCommerce\Entity;

use XF\CustomField\Set;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $license_id
 * @property int $parent_license_id
 * @property int $product_id
 * @property int $user_id
 * @property string $username
 * @property int $purchase_date
 * @property int $order_id
 * @property string|null $purchase_request_key
 * @property int $expiry_date
 * @property bool $sent_expiring_reminder
 * @property bool $sent_expired_reminder
 * @property int $latest_download_id
 * @property string $license_key
 * @property string $license_state
 * @property array $license_fields_
 * @property int $discussion_thread_id
 * @property array $required_user_group_ids
 *
 * GETTERS
 * @property string $title
 * @property string $full_title
 * @property Set $license_fields
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\License $Parent
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\License[] $Children
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\Order $Order
 * @property \XF\Entity\User $User
 * @property \DBTech\eCommerce\Entity\SerialKey $SerialKey
 * @property \XF\Entity\Thread $Discussion
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\LicenseFieldValue[] $LicenseFields
 * @property \DBTech\eCommerce\Entity\Download $LatestDownloaded
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\DownloadLog[] $DownloadLog
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 */
class License extends Entity
{
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->Product->title;
	}
	
	/**
	 * @return string
	 */
	public function getFullTitle(): string
	{
		if (!$this->Product->Parent)
		{
			return $this->getTitle();
		}
		
		$template = \XF::options()->dbtechEcommerceAddonProductTitle;
		return str_replace(['{title}', '{parent}'], [$this->getTitle(), $this->Product->Parent->title], $template);
	}
	
	/**
	 * @return bool
	 */
	public function isLifetime(): bool
	{
		return $this->expiry_date == 0;
	}
	
	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->isLifetime() || $this->expiry_date >= \XF::$time;
	}
	
	/**
	 * @return bool
	 */
	public function isExpired(): bool
	{
		return !$this->isActive();
	}
	
	/**
	 * @return bool
	 */
	public function isIgnored(): bool
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}

	/**
	 * @return bool
	 */
	public function isVisible(): bool
	{
		return ($this->license_state == 'visible');
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function isValid(&$error = null): bool
	{
		return ($this->canView($error)
			&& $this->isVisible()
			&& $this->hasRequiredUserGroups($error)
		);
	}
	
	/**
	 * @return bool
	 */
	public function isAssigned(): bool
	{
		return !$this->Product->isAddOn() || $this->parent_license_id;
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canView(&$error = null): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$this->Product->hasPermission('view'))
		{
			return false;
		}
		
		if ($this->license_state == 'moderated' || $this->Product->product_state == 'moderated')
		{
			if (
				(!$visitor->user_id || $visitor->user_id != $this->user_id)
				&& !$this->Product->hasPermission('viewModerated')
			) {
				return false;
			}
		}
		elseif ($this->license_state == 'deleted' || $this->Product->product_state == 'deleted')
		{
			if (!$this->Product->hasPermission('viewDeleted'))
			{
				return false;
			}
		}
		
		if ($this->user_id != $visitor->user_id && !$visitor->canViewDbtechEcommerceLicenses($error))
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canEdit(&$error = null): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		return $this->isValid($error) && (
			$visitor->user_id == $this->user_id ||
			$visitor->canEditAnyDbtechEcommerceLicenses($error)
		);
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function canRenew(&$error = null): bool
	{
		if ($this->isLifetime())
		{
			return false;
		}
		
		return $this->Product->canPurchase($this, $error);
	}
	
	/**
	 * @return bool
	 */
	public function canSendModeratorActionAlert(): bool
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id
			&& $visitor->user_id != $this->user_id
			&& $this->license_state == 'visible'
		);
	}

	/**
	 * @param $error
	 *
	 * @return bool
	 */
	public function hasRequiredUserGroups(&$error = null): bool
	{
		$visitor = \XF::visitor();

		if (empty($this->required_user_group_ids))
		{
			return true;
		}

		foreach ($this->required_user_group_ids AS $userGroupId)
		{
			if ($userGroupId == -1)
			{
				continue;
			}

			if (!$visitor->isMemberOf($userGroupId))
			{
				$error = \XF::phraseDeferred('dbtech_ecommerce_access_to_this_license_expired');
				return false;
			}
		}

		return true;
	}

	/**
	 * @param Product|null $product
	 * @param int $length
	 * @return string
	 */
	public function generateLicenseKey(?Product $product = null, int $length = 10): string
	{
		return ($product ? $product->license_prefix : $this->Product->license_prefix) .
			$this->user_id .
			\strtoupper(\preg_replace('/[^A-Za-z0-9]/', 'A', \XF::generateRandomString(10)));
	}

	/**
	 * @return bool
	 * @throws \XF\PrintableException
	 */
	public function generateSerialKey(): bool
	{
		$product = $this->Product;

		if (!empty($product->product_type_data['serial_key_formula']))
		{
			// This is a set formula for serial keys
			do
			{
				$serialKey = \preg_replace_callback_array(
					[
						'/\{d\}/i' => function ($match)
						{
							return \mt_rand(0, 9);
						},
						'/\{w\}/' => function ($match)
						{
							return \chr(mt_rand(97, 122));
						},
						'/\{W\}/' => function ($match)
						{
							return \chr(mt_rand(65, 90));
						},
					],
					$product->product_type_data['serial_key_formula']
				);

				/** @var \DBTech\eCommerce\Entity\SerialKey $serialKeyEntity */
				$serialKeyEntity = $this->_em->create('DBTech\eCommerce:SerialKey');
				$serialKeyEntity->product_id = $product->product_id;
				$serialKeyEntity->license_id = $this->license_id;
				$serialKeyEntity->user_id = $this->user_id;
				$serialKeyEntity->serial_key = $serialKey;
				$serialKeyEntity->serial_date = \XF::$time;

				if (!$serialKeyEntity->preSave())
				{
					continue;
				}

				$serialKeyEntity->save(true, false);

				break;
			}
			while (true);
		}
		else
		{
			// This is a list of keys
			if (empty($product->product_type_data['serial_key_list']))
			{
				return false;
			}

			$data = $product->product_type_data;

			$keys = \preg_split('#\r?\n#', $data['serial_key_list'], -1, PREG_SPLIT_NO_EMPTY);
			$serialKey = \array_shift($keys);

			$data['serial_key_list'] = \implode("\n", $keys);

			/** @var \DBTech\eCommerce\Entity\SerialKey $serialKeyEntity */
			$serialKeyEntity = $this->_em->create('DBTech\eCommerce:SerialKey');
			$serialKeyEntity->product_id = $product->product_id;
			$serialKeyEntity->license_id = $this->license_id;
			$serialKeyEntity->user_id = $this->user_id;
			$serialKeyEntity->serial_key = $serialKey;
			$serialKeyEntity->serial_date = \XF::$time;

			if (!$serialKeyEntity->preSave())
			{
				return false;
			}

			$serialKeyEntity->save(true, false);

			$product->fastUpdate('product_type_data', $data);
		}

		return true;
	}
	
	/**
	 * @return Set
	 */
	public function getLicenseFields(): Set
	{
		$fieldDefinitions = $this->app()->container('customFields.dbtechEcommerceLicenses');
		
		return new Set($fieldDefinitions, $this, 'license_fields');
	}
	
	/**
	 * @param string $editMode
	 * @param array $errors
	 *
	 * @return bool
	 */
	public function hasValidLicenseFields(string $editMode = 'user', array &$errors = []): bool
	{
		/** @var Set $fields */
		$fields = $this->getLicenseFields();
		
		$definitions = $fields->getDefinitionSet();
		
		$errors = [];
		foreach ($definitions as $fieldId => $field)
		{
			/** @var \XF\CustomField\Definition $field */
			$value = $fields->getFieldValue($fieldId);
			
			$error = null;
			$valid = $field->isValid($value, $error, $value);
			if (!$valid)
			{
				$errors[] = $error;
			}
			
			if (($value === '' || $value === []) && $field->isRequired($editMode))
			{
				$errors[] = \XF::phraseDeferred('please_enter_value_for_required_field_x', ['field' => $field->title]);
			}
		}
		
		return empty($errors);
	}
	
	/**
	 * @param int $productId
	 * @return bool
	 */
	protected function verifyProduct(int &$productId): bool
	{
		if (!$productId)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_product_id'), 'product_id');
			return false;
		}

		$product = $this->_em->find('DBTech\eCommerce:Product', $productId);
		if (!$product)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_product_id'), 'product_id');
			return false;
		}

		return true;
	}
	
	/**
	 * @return bool
	 */
	public function rebuildCounters(): bool
	{
		$this->rebuildLastDownloadInfo();
		$this->applyTemporaryUserGroupChangeIfNeeded();
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public function rebuildLastDownloadInfo(): bool
	{
		$lastDownload = $this->db()->fetchRow("
			SELECT *
			FROM xf_dbtech_ecommerce_download_log AS download_log
			LEFT JOIN xf_dbtech_ecommerce_download AS download USING(download_id)
			WHERE download_log.license_id = ?
				AND download.download_state = 'visible'
			ORDER BY download.release_date DESC
			LIMIT 1
		", $this->license_id);

		$this->latest_download_id = $lastDownload ? $lastDownload['download_id'] : 0;
		
		return true;
	}
	
	/**
	 *
	 */
	public function rebuildLicenseFieldValuesCache()
	{
		$this->repository('DBTech\eCommerce:LicenseField')->rebuildLicenseFieldValuesCache($this->license_id);
	}
	
	/**
	 * @param float $amount
	 * @param int|null $userId
	 *
	 * @throws \XF\Db\Exception
	 */
	protected function adjustUserLicenseCountIfNeeded(float $amount, ?int $userId = null)
	{
		if ($userId === null)
		{
			$userId = $this->user_id;
		}
		
		if ($userId)
		{
			$this->db()->query('
				UPDATE xf_user
				SET dbtech_ecommerce_license_count = GREATEST(0, CAST(dbtech_ecommerce_license_count AS SIGNED) + ?)
				WHERE user_id = ?
			', [$amount, $userId]);
		}
	}
	
	/**
	 *
	 */
	protected function applyTemporaryUserGroupChangeIfNeeded()
	{
		if ($this->Product->temporary_extra_group_ids)
		{
			/** @var \XF\Service\User\TempChange $changeService */
			$changeService = $this->app()->service('XF:User\TempChange');
			$changeService->applyGroupChange(
				$this->User,
				'dbtechEcommerce-' . $this->license_id,
				$this->Product->temporary_extra_group_ids,
				'dbtechEcommerce-' . $this->product_id,
				$this->isLifetime() ? null : $this->expiry_date
			);
		}
	}
	
	/**
	 *
	 */
	protected function expireTemporaryUserGroupChange()
	{
		/** @var \XF\Service\User\TempChange $changeService */
		$changeService = $this->app()->service('XF:User\TempChange');
		$changeService->expireUserChangeByKey($this->User, 'dbtechEcommerce-' . $this->license_id);
	}

	/**
	 *
	 */
	protected function expireSerialKeyIfNeeded()
	{
		$this->db()->update('xf_dbtech_ecommerce_serial_key', [
			'available' => 0
		], 'license_id = ?', [$this->license_id]);
	}
	
	/**
	 * @throws \XF\Db\Exception
	 */
	protected function licenseMadeVisible()
	{
		$this->adjustUserLicenseCountIfNeeded(1);
		$this->applyTemporaryUserGroupChangeIfNeeded();
	}
	
	/**
	 * @param bool $hardDelete
	 *
	 * @throws \XF\Db\Exception
	 */
	protected function licenseHidden(bool $hardDelete = false)
	{
		$this->adjustUserLicenseCountIfNeeded(-1);
		$this->expireTemporaryUserGroupChange();
		$this->expireSerialKeyIfNeeded();
	}
	
	/**
	 * @param Product $from
	 * @param Product $to
	 */
	protected function licenseMoved(Product $from, Product $to)
	{
		$this->applyTemporaryUserGroupChangeIfNeeded();
	}
	
	/**
	 * @throws \InvalidArgumentException
	 * @throws \XF\Db\Exception
	 */
	protected function licenseReassigned()
	{
		if ($this->license_state == 'visible')
		{
			$this->adjustUserLicenseCountIfNeeded(-1, $this->getExistingValue('user_id'));
			$this->adjustUserLicenseCountIfNeeded(1);
		}
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function licenseInsertedVisible()
	{
		$this->adjustUserLicenseCountIfNeeded(1);
		$this->applyTemporaryUserGroupChangeIfNeeded();
	}
	
	/**
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function updateProductRecord()
	{
		if (!$this->Product)
		{
			return;
		}
		
		$product = $this->Product;
		
		if ($this->isUpdate() && $this->isChanged('product_id'))
		{
			// moved, trumps the rest
			if ($this->license_state == 'visible')
			{
				$product->licenseAdded($this);
				$product->save();
			}
			
			if ($this->getExistingValue('license_state') == 'visible')
			{
				/** @var Product $oldProduct */
				$oldProduct = $this->getExistingRelation('Product');
				if ($oldProduct)
				{
					$oldProduct->licenseRemoved($this);
					$oldProduct->save();
				}
			}
			
			return;
		}
		
		// check for entering/leaving visible
		$visibilityChange = $this->isStateChanged('license_state', 'visible');
		if ($visibilityChange == 'enter' && $product)
		{
			$product->licenseAdded($this);
			$product->save();
		}
		elseif ($visibilityChange == 'leave' && $product)
		{
			$product->licenseRemoved($this);
			$product->save();
		}
		elseif ($this->license_state == 'visible' && $this->isUpdate())
		{
			$product->licenseDataChanged($this);
			$product->save();
		}
	}
	
	/**
	 *
	 */
	protected function _preSave()
	{
		/** @var \XF\Entity\User $user */
		if ($user = $this->_em->find('XF:User', $this->user_id))
		{
			// Automatically set user name
			$this->username = $user->username;
		}
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		if ($this->isInsert() && $product = $this->_em->find('DBTech\eCommerce:Product', $this->product_id))
		{
			$this->license_key = $this->generateLicenseKey($product);
		}
		
		if ($this->isUpdate() && $this->isChanged('expiry_date'))
		{
			$this->sent_expired_reminder = false;
			$this->sent_expiring_reminder = false;
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
		$visibilityChange = $this->isStateChanged('license_state', 'visible');
		$approvalChange = $this->isStateChanged('license_state', 'moderated');
		$deletionChange = $this->isStateChanged('license_state', 'deleted');
		
		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->licenseMadeVisible();
			}
			elseif ($visibilityChange == 'leave')
			{
				$this->licenseHidden();
			}
			
			if ($this->isChanged('product_id'))
			{
				/** @var \DBTech\eCommerce\Entity\Product $oldProduct */
				$oldProduct = $this->getExistingRelation('Product');
				if ($oldProduct && $this->Product)
				{
					$this->licenseMoved($oldProduct, $this->Product);
				}
			}
			
			if ($deletionChange == 'leave' && $this->DeletionLog)
			{
				$this->DeletionLog->delete();
			}
			
			if ($approvalChange == 'leave' && $this->ApprovalQueue)
			{
				$this->ApprovalQueue->delete();
			}
			
			if ($this->isChanged('expiry_date'))
			{
				$this->applyTemporaryUserGroupChangeIfNeeded();
			}
		}
		else
		{
			// insert
			if ($this->license_state == 'visible')
			{
				$this->licenseInsertedVisible();
			}
		}
		
		if ($this->isUpdate() && $this->isChanged('user_id'))
		{
			$this->licenseReassigned();
		}
		
		if (\XF::config('dbtechEcommerceCacheReleases'))
		{
			\XF::asVisitor($this->User, function ()
			{
				$fs = \XF::fs();
				
				if (($this->isInsert() || $this->isChanged('parent_license_id'))
					&& $this->parent_license_id
					&& $this->Product
					&& $this->Product->hasDownloadFunctionality()
					&& $this->Product->Parent
				) {
					foreach ($this->Product->Parent->Downloads as $download)
					{
						if ($download->download_type == 'dbtech_ecommerce_autogen')
						{
							foreach ($download->Versions as $version)
							{
								// Delete all cached releases
								$fs->deleteDir($version->getReleaseAbstractPath($this->Parent));
							}
						}
					}
				}
				
				if (($this->isInsert() || $this->isChanged('license_fields'))
					&& $this->Product
					&& $this->Product->hasDownloadFunctionality()
				) {
					foreach ($this->Product->Downloads as $download)
					{
						if ($download->download_type == 'dbtech_ecommerce_autogen')
						{
							foreach ($download->Versions as $version)
							{
								// Delete all cached releases
								$fs->deleteDir($version->getReleaseAbstractPath($this));
							}
						}
					}
				}
			});
		}
		
		if ($approvalChange == 'enter')
		{
			/** @var \XF\Entity\ApprovalQueue $approvalQueue */
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->purchase_date;
			$approvalQueue->save();
		}
		elseif ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}
		
		$this->updateProductRecord();
		
		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('dbtech_ecommerce_license', $this);
		}
	}
	
	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \XF\Db\Exception
	 */
	protected function _postDelete()
	{
		if ($this->license_state == 'visible')
		{
			$this->licenseHidden(true);
		}
		
		if ($this->license_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->license_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('dbtech_ecommerce_license', $this, 'delete_hard');
		}
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
		
		if ($this->license_state == 'deleted')
		{
			return false;
		}
		
		$this->license_state = 'deleted';
		
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
		
		if ($this->license_state == 'visible')
		{
			return false;
		}
		
		$this->license_state = 'visible';
		$this->save();
		
		return true;
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-type License
	 *
	 * @api-out str $username
	 * @api-out Product $product <cond> If the "with_product" option is passed to the API Result generation.
	 * @api-out array $license_fields
	 */
	protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result,
		$verbosity = self::VERBOSITY_NORMAL,
		array $options = []
	) {
		$result->username = $this->User ? $this->User->username : $this->username;
		
		if (!empty($options['with_product']))
		{
			$result->includeRelation('Product', self::VERBOSITY_NORMAL, [
				'with_latest_version' => !empty($options['with_latest_version'])
			]);
		}
		
		$result->license_fields = $this->license_fields_;
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_license';
		$structure->shortName = 'DBTech\eCommerce:License';
		$structure->contentType = 'dbtech_ecommerce_license';
		$structure->primaryKey = 'license_id';
		$structure->columns = [
			'license_id'              => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true, 'changeLog' => false],
			'parent_license_id'       => ['type' => self::UINT, 'default' => 0],
			'product_id'              => [
				'type'   => self::UINT, 'required' => true, 'default' => 0, 'writeOnce' => true, 'api' => true,
				'verify' => 'verifyProduct'
			],
			'user_id'                 => ['type' => self::UINT, 'required' => true, 'api' => true],
			'username'                => [
				'type'     => self::STR, 'maxLength' => 50, 'changeLog' => false,
				'required' => 'please_enter_valid_name'
			],
			'purchase_date'           => ['type' => self::UINT, 'default' => \XF::$time, 'api' => true],
			'order_id'                => ['type' => self::UINT, 'default' => 0],
			'purchase_request_key'    => ['type' => self::STR, 'maxLength' => 32, 'nullable' => true],
			'expiry_date'             => ['type' => self::UINT, 'default' => \XF::$time, 'api' => true],
			'sent_expiring_reminder'  => ['type' => self::BOOL, 'default' => false],
			'sent_expired_reminder'   => ['type' => self::BOOL, 'default' => false],
			'latest_download_id'      => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'license_key'             => ['type' => self::STR, 'required' => true, 'writeOnce' => true, 'api' => true],
			'license_state'           => [
				'type'          => self::STR, 'default' => 'visible',
				'allowedValues' => [
					'visible', 'awaiting_payment', 'moderated', 'deleted'
				],
				'api'           => true
			],
			'license_fields'          => ['type' => self::JSON_ARRAY, 'default' => [], 'changeLog' => 'customFields'],
			'discussion_thread_id'    => ['type' => self::UINT, 'default' => 0],
			'required_user_group_ids' => ['type' => self::JSON_ARRAY, 'default' => [], 'api' => true],
		];
		$structure->behaviors = [
			'XF:ChangeLoggable'      => ['contentType' => 'dbtech_ecommerce_license'],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField'     => 'purchase_date'
			],
			'XF:CustomFieldsHolder'  => [
				'column'     => 'license_fields',
				'valueTable' => 'xf_dbtech_ecommerce_license_field_value'
			]
		];
		$structure->getters = [
			'title'          => true,
			'full_title'     => true,
			'license_fields' => true
		];
		$structure->relations = [
			'Parent'           => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_ONE,
				'conditions' => [
					['license_id', '=', '$parent_license_id']
				],
				'primary'    => true
			],
			'Children'         => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_MANY,
				'conditions' => [
					['parent_license_id', '=', '$license_id']
				]
			],
			'Product'          => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
			'Order'            => [
				'entity'     => 'DBTech\eCommerce:Order',
				'type'       => self::TO_ONE,
				'conditions' => 'order_id',
				'primary'    => true
			],
			'User'             => [
				'entity'     => 'XF:User',
				'type'       => self::TO_ONE,
				'conditions' => 'user_id',
				'primary'    => true
			],
			'SerialKey'        => [
				'entity'     => 'DBTech\eCommerce:SerialKey',
				'type'       => self::TO_ONE,
				'conditions' => 'license_id'
			],
			'Discussion'       => [
				'entity'     => 'XF:Thread',
				'type'       => self::TO_ONE,
				'conditions' => [
					['thread_id', '=', '$discussion_thread_id']
				],
				'primary'    => true
			],
			'LicenseFields'    => [
				'entity'     => 'DBTech\eCommerce:LicenseFieldValue',
				'type'       => self::TO_MANY,
				'conditions' => 'license_id',
				'key'        => 'field_id'
			],
			'LatestDownloaded' => [
				'entity'     => 'DBTech\eCommerce:Download',
				'type'       => self::TO_ONE,
				'conditions' => [
					['download_id', '=', '$latest_download_id']
				],
				'primary'    => true
			],
			'DownloadLog'      => [
				'entity'        => 'DBTech\eCommerce:DownloadLog',
				'type'          => self::TO_MANY,
				'conditions'    => 'license_id',
				'cascadeDelete' => true
			],
			'DeletionLog'      => [
				'entity'     => 'XF:DeletionLog',
				'type'       => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_license'],
					['content_id', '=', '$license_id']
				],
				'primary'    => true
			],
			'ApprovalQueue'    => [
				'entity'     => 'XF:ApprovalQueue',
				'type'       => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_license'],
					['content_id', '=', '$license_id']
				],
				'primary'    => true
			]
		];

		$structure->options = [
			'log_moderator' => true
		];

		$structure->withAliases = [
			'product' => [
				function (): array
				{
					return [
						'Product',
						'Product.Permissions|' . \XF::visitor()->permission_combination_id
					];
				},
				function (array $withParams): ?array
				{
					if (!empty($withParams['category']))
					{
						return ['Product.Category'];
					}

					return null;
				}
			],
			'full'    => [
				'LatestDownloaded',
				'Product.full|category',
				'Product.LatestVersion',
				'User'
			],
			'api'     => [
				'User',
				'User.api',
				function (array $withParams): ?array
				{
					if (!empty($withParams['product']))
					{
						return ['Product.api'];
					}

					return null;
				}
			]
		];

		$structure->defaultWith = ['Product'];

		return $structure;
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
}