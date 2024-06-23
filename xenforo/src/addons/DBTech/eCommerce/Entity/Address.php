<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $address_id
 * @property int $user_id
 * @property string $address_state
 * @property string $title
 * @property string $business_title
 * @property string $business_co
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $address4
 * @property string $country_code
 * @property string $email
 * @property string $sales_tax_id
 * @property bool $is_default
 * @property int $order_count
 *
 * GETTERS
 * @property string $description
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \DBTech\eCommerce\Entity\Country $Country
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\Order[] $Orders
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingCombination[] $ApplicableShippingMethods
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 */
class Address extends Entity
{
	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->app()->templater()->renderMacro(
			'public:dbtech_ecommerce_address_edit_macros',
			'display_pairs',
			['address' => $this]
		);
	}

	/**
	 * @return bool
	 */
	public function canView(): bool
	{
		return (!$this->user_id
			|| \XF::visitor()->user_id == $this->user_id
		);
	}

	/**
	 * @return bool
	 */
	public function hasVerifiedSalesTaxId(): bool
	{
		return ($this->sales_tax_id && $this->address_state == 'verified');
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canEdit(&$error = null): bool
	{
		$visitor = \XF::visitor();
		if ($visitor->user_id != $this->user_id)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_requested_address_not_found');
			return false;
		}
		
		if ($this->address_state == 'verified')
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_may_not_edit_verified_addresses');
			return false;
		}
		
		if ($this->order_count)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_may_not_edit_addresses_used_in_orders');
			return false;
		}
		
		return true;
	}
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canDelete(&$error = null): bool
	{
		$visitor = \XF::visitor();
		if ($visitor->user_id != $this->user_id)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_requested_address_not_found');
			return false;
		}
		
		if ($this->order_count)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_may_not_delete_addresses_used_in_orders');
			return false;
		}
		
		return true;
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canApproveUnapprove(&$error = null): bool
	{
		$visitor = \XF::visitor();
		return (
			$visitor->user_id
			&& $visitor->hasPermission('dbtechEcommerce', 'approveUnapprove')
		);
	}
	
	/**
	 * @return bool
	 */
	public function canSendModeratorActionAlert(): bool
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id
			&& $this->user_id
			&& $visitor->user_id != $this->user_id
		);
	}

	/**
	 * @param string $email
	 * @param array  $bannedEmails
	 * @return string|null
	 */
	protected function getBannedEntryFromEmail(string $email, array $bannedEmails): ?string
	{
		foreach ($bannedEmails AS $bannedEmail)
		{
			$bannedEmailTest = str_replace('\\*', '(.*)', preg_quote($bannedEmail, '/'));
			if (preg_match('/^' . $bannedEmailTest . '$/i', $email))
			{
				return $bannedEmail;
			}
		}

		return null;
	}

	/**
	 * @param string $email
	 *
	 * @return bool
	 */
	protected function verifyEmail(string &$email): bool
	{
		if ($this->isUpdate() && $email === $this->getExistingValue('email'))
		{
			return true;
		}
		
		if ($this->getOption('admin_edit') && $email === '')
		{
			return true;
		}
		
		/** @var \XF\Repository\Banning $banningRepo */
		$banningRepo = $this->repository('XF:Banning');
		
		$bannedEmails = $this->app()->container('bannedEmails');
		
		$emailValidator = $this->app()->validator('Email');
		if (!$this->getOption('admin_edit'))
		{
			$emailValidator->setOption('banned', $bannedEmails);
		}
		$emailValidator->setOption('allow_empty', true);
		$emailValidator->setOption('check_typos', true);
		$email = $emailValidator->coerceValue($email);
		if (!$emailValidator->isValid($email, $errorKey))
		{
			if ($errorKey == 'banned')
			{
				// try to find triggering banned email entry. try exact match first...
				$emailBan = $this->_em->findOne('XF:BanEmail', ['banned_email' => $email]);
				if (!$emailBan)
				{
					// ...otherwise find the first entry that triggered
					if (is_callable([$banningRepo, 'getBannedEntryFromEmail']))
					{
						$bannedEmail = $banningRepo->getBannedEntryFromEmail($email, $bannedEmails);
					}
					else
					{
						$bannedEmail = $this->getBannedEntryFromEmail($email, $bannedEmails);
					}
					
					if ($bannedEmail)
					{
						$emailBan = $this->_em->findOne('XF:BanEmail', ['banned_email' => $bannedEmail]);
					}
				}
				if ($emailBan)
				{
					$emailBan->fastUpdate('last_triggered_date', time());
				}
			}
			elseif ($errorKey == 'typo')
			{
				$this->error(\XF::phrase('email_address_you_entered_appears_have_typo'));
			}
			else
			{
				$this->error(\XF::phrase('please_enter_valid_email'), 'email');
			}
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * @param Order $order
	 *
	 * @return bool
	 */
	public function orderAdded(Order $order): bool
	{
		if ($order->order_state != 'pending')
		{
			$this->order_count++;
			return true;
		}
		
		return false;
	}
	
	/**
	 * @param Order $order
	 *
	 * @return bool
	 */
	public function orderRemoved(Order $order): bool
	{
		if ($order->order_state != 'pending')
		{
			$this->order_count--;
			return true;
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function rebuildCounters(): bool
	{
		$this->rebuildOrderCount();
		
		return true;
	}
	
	/**
	 * @return bool|mixed|null
	 */
	public function rebuildOrderCount()
	{
		$this->order_count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_dbtech_ecommerce_order
			WHERE (address_id = ? OR shipping_address_id = ?)
				AND order_state != 'pending'
		", [$this->address_id, $this->address_id]);
		
		return $this->order_count;
	}
	
	/**
	 *
	 */
	protected function _preSave()
	{
		if (!$this->user_id && !$this->email)
		{
			$this->error(\XF::phrase('please_enter_valid_email'), 'email');
			return;
		}
		
		if (!$this->getOption('admin_edit'))
		{
			// We should be able to override this
			$moderatedAddressState = 'moderated';
			
			/** @var \XF\Entity\User $user */
			if ($user = $this->_em->find('XF:User', $this->user_id))
			{
				if ($user->hasPermission('dbtechEcommerce', 'addressWithoutApproval'))
				{
					// Gowan lad, you can get through
					$moderatedAddressState = 'verified';
				}
			}
			
			if ($this->isInsert() && $this->sales_tax_id)
			{
				$this->address_state = $moderatedAddressState;
			}
			elseif ($this->isUpdate())
			{
				if (!$this->sales_tax_id)
				{
					$this->address_state = 'visible';
				}
				elseif ($this->isChanged('sales_tax_id'))
				{
					$this->address_state = $moderatedAddressState;
				}
			}
		}
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	protected function _postSave()
	{
		$approvalChange = $this->isStateChanged('address_state', 'moderated');
		
		if ($this->isUpdate())
		{
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

		if ($this->is_default)
		{
			$this->db()->update(
				'xf_dbtech_ecommerce_address',
				['is_default' => 0],
				'user_id = ?',
				$this->user_id
			);
		}
	}
	
	/**
	 * @throws \XF\Db\Exception
	 * @throws \XF\PrintableException
	 */
	protected function _postDelete()
	{
		if ($this->address_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_order
			SET address_id = 0
			WHERE address_id = ?
		', $this->address_id);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_address';
		$structure->shortName = 'DBTech\eCommerce:Address';
		$structure->primaryKey = 'address_id';
		$structure->columns = [
			'address_id'     => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'user_id'        => ['type' => self::UINT, 'required' => true],
			'address_state'  => [
				'type'          => self::STR,
				'default'       => 'visible',
				'allowedValues' => ['visible', 'verified', 'moderated', 'deleted'],
				'api'           => true
			],
			'title'          => [
				'type'      => self::STR,
				'maxLength' => 100,
				'required'  => 'please_enter_valid_title'
			],
			'business_title' => [
				'type'      => self::STR,
				'maxLength' => 255,
				'required'  => 'dbtech_ecommerce_please_enter_name_or_business'
			],
			'business_co'    => ['type' => self::STR, 'maxLength' => 100],
			'address1'       => ['type' => self::STR, 'maxLength' => 100],
			'address2'       => ['type' => self::STR, 'maxLength' => 100],
			'address3'       => ['type' => self::STR, 'maxLength' => 100],
			'address4'       => ['type' => self::STR, 'maxLength' => 100],
			'country_code'   => [
				'type'      => self::STR,
				'maxLength' => 2,
				'required'  => 'dbtech_ecommerce_please_choose_country',
				'default'   => \XF::options()->dbtechEcommerceDefaultAddressCountry
			],
			'email'          => ['type' => self::STR, 'maxLength' => 120],
			'sales_tax_id'   => ['type' => self::STR, 'maxLength' => 100, 'default' => ''],
			'is_default'     => ['type' => self::BOOL, 'default' => false],
			'order_count'    => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true]
		];
		$structure->behaviors = [];
		$structure->getters = [
			'description' => true
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Country' => [
				'entity' => 'DBTech\eCommerce:Country',
				'type' => self::TO_ONE,
				'conditions' => 'country_code',
				'primary' => true
			],
			'Orders' => [
				'entity' => 'DBTech\eCommerce:Order',
				'type' => self::TO_MANY,
				'conditions' => 'address_id',
				'primary' => true
			],
			'ApplicableShippingMethods' => [
				'entity' => 'DBTech\eCommerce:ShippingCombination',
				'type' => self::TO_MANY,
				'conditions' => [
					['country_code', '=', '$country_code']
				],
				'key' => 'shipping_method_id',
				'with' => [
					'ShippingMethod',
					'ShippingZone'
				]
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_address'],
					['content_id', '=', '$address_id']
				],
				'primary' => true
			]
		];
		$structure->options = [
			'admin_edit' => false
		];
		$structure->defaultWith = [
			'Country'
		];

		return $structure;
	}
}