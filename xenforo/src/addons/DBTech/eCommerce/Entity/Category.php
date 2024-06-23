<?php

namespace DBTech\eCommerce\Entity;

use XF\Entity\AbstractCategoryTree;
use XF\Entity\LinkableInterface;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $category_id
 * @property string $title
 * @property string $description
 * @property int $product_count
 * @property int $last_update
 * @property string $last_product_title
 * @property int $last_product_id
 * @property array $prefix_cache
 * @property array $field_cache
 * @property array $review_field_cache
 * @property array $product_filters
 * @property bool $require_prefix
 * @property int $thread_node_id
 * @property int $thread_prefix_id
 * @property string $product_update_notify
 * @property bool $always_moderate_create
 * @property bool $always_moderate_update
 * @property int $min_tags
 * @property int $parent_category_id
 * @property int $display_order
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property array $breadcrumb_data
 *
 * GETTERS
 * @property \XF\Mvc\Entity\AbstractCollection $prefixes
 *
 * RELATIONS
 * @property \XF\Entity\Forum $ThreadForum
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\Product[] $Products
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\CategoryWatch[] $Watch
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\PermissionCacheContent[] $Permissions
 */
class Category extends AbstractCategoryTree implements LinkableInterface
{
	/** @var array */
	protected $_viewableDescendants = [];
	
	/**
	 * @param null $error
	 *
	 * @return mixed
	 */
	public function canView(&$error = null): bool
	{
		return $this->hasPermission('view');
	}
	
	/**
	 * @return mixed
	 */
	public function canViewDeletedProducts(): bool
	{
		return $this->hasPermission('viewDeleted');
	}
	
	/**
	 * @return mixed
	 */
	public function canViewModeratedProducts(): bool
	{
		return $this->hasPermission('viewModerated');
	}
	
	/**
	 * @param Product|null $product
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canEditTags(Product $product = null, &$error = null): bool
	{
		if (!$this->app()->options()->enableTagging)
		{
			return false;
		}
		
		$visitor = \XF::visitor();
		
		// if no product, assume will be owned by this person
		if (!$product || $product->user_id == $visitor->user_id)
		{
			if ($this->hasPermission('tagOwnProduct'))
			{
				return true;
			}
		}
		
		return (
			$this->hasPermission('tagAnyProduct')
			|| $this->hasPermission('manageAnyTag')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return mixed
	 */
	public function canUseInlineModeration(&$error = null): bool
	{
		return $this->hasPermission('inlineMod');
	}
	
