<?php

namespace Z61\Classifieds\Entity;

use XF\Draft;
use XF\Entity\AbstractCategoryTree;
use XF\Entity\Forum;
use XF\Entity\Phrase;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null category_id
 * @property string title
 * @property string description
 * @property int listing_count
 * @property int featured_count
 * @property int|null node_id
 * @property int thread_prefix_id
 * @property array field_cache
 * @property array prefix_cache
 * @property bool moderate_listings
 * @property bool allow_paid
 * @property bool paid_feature_enable
 * @property int paid_feature_days
 * @property float price
 * @property string currency
 * @property int last_listing_id
 * @property string last_listing_title
 * @property int last_listing_user_id
 * @property int last_listing_prefix_id
 * @property int last_listing_date
 * @property string last_listing_username
 * @property int expiration_days
 * @property array payment_profile_ids
 * @property array listing_type_ids
 * @property array condition_ids
 * @property array package_ids
 * @property bool contact_conversation
 * @property bool contact_email
 * @property bool contact_custom
 * @property int parent_category_id
 * @property int display_order
 * @property int lft
 * @property int rgt
 * @property int depth
 * @property array breadcrumb_data
 * @property bool location_enable
 * @property bool require_listing_image
 * @property string layout_type
 * @property bool require_sold_user
 * @property bool exclude_expired
 * @property string phrase_listing_type
 * @property string phrase_listing_condition
 * @property string phrase_listing_price
 * @property string listing_template
 *
 * GETTERS
 * @property \XF\Draft draft_listing
 * @property \XF\Mvc\Entity\ArrayCollection prefixes
 * @property mixed expiration_date
 * @property mixed listing_types
 * @property mixed conditions
 * @property mixed packages
 * @property Phrase Listing
 *
 * RELATIONS
 * @property \XF\Entity\Forum Forum
 * @property \XF\Entity\Draft[] DraftListings
 * @property \Z61\Classifieds\Entity\CategoryWatch[] Watch
 * @property \Z61\Classifieds\Entity\CategoryRead[] Read
 * @property \XF\Entity\PermissionCacheContent[] Permissions
 */
class Category extends AbstractCategoryTree
{
    protected $_viewableDescendants = [];

