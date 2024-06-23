<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\AbstractCategoryTree;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $category_id
 * @property string $title
 * @property string $og_title
 * @property string $meta_title
 * @property string $description
 * @property string $meta_description
 * @property string $content_image_url
 * @property string $content_message
 * @property string $content_title
 * @property string $content_term
 * @property int $display_order
 * @property int $parent_category_id
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $item_count
 * @property int $featured_count
 * @property int $last_item_date
 * @property string $last_item_title
 * @property int $last_item_id
 * @property int $thread_node_id
 * @property int $thread_prefix_id
 * @property int $thread_set_item_tags
 * @property bool $autopost_review
 * @property bool $autopost_update 
 * @property string $title_s1
 * @property string $title_s2
 * @property string $title_s3
 * @property string $title_s4
 * @property string $title_s5
 * @property string $title_s6
 * @property string $description_s1
 * @property string $description_s2
 * @property string $description_s3
 * @property string $description_s4
 * @property string $description_s5
 * @property string $description_s6
 * @property string $editor_s1
 * @property string $editor_s2
 * @property string $editor_s3
 * @property string $editor_s4
 * @property string $editor_s5
 * @property string $editor_s6
 * @property string $min_message_length_s1
 * @property string $min_message_length_s2
 * @property string $min_message_length_s3
 * @property string $min_message_length_s4
 * @property string $min_message_length_s5
 * @property string $min_message_length_s6
 * @property bool $allow_comments
 * @property bool $allow_ratings
 * @property string $review_voting
 * @property int $require_review
 * @property bool $allow_items
 * @property bool $allow_contributors
 * @property bool $allow_self_join_contributors
 * @property int $max_allowed_contributors
 * @property int $style_id
 * @property array $breadcrumb_data
 * @property array $prefix_cache
 * @property int $default_prefix_id
 * @property bool $require_prefix
 * @property array $field_cache
 * @property array $review_field_cache
 * @property array $update_field_cache
 * @property bool $allow_anon_reviews
 * @property bool $allow_author_rating
 * @property bool $allow_pros_cons
 * @property int $min_tags
 * @property array $default_tags
 * @property bool $allow_poll
 * @property bool $allow_location
 * @property bool $require_location
 * @property bool $allow_business_hours
 * @property bool $require_item_image
 * @property string $layout_type
 * @property string $item_list_order
 * @property array $map_options
 * @property bool $display_items_on_index
 * @property bool $expand_category_nav
 * @property bool $display_location_on_list
 * @property string $location_on_list_display_type
 * @property string $allow_index
 * @property array $index_criteria
 * 
 * GETTERS
 * @property \XF\Mvc\Entity\ArrayCollection $prefixes
 * @property \XF\Draft $draft_item
 *
 * RELATIONS
 * @property \XF\Entity\Forum $ThreadForum
 * @property \XenAddons\Showcase\Entity\Item $LastItem
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\CategoryWatch[] $Watch
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Draft[] $DraftItems
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\PermissionCacheContent[] $Permissions
 */
class Category extends AbstractCategoryTree implements \XF\Entity\LinkableInterface
{
	protected $_viewableDescendants = [];

	public function canView(&$error = null)
	{
		return $this->hasPermission('view'); 
	}
	
	public function canViewCategoryMap()
	{
		return $this->hasPermission('viewMultiMarkerMaps'); 
	}

	public function canViewDeletedItems()
	{
		return $this->hasPermission('viewDeleted');
	}

	public function canViewModeratedItems()
	{
		return $this->hasPermission('viewModerated');
	}

	public function canEditTags(Item $item = null, &$error = null)
	{
		if (!$this->app()->options()->enableTagging)
		{
			return false;
		}

		$visitor = \XF::visitor();

		// if no item, assume will be owned by this person
		if (!$item || $item->user_id == $visitor->user_id)
		{
			if ($this->hasPermission('tagOwnItem'))
			{
				return true;
			}
		}

		return (
			$this->hasPermission('tagAnyItem')
			|| $this->hasPermission('manageAnyTag')
		);
	}