	/**
	 * @return mixed
	 */
	public function canUploadAndManageProductImages(): bool
	{
		return $this->hasPermission('uploadProductAttach');
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canAddProduct(&$error = null): bool
	{
		return \XF::visitor()->user_id && $this->hasPermission('add');
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canWatch(&$error = null): bool
	{
		return (\XF::visitor()->user_id ? true : false);
	}
	
	/**
	 * @param string $permission
	 *
	 * @return mixed
	 */
	public function hasPermission(string $permission): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor->hasOption('hasDbEcommerce') && $visitor->hasDbtechEcommerceCategoryPermission($this->category_id, $permission);
	}
	
	/**
	 * @return mixed
	 */
	public function getViewableDescendants()
	{
		$userId = \XF::visitor()->user_id;
		if (!isset($this->_viewableDescendants[$userId]))
		{
			/** @var \DBTech\eCommerce\Repository\Category $categoryRepos */
			$categoryRepos = $this->repository('DBTech\eCommerce:Category');
			$viewable = $categoryRepos->getViewableCategories($this);
			$this->_viewableDescendants[$userId] = $viewable->toArray();
		}
		
		return $this->_viewableDescendants[$userId];
	}
	
	/**
	 * @param array $descendents
	 * @param int|null $userId
	 */
	public function cacheViewableDescendents(array $descendents, ?int $userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		
		$this->_viewableDescendants[$userId] = $descendents;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductPrefix|int|null $forcePrefix
	 *
	 * @return mixed
	 */
	public function getUsablePrefixes($forcePrefix = null)
	{
		$prefixes = $this->prefixes;
		
		if ($forcePrefix instanceof ProductPrefix)
		{
			$forcePrefix = $forcePrefix->prefix_id;
		}
		
		$prefixes = $prefixes->filter(function (ProductPrefix $prefix) use ($forcePrefix): bool
		{
			if ($forcePrefix && $forcePrefix == $prefix->prefix_id)
			{
				return true;
			}
			return $this->isPrefixUsable($prefix);
		});
		
		return $prefixes->groupBy('prefix_group_id');
	}
	
	/**
	 * @return mixed
	 */
	public function getPrefixesGrouped()
	{
		return $this->prefixes->groupBy('prefix_group_id');
	}
	
	/**
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getPrefixes(): \XF\Mvc\Entity\AbstractCollection
	{
		if (!$this->prefix_cache)
		{
			return $this->_em->getEmptyCollection();
		}

		return $this->finder('DBTech\eCommerce:ProductPrefix')
			->where('prefix_id', $this->prefix_cache)
			->order('materialized_order')
			->fetch()
			;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductPrefix|int $prefix
	 * @param \XF\Entity\User|null $user
	 *
	 * @return bool
	 */
	public function isPrefixUsable($prefix, \XF\Entity\User $user = null): bool
	{
		if (!$this->isPrefixValid($prefix))
		{
			return false;
		}
		
		if (!($prefix instanceof ProductPrefix))
		{
			$prefix = $this->em()->find('DBTech\eCommerce:ProductPrefix', $prefix);
			if (!$prefix)
			{
				return false;
			}
		}
		
		return $prefix->isUsableByUser($user);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductPrefix|int $prefix
	 *
	 * @return bool
	 */
	public function isPrefixValid($prefix): bool
	{
		if ($prefix instanceof ProductPrefix)
		{
			$prefix = $prefix->prefix_id;
		}
		
		return (!$prefix || isset($this->prefix_cache[$prefix]));
	}
	
	/**
	 * @param string $productType
	 *
	 * @return Product
	 * @throws \InvalidArgumentException
	 */
	public function getNewProduct(string $productType): Product
	{
		try
		{
			$this->getProductRepo()->getProductTypeHandler($productType);
		}
		catch (\Exception $e)
		{
			throw new \InvalidArgumentException('Invalid product type.');
		}
		
		/** @var Product $product */
		$product = $this->_em->create('DBTech\eCommerce:Product');
		$product->product_category_id = $this->category_id;
		$product->product_type = $productType;
		$product->hydrateRelation('Category', $this);
		
		return $product;
	}
	
	/**
	 * @param Product|null $product
	 *
	 * @return string
	 */
	public function getNewContentState(?Product $product = null): string
	{
		$visitor = \XF::visitor();
		
		if ($visitor->user_id && $this->hasPermission('approveUnapprove'))
		{
			return 'visible';
		}

		if (!$this->hasPermission('addWithoutApproval'))
		{
			return 'moderated';
		}
		
		if ($product)
		{
			return $this->always_moderate_update ? 'moderated' : 'visible';
		}
		
		return $this->always_moderate_create ? 'moderated' : 'visible';
	}
	
	/**
	 * @param bool $includeSelf
	 * @param string $linkType
	 *
	 * @return array
	 */
	public function getBreadcrumbs(bool $includeSelf = true, string $linkType = 'public'): array
	{
		if ($linkType == 'public')
		{
			$link = 'dbtech-ecommerce/categories';
		}
		else
		{
			$link = 'dbtech-ecommerce/categories';
		}
		return $this->_getBreadcrumbs($includeSelf, $linkType, $link);
	}
	
	/**
	 * @return array
	 */
	public function getCategoryListExtras(): array
	{
		return [
			'product_count' => $this->product_count,
			'last_update' => $this->last_update,
			'last_product_title' => $this->last_product_title,
			'last_product_id' => $this->last_product_id
		];
	}
	
	/**
	 * @param Product $product
	 */
	public function productAdded(Product $product)
	{
		$this->product_count++;
		
		if ($product->last_update >= $this->last_update)
		{
			$this->last_update = $product->last_update;
			$this->last_product_title = $product->title;
			$this->last_product_id = $product->product_id;
		}
	}
	
	/**
	 * @param Product $product
	 *
	 * @throws \InvalidArgumentException
	 */
	public function productDataChanged(Product $product)
	{
		if ($product->isChanged(['last_update', 'title']))
		{
			if ($product->last_update >= $this->last_update)
			{
				$this->last_update = $product->last_update;
				$this->last_product_title = $product->title;
				$this->last_product_id = $product->product_id;
			}
			elseif ($product->getExistingValue('last_update') == $this->last_update)
			{
				$this->rebuildLastProduct();
			}
		}
	}
	
	/**
	 * @param Product $product
	 */
	public function productRemoved(Product $product)
	{
		$this->product_count--;
		
		if ($product->last_update == $this->last_update)
		{
			$this->rebuildLastProduct();
		}
	}
	
	/**
	 * @return bool
	 */
	public function rebuildCounters(): bool
	{
		$this->rebuildProductCount();
		$this->rebuildLastProduct();
		
		return true;
	}
	
	/**
	 * @return bool|mixed|null
	 */
	public function rebuildProductCount()
	{
		$this->product_count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_dbtech_ecommerce_product
			WHERE product_category_id = ?
				AND product_state = 'visible'
				AND parent_product_id = 0
		", $this->category_id);
		
		return $this->product_count;
	}
	
	/**
	 *
	 */
	public function rebuildLastProduct()
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->finder('DBTech\eCommerce:Product')
			->where('product_category_id', $this->category_id)
			->where('product_state', 'visible')
			->order('last_update', 'desc')
			->fetchOne();
		
		if ($product)
		{
			$this->last_update = $product->last_update;
			$this->last_product_title = $product->title;
			$this->last_product_id = $product->product_id;
		}
		else
		{
			$this->last_update = 0;
			$this->last_product_title = '';
			$this->last_product_id = 0;
		}
	}

	/**
	 * @param bool $canonical
	 * @param array $extraParams
	 * @param null $hash
	 *
	 * @return mixed|string
	 */
	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null): string
	{
		$route = $canonical ? 'canonical:dbtech-ecommerce/categories' : 'dbtech-ecommerce/categories';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}

	/**
	 * @return string|null
	 */
	public function getContentPublicRoute(): ?string
	{
		return 'dbtech-ecommerce/categories';
	}

	/**
	 * @param string $context
	 *
	 * @return string|\XF\Phrase
	 */
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('dbtech_ecommerce_category_x', ['title' => $this->title]);
	}

