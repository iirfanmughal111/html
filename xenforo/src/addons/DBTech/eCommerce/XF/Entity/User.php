<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class User
 * @package DBTech\eCommerce\XF\Entity
 */
class User extends XFCP_User
{
	/**
	 * @param null $error
	 * @return bool
	 */
	public function canViewDbtechEcommerceProducts(&$error = null)
	{
		return $this->hasPermission('dbtechEcommerce', 'view');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canPurchaseDbtechEcommerceProducts(&$error = null)
	{
		return $this->hasPermission('dbtechEcommerce', 'purchase');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canViewDbtechEcommerceIncomeStats(&$error = null)
	{
		return $this->hasPermission('dbtechEcommerce', 'viewIncomeStats');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canViewAnyDbtechEcommerceIncomeStats(&$error = null)
	{
		return $this->hasPermission('dbtechEcommerceAdmin', 'viewAnyIncomeStats');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canViewDbtechEcommerceLicenses(&$error = null)
	{
		return $this->hasPermission('dbtechEcommerceAdmin', 'viewLicenses');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canEditAnyDbtechEcommerceLicenses(&$error = null)
	{
		return $this->hasPermission('dbtechEcommerceAdmin', 'editAnyLicenses');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canDownloadAnyDbtechEcommerceLicense(&$error = null)
	{
		return $this->hasPermission('dbtechEcommerceAdmin', 'downloadAnyLicenses');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canAddDbtechEcommerceProduct(&$error = null)
	{
		return ($this->user_id && $this->hasPermission('dbtechEcommerce', 'add'));
	}

	/**
	 * @param $contentId
	 * @param $permission
	 *
	 * @return bool
	 */
	public function hasDbtechEcommerceCategoryPermission($contentId, $permission)
	{
		return $this->PermissionSet->hasContentPermission('dbtech_ecommerce_category', $contentId, $permission);
	}

	/**
	 * @param $contentId
	 * @param $permission
	 *
	 * @return bool
	 */
	public function hasDbtechEcommerceProductPermission($contentId, $permission)
	{
		return $this->PermissionSet->hasContentPermission('dbtech_ecommerce_product', $contentId, $permission);
	}

	/**
	 * @param $contentId
	 * @param $permission
	 *
	 * @return bool
	 */
	public function hasDbtechEcommerceCouponPermission($contentId, $permission)
	{
		return $this->PermissionSet->hasContentPermission('dbtech_ecommerce_coupon', $contentId, $permission);
	}

	/**
	 * @param $contentId
	 * @param $permission
	 *
	 * @return bool
	 */
	public function hasDbtechEcommerceDiscountPermission($contentId, $permission)
	{
		return $this->PermissionSet->hasContentPermission('dbtech_ecommerce_discount', $contentId, $permission);
	}

	/**
	 * @param array|null $categoryIds
	 */
	public function cacheDbtechEcommerceCategoryPermissions(array $categoryIds = null)
	{
		if (is_array($categoryIds))
		{
			\XF::permissionCache()->cacheContentPermsByIds($this->permission_combination_id, 'dbtech_ecommerce_category', $categoryIds);
		}
		else
		{
			\XF::permissionCache()->cacheAllContentPerms($this->permission_combination_id, 'dbtech_ecommerce_category');
		}
	}

	/**
	 * @param array|null $productIds
	 */
	public function cacheDbtechEcommerceProductPermissions(array $productIds = null)
	{
		if (is_array($productIds))
		{
			\XF::permissionCache()->cacheContentPermsByIds($this->permission_combination_id, 'dbtech_ecommerce_product', $productIds);
		}
		else
		{
			\XF::permissionCache()->cacheAllContentPerms($this->permission_combination_id, 'dbtech_ecommerce_product');
		}
	}

	/**
	 * @param array|null $couponIds
	 */
	public function cacheDbtechEcommerceCouponPermissions(array $couponIds = null)
	{
		if (is_array($couponIds))
		{
			\XF::permissionCache()->cacheContentPermsByIds($this->permission_combination_id, 'dbtech_ecommerce_coupon', $couponIds);
		}
		else
		{
			\XF::permissionCache()->cacheAllContentPerms($this->permission_combination_id, 'dbtech_ecommerce_coupon');
		}
	}

	/**
	 * @param array|null $discountIds
	 */
	public function cacheDbtechEcommerceDiscountPermissions(array $discountIds = null)
	{
		if (is_array($discountIds))
		{
			\XF::permissionCache()->cacheContentPermsByIds($this->permission_combination_id, 'dbtech_ecommerce_discount', $discountIds);
		}
		else
		{
			\XF::permissionCache()->cacheAllContentPerms($this->permission_combination_id, 'dbtech_ecommerce_discount');
		}
	}

	/**
	 * @return null|\XF\Entity\Page
	 */
	public function getDbtechEcommerceTerms()
	{
		$options = \XF::options();

		if (!$options->dbtechEcommerceTermsPageId)
		{
			return null;
		}

		/** @var \XF\Entity\Page $page */
		$page = $this->_em->find('XF:Page', $options->dbtechEcommerceTermsPageId);

		return $page;
	}

	/**
	 * @return bool
	 */
	public function hasAcceptedDbtechEcommerceTerms()
	{
		/** @var \XF\Entity\Page $page */
		$page = $this->dbtech_ecommerce_terms;

		if (!$page)
		{
			return true;
		}

		if ($this->user_id)
		{
			return ($page->modified_date <= $this->dbtech_ecommerce_tos_accept);
		}

		return ($page->modified_date <= $this->app()->request()->getCookie('dbtechEcommerceTosAccept', 0));
	}

	/**
	 * @return float
	 * @throws \Exception
	 */
	public function getDbtechEcommerceSubTotal()
	{
		/** @var \DBTech\eCommerce\Repository\Order $orderRepo */
		$orderRepo = $this->repository('DBTech\eCommerce:Order');
		return $orderRepo->getSubTotalForUser($this->user_id);
	}

	/**
	 * @return int|mixed|null
	 */
	public function getDbtechEcommerceCartItems()
	{
		if ($this->user_id)
		{
			return $this->canPurchaseDbtechEcommerceProducts() ? $this->dbtech_ecommerce_cart_items : 0;
		}

		return $this->app()->request()->getCookie('dbtechEcommerceCartItems', 0);
	}

	/**
	 * @param $orderId
	 *
	 * @return int
	 */
	public function rebuildDbtechEcommerceCartItems($orderId)
	{
		$orderItems = $this->db()->fetchOne('
			SELECT SUM(quantity)
			FROM xf_dbtech_ecommerce_order_item
			WHERE user_id = ?
				AND order_id = ?
		', [$this->user_id, $orderId]);

		if ($this->user_id)
		{
			$this->dbtech_ecommerce_cart_items = $orderItems;
			$this->saveIfChanged();
		}
		else
		{
			$this->app()->response()->setCookie('dbtechEcommerceCartItems', $orderItems, 86400 * 365);
		}

		return $orderItems;
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 * @noinspection PhpMissingReturnTypeInspection
	 */
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);

		$structure->columns['dbtech_ecommerce_store_credit'] = ['type' => self::UINT, 'min' => 0, 'default' => 0, 'changeLog' => false];
		$structure->columns['dbtech_ecommerce_tos_accept'] = ['type' => self::UINT, 'default' => 0];
		$structure->columns['dbtech_ecommerce_product_count'] = ['type' => self::UINT, 'min' => 0, 'default' => 0, 'changeLog' => false];
		$structure->columns['dbtech_ecommerce_license_count'] = ['type' => self::UINT, 'min' => 0, 'default' => 0, 'changeLog' => false];
		$structure->columns['dbtech_ecommerce_amount_spent'] = ['type' => self::FLOAT, 'min' => 0, 'default' => 0, 'changeLog' => false];
		$structure->columns['dbtech_ecommerce_cart_items'] = ['type' => self::UINT, 'default' => 0, 'changeLog' => false];
		$structure->columns['dbtech_ecommerce_is_distributor'] = ['type' => self::BOOL, 'default' => 0, 'changeLog' => false];
		$structure->columns['dbtech_ecommerce_api_key'] = ['type' => self::UINT, 'default' => 0];

		$structure->getters['dbtech_ecommerce_sub_total'] = true;
		$structure->getters['dbtech_ecommerce_terms'] = true;

		$structure->relations['DBTechEcommerceApiKey'] = [
			'entity' => 'XF:ApiKey',
			'type' => self::TO_ONE,
			'conditions' => [
				['api_key_id', '=', '$dbtech_ecommerce_api_key']
			]
		];

		$structure->relations['DBTechEcommerceCommission'] = [
			'entity' => 'DBTech\eCommerce:Commission',
			'type' => self::TO_ONE,
			'conditions' => 'user_id',
			'cascadeDelete' => true
		];

		$structure->relations['DBTechEcommerceLicenses'] = [
			'entity' => 'DBTech\eCommerce:License',
			'type' => self::TO_MANY,
			'conditions' => 'user_id',
			'key' => 'license_id'
		];
		$structure->relations['DBTechEcommerceProducts'] = [
			'entity' => 'DBTech\eCommerce:Product',
			'type' => self::TO_MANY,
			'conditions' => 'user_id',
			'key' => 'product_id'
		];

		$structure->options['hasDbEcommerce'] = true;

		return $structure;
	}
}