	public function canUseInlineModeration(&$error = null)
	{
		return $this->hasPermission('inlineMod');
	}

	public function canUploadAndManageItemAttachments()
	{
		return $this->hasPermission('uploadItemAttach');
	}
	
	public function canUploadAndManagePageAttachments()
	{
		return $this->hasPermission('uploadItemAttach');
	}
	
	public function canUploadAndManageUpdateImages()
	{
		return $this->hasPermission('uploadItemAttach');
	}
	
	public function canUploadAndManageReviewImages()
	{
		return $this->hasPermission('uploadReviewAttach');
	}
	
	public function canUploadAndManageCommentImages()
	{
		return $this->hasPermission('uploadCommentAttach');
	}
	
	public function canUploadItemVideos()
	{
		return $this->hasPermission('uploadItemVideo');
	}
	
	public function canUploadUpdateVideos()
	{
		return $this->hasPermission('uploadUpdateVideo');
	}
	
	public function canUploadReviewVideos()
	{
		return $this->hasPermission('uploadReviewVideo');
	}
	
	public function canUploadCommentVideos()
	{
		return $this->hasPermission('uploadCommentVideo');
	}

	public function canAddItem(&$error = null)
	{
		if (!\XF::visitor()->user_id || !$this->hasPermission('add'))
		{
			return false;
		}
		
		$hasAllowedTypes = (
			$this->allow_items
		);
		if (!$hasAllowedTypes)
		{
			$error = \XF::phraseDeferred('xa_sc_category_not_allow_new_items');
			return false;
		}

		$maxItemCount = $this->hasPermission('maxItemCount');
		$userItemCount = \XF::visitor()->xa_sc_item_count;
		
		if ($maxItemCount == -1 || $maxItemCount == 0) // unlimited NOTE: in this particular case, we want 0 to count as inlimited. 
		{
			return true;
		}
		
		if ($userItemCount < $maxItemCount)
		{
			return true;
		}

		return false;
	}

	public function canCreatePoll(&$error = null)
	{
		return $this->allow_poll;
	}
	
	public function canWatch(&$error = null)
	{
		return (\XF::visitor()->user_id ? true : false);
	}