	/**
	 *
	 */
	protected function _preSave()
	{
		if ($this->isChanged(['thread_node_id', 'thread_prefix_id']))
		{
			if (!$this->thread_node_id)
			{
				$this->thread_prefix_id = 0;
			}
			else
			{
				if (!$this->ThreadForum)
				{
					$this->thread_node_id = 0;
					$this->thread_prefix_id = 0;
				}
				elseif ($this->thread_prefix_id && !$this->ThreadForum->isPrefixValid($this->thread_prefix_id))
				{
					$this->thread_prefix_id = 0;
				}
			}
		}
	}
	
	/**
	 *
	 */
	protected function _postDelete()
	{
		$db = $this->db();
		
		$db->delete('xf_dbtech_ecommerce_category_watch', 'category_id = ?', $this->category_id);
		
		if ($this->getOption('delete_products'))
		{
			$this->app()->jobManager()->enqueueUnique('dbtechEcomCategoryDelete' . $this->category_id, 'DBTech\eCommerce:CategoryDelete', [
				'category_id' => $this->category_id
			]);
		}
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-type Category
	 *
	 * @api-out str $prefixes
	 * @api-out array $product_fields
	 * @api-out bool $can_add
	 * @api-out bool $can_upload_images
	 */
	protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result,
		$verbosity = self::VERBOSITY_NORMAL,
		array $options = []
	) {
		if ($verbosity > self::VERBOSITY_NORMAL)
		{
			$result->prefixes = $this->prefixes->toApiResults();
			
			$fields = [];
			if ($this->field_cache)
			{
				$fieldEntities = $this->repository('DBTech\eCommerce:ProductField')->findFieldsForList()
					->whereIds($this->field_cache)
					->fetch();
				foreach ($fieldEntities AS $fieldId => $field)
				{
					$fields[$fieldId] = $field->toApiResult();
				}
			}
			
			$result->product_fields = $fields;
		}
		
		$result->can_add = $this->canAddProduct();
		$result->can_upload_images = $this->canUploadAndManageProductImages();
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_category';
		$structure->shortName = 'DBTech\eCommerce:Category';
		$structure->primaryKey = 'category_id';
		$structure->contentType = 'dbtech_ecommerce_category';
		$structure->columns = [
			'category_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'title' => ['type' => self::STR, 'maxLength' => 100,
				'required' => 'please_enter_valid_title', 'api' => true
			],
			'description' => ['type' => self::STR, 'default' => '', 'api' => true],
			'product_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'last_update' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'last_product_title' => ['type' => self::STR, 'default' => '', 'maxLength' => 100,
				'censor' => true, 'api' => true
			],
			'last_product_id' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'prefix_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
			'field_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
			'review_field_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
			'product_filters' => ['type' => self::JSON_ARRAY, 'default' => []],
			'require_prefix' => ['type' => self::BOOL, 'default' => false],
			'thread_node_id' => ['type' => self::UINT, 'default' => 0],
			'thread_prefix_id' => ['type' => self::UINT, 'default' => 0],
			'product_update_notify' => ['type' => self::STR, 'default' => 'thread',
				'allowedValues' => ['thread', 'reply']
			],
			'always_moderate_create' => ['type' => self::BOOL, 'default' => false],
			'always_moderate_update' => ['type' => self::BOOL, 'default' => false],
			'min_tags' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'max' => 100, 'api' => true],
		];
		$structure->behaviors = [];
		$structure->getters = [
			'prefixes' => true,
		];
		$structure->relations = [
			'ThreadForum' => [
				'entity' => 'XF:Forum',
				'type' => self::TO_ONE,
				'conditions' => [
					['node_id', '=', '$thread_node_id']
				],
				'primary' => true,
				'with' => 'Node'
			],
			'Products' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_MANY,
				'conditions' => [
					['product_category_id', '=', '$category_id']
				],
				'key' => 'product_id'
			],
			'Watch' => [
				'entity' => 'DBTech\eCommerce:CategoryWatch',
				'type' => self::TO_MANY,
				'conditions' => 'category_id',
				'key' => 'user_id'
			]
		];
		$structure->options = [
			'delete_products' => true
		];
		$structure->withAliases = [
			'api' => []
		];
		
		static::addCategoryTreeStructureElements($structure, [
			'breadcrumb_json' => true
		]);

		return $structure;
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('DBTech\eCommerce:Category');
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
}