    public function canApproveUnapprove(&$error = null)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        return $visitor->user_id && $visitor->hasClassifiedsCategoryPermission($this->category_id, 'approveUnapprove');
    }

    public function canView(&$error = null)
    {
        return $this->hasPermission('view');
    }

    public function canViewDeletedListings()
    {
        return $this->hasPermission('viewDeleted');
    }

    public function canViewModeratedListings()
    {
        return $this->hasPermission('viewModerated');
    }

    public function canAddListing(&$error = null)
    {
        return (\XF::visitor()->user_id && $this->hasPermission('add'));
    }

    public function canUploadAndManageAttachments()
    {
        return $this->hasPermission('uploadAttachment');
    }

    public function canWatch(&$error = null)
    {
        return (\XF::visitor()->user_id ? true : false);
    }

    protected function _postDelete()
    {
        $db = $this->db();
        $db->delete('xf_z61_classifieds_category_field', 'category_id = ?', $this->category_id);
        $db->delete('xf_z61_classifieds_category_watch', 'category_id = ?', $this->category_id);
        if ($this->getOption('delete_listings'))
        {
            $this->app()->jobManager()->enqueueUnique('z61ClassifiedsCategoryDelete.' . $this->category_id, 'Z61\Classifieds:CategoryDelete', [
                'category_id' => $this->category_id
            ]);
        }
    }


    public function canEditTags(Listing $listing = null, &$error = null)
    {
        if (!$this->app()->options()->enableTagging)
        {
            return false;
        }

        if ($listing)
        {
            if (!$listing->listing_open && !$listing->canLockUnlock())
            {
                $error = \XF::phraseDeferred('z61_classifieds_you_may_not_perform_this_action_because_listing_is_closed');

                return false;
            }
        }

        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();

        if (!$listing || $listing->user_id == $visitor->user_id)
        {
            if ($visitor->hasClassifiedsCategoryPermission($this->category_id, 'tagOwnListing'))
            {
                return true;
            }
        }

        if (
            $visitor->hasClassifiedsCategoryPermission($this->category_id, 'tagAnyListing')
            || $visitor->hasClassifiedsCategoryPermission($this->category_id, 'manageAnyTag')
        )
        {
            return true;
        }

        return false;
    }

    public function canPurchaseFeature()
    {
        return ($this->paid_feature_enable && $this->paid_feature_days > 0 && !empty($this->payment_profile_ids));
    }

    public function hasPermission($permission)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();

        return $visitor->hasClassifiedsCategoryPermission($this->category_id, $permission);
    }

    public function isPrefixValid($prefix)
    {
        if ($prefix instanceof ListingPrefix)
        {
            $prefix = $prefix->prefix_id;
        }

        return (!$prefix || isset($this->prefix_cache[$prefix]));
    }

    public function isPrefixUsable($prefix, \XF\Entity\User $user = null)
    {
        if (!$this->isPrefixValid($prefix))
        {
            return false;
        }

        if (!($prefix instanceof ListingPrefix))
        {
            $prefix = $this->em()->find('Z61\Classifieds:ListingPrefix', $prefix);
            if (!$prefix)
            {
                return false;
            }
        }

        return $prefix->isUsableByUser($user);
    }

    public function isWatched()
    {
        return isset($this->Watch[\XF::visitor()->user_id]);
    }

    public function getExpirationDate()
    {
        return strtotime("+ $this->expiration_days days", \XF::$time);
    }

    /**
     * @return \XF\Draft
     */
    public function getDraftListing()
    {
        return \XF\Draft::createFromEntity($this, 'DraftListings');
    }

    public function getCategoryListExtras()
    {
        return [
            'listing_count' => $this->listing_count,
            'last_listing_date' => $this->last_listing_date,
            'last_listing_title' => $this->last_listing_title,
            'last_listing_id' => $this->last_listing_id
        ];
    }

    public function getNewContentState(Listing $listing = null)
    {
        $visitor = \XF::visitor();

        if ($visitor->user_id && $visitor->hasContentPermission('classifieds_listing', $this->category_id, 'approveUnapprove'))
        {
            return 'visible';
        }

        if (!$visitor->hasPermission('classifieds', 'submitWithoutApproval'))
        {
            return 'moderated';
        }

        if ($listing)
        {
            return $this->moderate_listings ? 'moderated' : 'visible';
        }

        return 'moderated';
    }

    public function getNewListing()
    {
        $listing = $this->_em->create('Z61\Classifieds:Listing');
        $listing->set('category_id', $this->category_id);

        return $listing;
    }

    public function getBreadcrumbs($includeSelf = true, $linkType = 'public')
    {
        $link = 'classifieds/categories';

        return $this->_getBreadcrumbs($includeSelf, $linkType, $link);
    }

    /**
     * @return \XF\Phrase|string
     */
    public function getCostPhrase()
    {
        $cost = $this->app()->data('XF:Currency')->languageFormat($this->price, $this->currency);
        $phrase = $cost;

        if ($this->paid_feature_days)
        {
            if ($this->paid_feature_days > 1)
            {
                $phrase = \XF::phrase("x_for_y_days", [
                    'cost' => $cost,
                    'length' => $this->paid_feature_days
                ]);
            }
            else
            {
                $phrase = \XF::phrase("x_for_one_day", [
                    'cost' => $cost
                ]);
            }
        }

        return $phrase;
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

        $prefixes = $this->finder('Z61\Classifieds:ListingPrefix')
            ->where('prefix_id', $this->prefix_cache)
            ->order('materialized_order')
            ->fetch();

        return $prefixes;
    }

    public function listingAdded(Listing $listing)
    {
        $this->listing_count++;

        if ($listing->listing_date >= $this->last_listing_date)
        {
            $this->last_listing_id = $listing->listing_id;
            $this->last_listing_date = $listing->listing_date;
            $this->last_listing_user_id = $listing->user_id;
            $this->last_listing_username = $listing->username;
            $this->last_listing_title = $listing->title;
            $this->last_listing_prefix_id = $listing->prefix_id;
        }
    }

    public function getViewableDescendants()
    {
        $userId = \XF::visitor()->user_id;
        if (!isset($this->_viewableDescendants[$userId]))
        {
            $viewable = $this->repository('Z61\Classifieds:Category')->getViewableCategories($this);
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

    public function listingRemoved(Listing $listing)
    {
        $this->listing_count--;
    }

    public function listingDataChanged(Listing $listing)
    {
        $this->rebuildLastListing();
    }

    public function rebuildCounters()
    {
        $counters = $this->db()->fetchRow("
			SELECT COUNT(*) AS listing_count
			FROM xf_z61_classifieds_listing
			WHERE category_id = ?
				AND listing_state = 'visible'
				AND listing_status = 'active'
		", $this->category_id);

        $this->listing_count = $counters['listing_count'];

        $this->featured_count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_z61_classifieds_listing_feature AS feature
				INNER JOIN xf_z61_classifieds_listing AS listing ON (listing.listing_id = feature.listing_id)
			WHERE listing.category_id = ?
				AND listing.listing_state = 'visible'
				AND listing.listing_status = 'active'
		", $this->category_id);

        $this->rebuildLastListing();
    }

    public function rebuildLastListing()
    {
        $listing = $this->db()->fetchRow("
			SELECT *
			FROM xf_z61_classifieds_listing
			WHERE category_id = ?
				AND listing_state = 'visible'
			ORDER BY listing_date DESC
			LIMIT 1
		", $this->category_id);
        if ($listing)
        {
            $this->last_listing_id = $listing['listing_id'];
            $this->last_listing_date = $listing['listing_date'];
            $this->last_listing_user_id = $listing['user_id'];
            $this->last_listing_username = $listing['username'];
            $this->last_listing_title = $listing['title'];
            $this->last_listing_prefix_id = $listing['prefix_id'];
        }
        else
        {
            $this->last_listing_id = 0;
            $this->last_listing_date = 0;
            $this->last_listing_user_id = 0;
            $this->last_listing_username = '';
            $this->last_listing_title = '';
            $this->last_listing_prefix_id = 0;
        }
    }

    public function getUsablePrefixes($forcePrefix = null)
    {
        $prefixes = $this->prefixes;

        if ($forcePrefix instanceof ListingPrefix)
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

    public function getListingTypes()
    {
        $typeFinder = $this->finder('Z61\Classifieds:ListingType');
        $typeFinder->where('listing_type_id', $this->listing_type_ids);
        return $typeFinder->fetch();
    }

    public function getConditions()
    {
        $conditionFinder = $this->finder('Z61\Classifieds:Condition');
        $conditionFinder
	        ->where('condition_id', $this->condition_ids)
            ->where('active', 1);
        return $conditionFinder->fetch();
    }

    public function getPackages()
    {
        $packageFinder = $this->finder('Z61\Classifieds:Package');
        $packageFinder->where('package_id', $this->package_ids);
        return $packageFinder->fetch();
    }

    /**
     * @return \XF\Phrase
     */
    public function getTypePhrase()
    {
        return \XF::phrase($this->phrase_listing_type);
    }

    /**
     * @return \XF\Phrase
     */
    public function getConditionPhrase()
    {
        return \XF::phrase($this->phrase_listing_condition);
    }

    /**
     * @return \XF\Phrase
     */
    public function getPricePhrase()
    {
        return \XF::phrase($this->phrase_listing_price);
    }

    protected function _preSave()
    {
        if ($this->isChanged(['node_id', 'thread_prefix_id']))
        {
            if (!$this->node_id)
            {
                $this->thread_prefix_id = 0;
            }
            else
            {
                if (!$this->Forum)
                {
                    $this->node_id = 0;
                    $this->thread_prefix_id = 0;
                }
                else if ($this->thread_prefix_id && !$this->Forum->isPrefixValid($this->thread_prefix_id))
                {
                    $this->thread_prefix_id = 0;
                }
            }
        }
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_category';
        $structure->shortName = 'Z61\Classifieds:Category';
        $structure->primaryKey = 'category_id';
        $structure->contentType = 'classifieds_category';
        $structure->columns = [
            'category_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'title' => ['type' => self::STR, 'maxLength' => 100,
                'required' => 'please_enter_valid_title'
            ],
            'description' => ['type' => self::STR, 'default' => ''],
            'listing_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
            'featured_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
            'node_id' => ['type' => self::UINT, 'nullable' => true, 'verify' => 'verifyNodeId', 'default' => null],
            'thread_prefix_id' => ['type' => self::UINT, 'default' => 0],
            'field_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
            'prefix_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
            'moderate_listings' => ['type' => self::BOOL, 'default' => false],
            'allow_paid' => ['type' => self::BOOL, 'default' => true],
            'paid_feature_enable' => ['type' => self::BOOL, 'default' => true],
            'paid_feature_days' => ['type' => self::UINT, 'default' => 30],
            'price' => ['type' => self::FLOAT, 'default' => 0, 'max' => 99999999, 'min' => 0],
            'currency' => ['type' => self::STR, 'default' => '', 'maxLength' => 3],
            'last_listing_id' => ['type' => self::UINT, 'default' => 0],
            'last_listing_title' => ['type' => self::STR, 'default' => '', 'maxLength' => 100,
                'censor' => true
            ],
            'last_listing_user_id' => ['type' => self::UINT, 'default' => 0],
            'last_listing_prefix_id' => ['type' => self::UINT, 'default' => 0],
            'last_listing_date' => ['type' => self::UINT, 'default' => null],
            'last_listing_username' => ['type' => self::STR, 'default' => '', 50],
            'payment_profile_ids' => ['type' => self::LIST_COMMA,
                'verify' => 'verifyPaymentProfileIds',
                'list' => ['type' => 'posint', 'unique' => true, 'sort' => SORT_NUMERIC]
            ],
            'listing_type_ids' => ['type' => self::LIST_COMMA,
                'verify' => 'verifyListingTypeIds',
                'list' => ['type' => 'posint', 'unique' => true, 'sort' => SORT_NUMERIC]
            ],
            'condition_ids' => ['type' => self::LIST_COMMA,
                'list' => ['type' => 'posint', 'unique' => true, 'sort' => SORT_NUMERIC]
            ],
            'package_ids' => ['type' => self::LIST_COMMA,
                //'verify' => 'verifyPackageIds',
                'list' => ['type' => 'posint', 'unique' => true, 'sort' => SORT_NUMERIC]
            ],
            'contact_conversation' => ['type' => self::BOOL, 'default' => true],
            'contact_email' => ['type' => self::BOOL, 'default' => false],
            'contact_custom' => ['type' => self::BOOL, 'default' => true],
            'location_enable' => ['type' => self::BOOL, 'default' => true],
            'require_listing_image' => ['type' => self::BOOL, 'default' => false],
            'layout_type' => ['type' => self::STR, 'default' => 'list_view', 'maxLength' => 20],
            'require_sold_user' => ['type' => self::BOOL, 'default' => true],
            'replace_forum_action_button' => ['type' => self::BOOL, 'default' => true],
            'exclude_expired' => ['type' => self::BOOL, 'default' => true],
            'phrase_listing_type' => ['type' => self::STR, 'default' => 'z61_classifieds_type', 'verify' => 'verifyPhrase', 'required' => true],
            'phrase_listing_condition' => ['type' => self::STR, 'default' => 'z61_classifieds_condition', 'verify' => 'verifyPhrase', 'required' => true ],
            'phrase_listing_price' => ['type' => self::STR, 'default' => 'price', 'verify' => 'verifyPhrase', 'required' => true],
            'listing_template' => ['type' => self::STR, 'default' => '', 'censor' => true],
        ];
        $structure->relations = [
            'Forum' => [
                'entity' => 'XF:Forum',
                'type' => self::TO_ONE,
                'conditions' => 'node_id',
                'primary' => true
            ],
            'DraftListings' => [
                'entity'     => 'XF:Draft',
                'type'       => self::TO_MANY,
                'conditions' => [
                    ['draft_key', '=', 'classifieds-category-', '$category_id']
                ],
                'key'        => 'user_id'
            ],
            'Watch' => [
                'entity' => 'Z61\Classifieds:CategoryWatch',
                'type' => self::TO_MANY,
                'conditions' => 'category_id',
                'key' => 'user_id'
            ],
            'Read' => [
                'entity' => 'Z61\Classifieds:CategoryRead',
                'type' => self::TO_MANY,
                'conditions' => 'category_id',
                'key' => 'user_id'
            ],
        ];
        $structure->getters = [
            'draft_listing' => true,
            'prefixes' => true,
            'expiration_date' => true,
            'listing_types' => true,
            'conditions' => true,
            'packages' => true,
            'cost_phrase' => true,
            'price_phrase' => true,
            'type_phrase' => true,
            'condition_phrase' => true
        ];
        $structure->options = [
            'delete_listings' => true
        ];

        static::addCategoryTreeStructureElements($structure, [
            'breadcrumb_json' => true
        ]);

        return $structure;
    }

    protected function verifyPaymentProfileIds(&$choice)
    {
        if ($this->paid_feature_enable && empty($choice))
        {
            return false;
        }

        return true;
    }

    protected function verifyListingTypeIds(&$choice)
    {
        if (empty($choice))
        {
            $this->error(\XF::phrase('z61_classifieds_please_select_at_least_one_listing_type'));
            return false;
        }

        return true;
    }

    protected function verifyNodeId(&$nodeId)
    {
        /** @var \Z61\Classifieds\XF\Entity\Forum $forum */
        $forum = $this->_em->find('XF:Forum', $nodeId);
        return !empty($forum);
    }

    protected function verifyPhrase(&$phrase)
    {
    	$temp = $this->_em->findOne('XF:Phrase', ['title' => $phrase]);
        return !empty($temp->title);
    }
}