	public function hasPermission($permission)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor->hasShowcaseItemCategoryPermission($this->category_id, $permission);
	}

	public function getViewableDescendants()
	{
		$userId = \XF::visitor()->user_id;
		if (!isset($this->_viewableDescendants[$userId]))
		{
			$viewable = $this->repository('XenAddons\Showcase:Category')->getViewableCategories($this);
			$this->_viewableDescendants[$userId] = $viewable->toArray();
		}

		return $this->_viewableDescendants[$userId];
	}

	public function cacheViewableDescendents(array $descendents, $userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}

		$this->_viewableDescendants[$userId] = $descendents;
	}

	/**
	 * @return \XF\Draft
	 */
	public function getDraftItem()
	{
		return \XF\Draft::createFromEntity($this, 'DraftItems');
	}
	
	public function getMaxAllowedAttachmentsPerItem()
	{
		return $this->hasPermission('maxAttachPerItem');
	}

	public function getUsablePrefixes($forcePrefix = null)
	{
		$prefixes = $this->prefixes;

		if ($forcePrefix instanceof ItemPrefix)  
		{
			$forcePrefix = $forcePrefix->prefix_id;
		}

		$prefixes = $prefixes->filter(function($prefix) use ($forcePrefix)
		{
			if ($forcePrefix && $forcePrefix == $prefix->prefix_id)
			{
				return true;
			}
			return $this->isPrefixUsable($prefix);
		});

		return $prefixes->groupBy('prefix_group_id');
	}

	public function getPrefixesGrouped()
	{
		return $this->prefixes->groupBy('prefix_group_id');
	}

	/**
	 * @return \XF\Mvc\Entity\ArrayCollection
	 */
	public function getPrefixes()
	{
		if (!$this->prefix_cache)
		{
			return $this->_em->getEmptyCollection();
		}

		$prefixes = $this->finder('XenAddons\Showcase:ItemPrefix')
			->where('prefix_id', $this->prefix_cache)
			->order('materialized_order')
			->fetch();

		return $prefixes;
	}

	public function isPrefixUsable($prefix, \XF\Entity\User $user = null)
	{
		if (!$this->isPrefixValid($prefix))
		{
			return false;
		}

		if (!($prefix instanceof ItemPrefix))
		{
			$prefix = $this->em()->find('XenAddons\Showcase:ItemPrefix', $prefix);
			if (!$prefix)
			{
				return false;
			}
		}

		return $prefix->isUsableByUser($user);
	}

	public function isPrefixValid($prefix)
	{
		if ($prefix instanceof ItemPrefix)
		{
			$prefix = $prefix->prefix_id;
		}

		return (!$prefix || isset($this->prefix_cache[$prefix]));
	}

	public function isSearchEngineIndexable() //: bool
	{
		if ($this->allow_index == 'deny')
		{
			return false;
		}
	
		return true;
	}
	
	public function getNewItem()
	{
		$item = $this->_em->create('XenAddons\Showcase:Item');
		$item->category_id = $this->category_id;

		return $item;
	}

	public function getNewItemState(Item $item = null)
	{
		$visitor = \XF::visitor();

		if ($visitor->user_id && $this->hasPermission('approveUnapprove'))
		{
			return 'visible';
		}

		if (!$this->hasPermission('submitWithoutApproval'))  
		{
			return 'moderated';
		}
		
		return 'visible';
	}
	
	public function getCategoryContentImageThumbnailUrlFull()
	{
		$baseUrl = \XF::app()->request()->getFullBasePath();
		$imagePath = "/" . $this->content_image_url;
		$thumbnailUrl = $baseUrl . $imagePath;
	
		return $thumbnailUrl;
	}

	public function getBreadcrumbs($includeSelf = true, $linkType = 'public')
	{
		if ($linkType == 'public')
		{
			$link = 'showcase/categories';
		}
		else
		{
			$link = 'xa-sc/categories';
		}
		return $this->_getBreadcrumbs($includeSelf, $linkType, $link);
	}

	public function getCategoryListExtras()
	{
		return [
			'item_count' => $this->item_count,
			'last_item_date' => $this->last_item_date,
			'last_item_title' => $this->last_item_title,
			'last_item_id' => $this->last_item_id
		];
	}

	public function itemAdded(Item $item)
	{
		$this->item_count++;

		if ($item->last_update >= $this->last_item_date)
		{
			$this->last_item_date = $item->last_update;
			$this->last_item_title = $item->title;
			$this->last_item_id = $item->item_id;
		}

		if ($item->Featured)
		{
			$this->featured_count++;
		}
	}

	public function itemDataChanged(Item $item)
	{
		if ($item->isChanged(['last_update', 'title']))
		{
			if ($item->last_update >= $this->last_item_date)
			{
				$this->last_item_date = $item->last_update;
				$this->last_item_title = $item->title;
				$this->last_item_id = $item->item_id;
			}
			else if ($item->getExistingValue('last_update') == $this->last_item_date)
			{
				$this->rebuildLastItem();
			}
		}
	}

	public function itemRemoved(Item $item)
	{
		$this->item_count--;

		if ($item->last_update == $this->last_item_date)
		{
			$this->rebuildLastItem();
		}

		if ($item->Featured)
		{
			$this->featured_count--;
		}
	}

	public function rebuildCounters()
	{
		$counters = $this->db()->fetchRow("
			SELECT COUNT(*) AS item_count
			FROM xf_xa_sc_item
			WHERE category_id = ?
				AND item_state = 'visible'
		", $this->category_id);

		$this->item_count = $counters['item_count'];

		$this->featured_count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_item_feature AS feature
				INNER JOIN xf_xa_sc_item AS item ON (item.item_id = feature.item_id)
			WHERE item.category_id = ?
				AND item.item_state = 'visible'
		", $this->category_id);

		$this->rebuildLastItem();
	}

	public function rebuildLastItem()
	{
		$item = $this->db()->fetchRow("
			SELECT *
			FROM xf_xa_sc_item
			WHERE category_id = ?
				AND item_state = 'visible'
			ORDER BY last_update DESC
			LIMIT 1
		", $this->category_id);
		if ($item)
		{
			$this->last_item_date = $item['last_update'];
			$this->last_item_title = $item['title'];
			$this->last_item_id = $item['item_id'];
		}
		else
		{
			$this->last_item_date = 0;
			$this->last_item_title = '';
			$this->last_item_id = 0;
		}
	}

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
				else if ($this->thread_prefix_id && !$this->ThreadForum->isPrefixValid($this->thread_prefix_id))
				{
					$this->thread_prefix_id = 0;
				}
			}
		}
	}

	protected function _postDelete()
	{
		$db = $this->db();

		$db->delete('xf_xa_sc_category_field', 'category_id = ?', $this->category_id);
		$db->delete('xf_xa_sc_category_prefix', 'category_id = ?', $this->category_id);
		$db->delete('xf_xa_sc_category_review_field', 'category_id = ?', $this->category_id);
		$db->delete('xf_xa_sc_category_update_field', 'category_id = ?', $this->category_id);
		$db->delete('xf_xa_sc_category_watch', 'category_id = ?', $this->category_id);

		if ($this->getOption('delete_items'))
		{
			$this->app()->jobManager()->enqueueUnique('xa_scCategoryDelete' . $this->category_id, 'XenAddons\Showcase:CategoryDelete', [
				'category_id' => $this->category_id
			]);
		}
	}
	
	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
	{
		$route = ($canonical ? 'canonical:' : '') . 'showcase/categories';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}
	
	public function getContentPublicRoute()
	{
		return 'showcase/categories';
	}
	
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('xa_sc_item_category_x', [
			'title' => $this->title
		]);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_category';
		$structure->shortName = 'XenAddons\Showcase:Category';
		$structure->primaryKey = 'category_id';
		$structure->contentType = 'sc_category';
		$structure->columns = [
			'category_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'title' => ['type' => self::STR, 'maxLength' => 100,
				'required' => 'please_enter_valid_title'
			],
			'og_title' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'meta_title' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'description' => ['type' => self::STR, 
				'default' => '',
				'censor' => true
			],
			'meta_description' => ['type' => self::STR, 'maxLength' => 320,
				'default' => '',
				'censor' => true
			],	
			'content_image_url' =>['type' => self::STR, 'default' => ''],
			'content_message' =>['type' => self::STR, 'default' => ''],
			'content_title' =>['type' => self::STR, 'default' => ''],
			'content_term' =>['type' => self::STR, 'default' => ''],
			'item_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
			'featured_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
			'last_item_date' => ['type' => self::UINT, 'default' => 0],
			'last_item_title' => ['type' => self::STR, 'maxLength' => 150, 
				'default' => '', 
				'censor' => true
			],
			'last_item_id' => ['type' => self::UINT, 'default' => 0],
			'thread_node_id' => ['type' => self::UINT, 'default' => 0],
			'thread_prefix_id' => ['type' => self::UINT, 'default' => 0],
			'thread_set_item_tags' => ['type' => self::BOOL, 'default' => false],
			'autopost_review' => ['type' => self::BOOL, 'default' => false],
			'autopost_update' => ['type' => self::BOOL, 'default' => false],
			'title_s1' => ['type' => self::STR, 'maxLength' => 100, 
				'required' => 'xa_sc_please_enter_valid_section_1_title',
				'censor' => true
			],
			'title_s2' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'title_s3' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'title_s4' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'title_s5' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'title_s6' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'description_s1' => ['type' => self::STR, 
				'default' => '',
				'censor' => true
			],
			'description_s2' => ['type' => self::STR, 
				'default' => '',
				'censor' => true
			],
			'description_s3' => ['type' => self::STR, 
				'default' => '',
				'censor' => true
			],
			'description_s4' => ['type' => self::STR, 
				'default' => '',
				'censor' => true
			],
			'description_s5' => ['type' => self::STR, 
				'default' => '',
				'censor' => true
			],
			'description_s6' => ['type' => self::STR, 
				'default' => '',
				'censor' => true
			],
			'editor_s1' => ['type' => self::BOOL, 'default' => true],
			'editor_s2' => ['type' => self::BOOL, 'default' => false],
			'editor_s3' => ['type' => self::BOOL, 'default' => false],
			'editor_s4' => ['type' => self::BOOL, 'default' => false],
			'editor_s5' => ['type' => self::BOOL, 'default' => false],
			'editor_s6' => ['type' => self::BOOL, 'default' => false],
			'min_message_length_s1' => ['type' => self::UINT, 'default' => 0],
			'min_message_length_s2' => ['type' => self::UINT, 'default' => 0],
			'min_message_length_s3' => ['type' => self::UINT, 'default' => 0],
			'min_message_length_s4' => ['type' => self::UINT, 'default' => 0],
			'min_message_length_s5' => ['type' => self::UINT, 'default' => 0],
			'min_message_length_s6' => ['type' => self::UINT, 'default' => 0],
			'allow_comments' => ['type' => self::BOOL, 'default' => false],
			'allow_ratings' => ['type' => self::UINT, 'default' => 0],
			'review_voting' =>['type' => self::STR, 'default' => ''],
			'require_review' => ['type' => self::BOOL, 'default' => false],			
			'allow_items' => ['type' => self::BOOL, 'default' => false],
			'allow_contributors' => ['type' => self::BOOL, 'default' => false],
			'allow_self_join_contributors' => ['type' => self::BOOL, 'default' => false],
			'max_allowed_contributors' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'max' => 100],
			'style_id' => ['type' => self::UINT, 'default' => 0],
			'prefix_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
			'default_prefix_id' => ['type' => self::UINT, 'default' => 0],			
			'require_prefix' => ['type' => self::BOOL, 'default' => false],
			'field_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
			'review_field_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
			'update_field_cache' => ['type' => self::JSON_ARRAY, 'default' => []],			
			'allow_anon_reviews' => ['type' => self::BOOL, 'default' => false],
			'allow_author_rating' => ['type' => self::BOOL, 'default' => false],
			'allow_pros_cons' => ['type' => self::BOOL, 'default' => false],
			'min_tags' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'max' => 100],
			'default_tags' =>['type' => self::STR, 'default' => ''],
			'allow_poll' => ['type' => self::BOOL, 'default' => false],
			'allow_location' => ['type' => self::BOOL, 'default' => false],
			'require_location' => ['type' => self::BOOL, 'default' => false],
			'allow_business_hours' => ['type' => self::BOOL, 'default' => false],
			'require_item_image' => ['type' => self::BOOL, 'default' => false],
			'layout_type' =>['type' => self::STR, 'default' => ''],
			'item_list_order' =>['type' => self::STR, 'default' => ''],
			'map_options' => ['type' => self::JSON_ARRAY, 'default' => []],
			'display_items_on_index' => ['type' => self::BOOL, 'default' => true],
			'expand_category_nav' => ['type' => self::BOOL, 'default' => false],
			'display_location_on_list' => ['type' => self::BOOL, 'default' => false],
			'location_on_list_display_type' =>['type' => self::STR, 'default' => ''],
			'allow_index' => ['type' => self::STR, 'default' => 'allow',
				'allowedValues' => ['allow', 'deny', 'criteria']
			],
			'index_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->getters = [
			'prefixes' => true,
			'draft_item' => true
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
			'LastItem' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => [
					['item_id', '=', '$last_item_id']
				],
				'primary' => true,
				'with' => ['LastComment, LastCommenter']
			],
			'Watch' => [
				'entity' => 'XenAddons\Showcase:CategoryWatch',
				'type' => self::TO_MANY,
				'conditions' => 'category_id',
				'key' => 'user_id'
			],
			'DraftItems' => [
				'entity'     => 'XF:Draft',
				'type'       => self::TO_MANY,
				'conditions' => [
					['draft_key', '=', 'xa-sc-category-', '$category_id']
				],
				'key' => 'user_id'
			]
		];
		$structure->options = [
			'delete_items' => true
		];

		static::addCategoryTreeStructureElements($structure, [
			'breadcrumb_json' => true
		]);

		return $structure;
	}
}