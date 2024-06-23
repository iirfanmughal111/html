<?php

namespace DBTech\eCommerce\Entity;

use XF\BbCode\RenderableContentInterface;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Entity\LinkableInterface;
use XF\Entity\BookmarkTrait;
use XF\Entity\ReactionTrait;

/**
 * COLUMNS
 * @property int|null $product_id
 * @property string $title
 * @property int $parent_product_id
 * @property int $product_category_id
 * @property string $product_state
 * @property int $creation_date
 * @property int $last_update
 * @property int $latest_version_id
 * @property bool $is_paid
 * @property bool $is_featured
 * @property bool $is_discountable
 * @property bool $is_listed
 * @property bool $welcome_email
 * @property bool $is_all_access
 * @property array $all_access_group_ids
 * @property int $user_id
 * @property string $username
 * @property int $ip_id
 * @property int $warning_id
 * @property string $warning_message
 * @property array $requirements
 * @property string $description_full
 * @property string $product_specification
 * @property string $copyright_info
 * @property int $attach_count
 * @property string $product_type
 * @property array $product_type_data
 * @property string $license_prefix
 * @property array $product_versions
 * @property bool $has_demo
 * @property array $extra_group_ids
 * @property array $temporary_extra_group_ids
 * @property int $support_node_id
 * @property int $thread_node_id
 * @property int $thread_prefix_id
 * @property int $discussion_thread_id
 * @property array $field_cache
 * @property array $product_fields_
 * @property array $product_filters
 * @property array $cost_cache
 * @property array $shipping_zones
 * @property int $download_count
 * @property int $full_download_count
 * @property int $rating_count
 * @property int $rating_sum
 * @property float $rating_avg
 * @property float $rating_weighted
 * @property int $release_count
 * @property int $review_count
 * @property int $license_count
 * @property int $purchase_count
 * @property int $icon_date
 * @property string $icon_extension
 * @property int $prefix_id
 * @property array $tags
 * @property int $global_branding_free
 * @property int $branding_free
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 *
 * GETTERS
 * @property string $full_title
 * @property \XF\Phrase $tagline
 * @property \XF\Phrase $description
 * @property string $product_page_url
 * @property \XF\Phrase|float $starting_price
 * @property ProductCost|null $starting_cost
 * @property \XF\CustomField\Set $product_fields
 * @property int $real_release_count
 * @property int $real_review_count
 * @property array $product_download_ids
 * @property array $product_rating_ids
 * @property \XF\Mvc\Entity\AbstractCollection $UserLicenses
 * @property mixed $reactions
 * @property mixed $reaction_users
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTagline
 * @property \XF\Entity\Phrase $MasterDescription
 * @property \DBTech\eCommerce\Entity\Category $Category
 * @property \DBTech\eCommerce\Entity\ProductSale $Sale
 * @property \XF\Entity\User $User
 * @property \XF\Entity\Forum $ThreadForum
 * @property \XF\Entity\Forum $SupportForum
 * @property \XF\Entity\Thread $Discussion
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\PermissionCacheContent[] $Permissions
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductFeatureTemp[] $TempFeatures
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductRating[] $Ratings
 * @property \DBTech\eCommerce\Entity\Product $Parent
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\Product[] $Children
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductCost[] $Costs
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\License[] $Licenses
 * @property \DBTech\eCommerce\Entity\License $AllAccessLicense
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\Download[] $Downloads
 * @property \DBTech\eCommerce\Entity\Download $LatestVersion
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductDownload[] $ProductDownloads
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\DownloadLog[] $DownloadLog
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\PurchaseLog[] $PurchaseLog
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingZoneProductMap[] $ShippingZones
 * @property \DBTech\eCommerce\Entity\ProductWelcomeEmail $WelcomeEmail
 * @property \DBTech\eCommerce\Entity\ProductPrefix $Prefix
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductWatch[] $Watch
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\BookmarkItem[] $Bookmarks
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 */
class Product extends Entity implements LinkableInterface, RenderableContentInterface
{
	use BookmarkTrait;
	use ReactionTrait;

	/** @var int */
	public const RATING_WEIGHTED_THRESHOLD = 10;
	
	/** @var int */
	public const RATING_WEIGHTED_AVERAGE = 3;
	
	
	/**
	 * @return string
	 */
	public function getFullTitle(): string
	{
		if (!$this->parent_product_id)
		{
			return $this->title;
		}
		
		$template = \XF::options()->dbtechEcommerceAddonProductTitle;
		return str_replace(['{title}', '{parent}'], [$this->title, $this->Parent->title], $template);
	}
	
	/**
	 * @return string
	 */
	public function getTaglinePhraseName(): string
	{
		return 'dbtech_ecommerce_product_tag.' . $this->product_id;
	}

	/**
	 * @return string
	 */
	public function getDescriptionPhraseName(): string
	{
		return 'dbtech_ecommerce_product_desc.' . $this->product_id;
	}

	/**
	 * @return \XF\Phrase
	 */
	public function getTagline(): \XF\Phrase
	{
		return \XF::phrase($this->getTaglinePhraseName());
	}

	/**
	 * @return \XF\Phrase
	 */
	public function getDescription(): \XF\Phrase
	{
		return \XF::phrase($this->getDescriptionPhraseName());
	}

	/**
	 * @return \XF\Entity\Phrase
	 */
	public function getMasterTaglinePhrase(): \XF\Entity\Phrase
	{
		$phrase = $this->MasterTagline;
		if (!$phrase)
		{
			/** @var \XF\Entity\Phrase $phrase */
			$phrase = $this->_em->create('XF:Phrase');
			$phrase->title = $this->_getDeferredValue(function (): string
			{
				return $this->getTaglinePhraseName();
			}, 'save');
			$phrase->language_id = 0;
			$phrase->addon_id = '';
		}

		return $phrase;
	}

	/**
	 * @return \XF\Entity\Phrase
	 */
	public function getMasterDescriptionPhrase(): \XF\Entity\Phrase
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
	 * @return \XF\Phrase|null
	 * @throws \Exception
	 */
	public function getProductTypePhrase(): ?\XF\Phrase
	{
		$handler = $this->getHandler();
		return $handler ? $handler->getProductTypePhrase() : null;
	}
	
	/**
	 * @return string
	 */
	public function getProductPageUrl(): string
	{
		return $this->app()->router('public')->buildLink('canonical:dbtech-ecommerce', $this);
	}
	
	/**
	 * @return ProductCost|null
	 */
	public function getStartingCost(): ?ProductCost
	{
		if (empty($this->cost_cache))
		{
			return null;
		}
		
		$costs = $this->cost_cache;
		
		/** @var \DBTech\eCommerce\Entity\ProductCost $cost */
		$cost = $this->em()->instantiateEntity('DBTech\eCommerce:ProductCost', reset($costs));
		
		return $cost;
	}

	/**
	 * @param License|null $license
	 * @param bool $includeDiscounts
	 * @param bool $forDisplay
	 *
	 * @return \XF\Phrase|float
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function getStartingPrice(License $license = null, bool $includeDiscounts = true, bool $forDisplay = false)
	{
		$cost = $this->getStartingCost();
		
		if ($cost === null)
		{
			return $forDisplay ? \XF::phrase('dbtech_ecommerce_free') : 0.00;
		}
		
		return $cost->getPrice($license, $includeDiscounts, $forDisplay);
	}
	
	/**
	 * @param bool $includeDiscounts
	 *
	 * @return float
	 */
	public function getPhysicalPrice(bool $includeDiscounts = true)
	{
		return 0;
	}
	
	/**
	 * @return string
	 */
	public function getShippingZoneList(): string
	{
		$zones = [];
		
		/** @var \DBTech\eCommerce\Entity\ShippingZoneProductMap $zoneProductMap */
		foreach ($this->ShippingZones as $zoneProductMap)
		{
			$zones[] = $zoneProductMap->ShippingZone->title;
		}
		
		return implode(', ', $zones);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canUseInlineModeration(&$error = null): bool
	{
		$visitor = \XF::visitor();
		return ($visitor->user_id && $this->hasPermission('inlineMod'));
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
			&& $this->product_state == 'visible'
		);
	}
	
	/**
	 * @param null $error
	 * @param \XF\Entity\User|null $asUser
	 *
	 * @return bool
	 */
	public function canReport(&$error = null, \XF\Entity\User $asUser = null): bool
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canWarn(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		if ($this->warning_id
			|| !$this->user_id
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$this->hasPermission('warn')
		) {
			return false;
		}
		
		$user = $this->User;
		return ($user && $user->isWarnable());
	}
	
	/**
	 * @param string $permission
	 * @return bool
	 */
	public function hasPermission(string $permission): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!$visitor->hasOption('hasDbEcommerce'))
		{
			return false;
		}
		
		switch ($permission)
		{
			case 'view':
			case 'viewProductAttach':
			case 'purchase':
			case 'download':
			case 'react':
			case 'rate':
				// These are per-product
				return $visitor->hasDbtechEcommerceProductPermission($this->product_id, $permission);

			default:
				// The rest are per-category
				return $visitor->hasDbtechEcommerceCategoryPermission($this->product_category_id, $permission);
		}
	}
	
	/**
	 * @return Product
	 * @throws \InvalidArgumentException
	 */
	public function getNewAddOn(): Product
	{
		/** @var Product $product */
		$product = $this->_em->create('DBTech\eCommerce:Product');
		
		$product->parent_product_id = $this->product_id;
		$product->product_category_id = $this->product_category_id;
		$product->product_type = $this->product_type;
		
		$product->hydrateRelation('Parent', $this);
		
		return $product;
	}
	
	/**
	 * @return License
	 */
	public function getNewLicense(): License
	{
		/** @var License $license */
		$license = $this->_em->create('DBTech\eCommerce:License');
		
		$license->product_id = $this->product_id;
		$license->purchase_date = \XF::$time;
		
		return $license;
	}
	
	/**
	 * @param License|null $license
	 *
	 * @return string
	 */
	public function getNewContentState(License $license = null): string
	{
		return 'visible';
	}
	
	/**
	 * @return bool
	 */
	public function isIgnored(): bool
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}
	
	/**
	 * @return mixed|null
	 */
	public function isVisible(): ?bool
	{
		return ($this->product_state == 'visible');
	}
	
	/**
	 * @return bool
	 */
	public function isAddOn(): bool
	{
		return ($this->parent_product_id != 0);
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function isValidAddOnParent(): bool
	{
		return (
			$this->canAddAddOn()
			|| $this->hasPermission('editAny')
		);
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function isValidAddOnTarget(): bool
	{
		return $this->hasAddonFunctionality() && !$this->isAddOn();
	}

	/**
	 * @return \DBTech\eCommerce\ProductType\AbstractHandler|null
	 * @throws \Exception
	 */
	public function getHandler(): ?\DBTech\eCommerce\ProductType\AbstractHandler
	{
		return $this->getProductRepo()->getProductTypeHandler($this->product_type);
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasLicenseFunctionality(): bool
	{
		$handler = $this->getHandler();
		return $handler && $handler->hasFunctionality('licenses');
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasAddonFunctionality(): bool
	{
		$handler = $this->getHandler();
		return $handler && $handler->hasFunctionality('addons');
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasDownloadFunctionality(): bool
	{
		$handler = $this->getHandler();
		return $handler && $handler->hasFunctionality('downloads');
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasQuantityFunctionality(): bool
	{
		$handler = $this->getHandler();
		return $handler && $handler->hasFunctionality('quantity');
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasShippingFunctionality(): bool
	{
		$handler = $this->getHandler();
		return $handler && $handler->hasFunctionality('shipping');
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasStockFunctionality(): bool
	{
		$handler = $this->getHandler();
		return $handler && $handler->hasFunctionality('stock');
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function hasWeight(): bool
	{
		$handler = $this->getHandler();
		return $handler && $handler->hasFunctionality('weight');
	}
	
	/**
	 * @return bool
	 */
	public function hasViewableDiscussion(): bool
	{
		if (!$this->discussion_thread_id)
		{
			return false;
		}
		
		$thread = $this->Discussion;
		if (!$thread)
		{
			return false;
		}
		
		return $thread->canView();
	}
	
	/**
	 * @return bool
	 */
	public function hasViewableSupportForum(): bool
	{
		if (!$this->support_node_id)
		{
			return false;
		}
		
		$forum = $this->SupportForum;
		if (!$forum)
		{
			return false;
		}
		
		return $forum->canView();
	}



	/**
	 * @param $error
	 *
	 * @return bool
	 */
	public function hasRequiredUserGroups(&$error = null): bool
	{
		$visitor = \XF::visitor();

		if (empty($this->all_access_group_ids))
		{
			return true;
		}

		foreach ($this->all_access_group_ids AS $userGroupId)
		{
			if ($userGroupId == -1)
			{
				continue;
			}

			if (!$visitor->isMemberOf($userGroupId))
			{
				$error = \XF::phraseDeferred('dbtech_ecommerce_do_not_have_all_access_pass');
				return false;
			}
		}

		return true;
	}
	
	/**
	 * @return array
	 */
	public function getExtraFieldTabs(): array
	{
		if (!$this->getValue('product_fields'))
		{
			// if they haven't set anything, we can bail out quickly
			return [];
		}
		
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $this->product_fields;
		$definitionSet = $fieldSet->getDefinitionSet()
			->filterOnly($this->Category->field_cache)
			->filterGroup('new_tab')
			->filterWithValue($fieldSet);
		
		$output = [];
		foreach ($definitionSet AS $fieldId => $definition)
		{
			$output[$fieldId] = $definition->title;
		}
		
		return $output;
	}
	
	/**
	 * @return string
	 */
	public function getFieldEditMode(): string
	{
		$visitor = \XF::visitor();
		
		$isSelf = ($visitor->user_id == $this->user_id || !$this->product_id);
		$isMod = ($visitor->user_id && $this->hasPermission('editAny'));
		
		if ($isMod || !$isSelf)
		{
			return $isSelf ? 'moderator_user' : 'moderator';
		}
		else
		{
			return 'user';
		}
	}

	/**
	 * @return \XF\CustomField\Set
	 * @throws \Exception
	 */
	public function getProductFields(): \XF\CustomField\Set
	{
		$class = 'XF\CustomField\Set';
		$class = $this->app()->extendClass($class);

		/** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
		$fieldDefinitions = $this->app()->container('customFields.dbtechEcommerceProducts');

		return new $class($fieldDefinitions, $this, 'product_fields');
	}

	/**
	 * @return mixed|null|string|string[]
	 * @throws \Exception
	 */
	public function getExpectedThreadTitle()
	{
		$template = '';
		$options = $this->app()->options();
		
		if ($this->product_state != 'visible' && $options->dbtechEcommerceContentDeleteThreadAction['update_title'])
		{
			$template = $options->dbtechEcommerceContentDeleteThreadAction['title_template'];
		}
		
		if (!$template)
		{
			$template = $options->dbtechEcommerceReleaseThreadTitle;
		}
		
		$threadTitle = strtr($template, [
			'{title}' => $this->full_title,
			'{category}' => $this->Category->title,
			'{starting_price}' => $this->getStartingPrice(null, false),
		]);
		return $this->app()->stringFormatter()->wholeWordTrim($threadTitle, 100);
	}
	
	/**
	 * @param array $versions
	 * @return string[]
	 */
	public function getVersionLabels(array $versions): array
	{
		$labels = [];
		foreach ($versions AS $version => $defaultLabel)
		{
			$labels[$version] = $this->getVersionLabel($version);
		}
		return $labels;
	}

	/**
	 * @param string $version
	 * @return \XF\Phrase|string
	 */
	public function getVersionLabel(string $version)
	{
		if (!isset($this->product_versions[$version]))
		{
			return $version;
		}
		
		return \XF::phrase($this->getVersionPhraseName($version));
	}

	/**
	 * @param array $versions
	 * @return array
	 */
	public function getVersionPhraseNames(array $versions): array
	{
		$phraseNames = [];
		foreach ($versions AS $version)
		{
			$phraseNames[$version] = $this->getVersionPhraseName($version);
		}
		return $phraseNames;
	}

	/**
	 * @param string $version
	 * @return string
	 */
	public function getVersionPhraseName(string $version): string
	{
		return 'dbtech_ecommerce_product_version' . '.' . $this->product_id . '_' . $version;
	}

	/**
	 * @param string $version
	 * @return null|Entity
	 */
	public function getMasterVersionPhrase(string $version)
	{
		$versionPhraseName = $this->getVersionPhraseName($version);

		$phrase = $this->finder('XF:Phrase')
			->where('title', $versionPhraseName)
			->fetchOne();

		if (!$phrase)
		{
			/** @var \XF\Entity\Phrase $phrase */
			$phrase = $this->_em->create('XF:Phrase');
			$phrase->title = $versionPhraseName;
			$phrase->language_id = 0;
			$phrase->addon_id = 'DBTech\eCommerce';
		}

		return $phrase;
	}

	/**
	 * @param $context
	 * @param $type
	 * @return array
	 */
	public function getBbCodeRenderOptions($context, $type): array
	{
		return [
			'entity' => $this,
			'user' => $this->User,
			'attachments' => $this->attach_count ? $this->Attachments : [],
			'viewAttachments' => $this->canViewProductImages()
		];
	}
	
	/**
	 * @param bool $includeSelf
	 *
	 * @return array
	 */
	public function getBreadcrumbs(bool $includeSelf = true): array
	{
		$breadcrumbs = $this->Category ? $this->Category->getBreadcrumbs() : [];
		if ($includeSelf && $this->exists())
		{
			$breadcrumbs[] = [
				'href' => $this->app()->router()->buildLink('dbtech-ecommerce', $this),
				'value' => $this->title
			];
		}
		
		return $breadcrumbs;
	}

	/**
	 * @param string|null $sizeCode
	 * @param string $imageType
	 *
	 * @return string
	 */
	public function getAbstractedIconPath(?string $sizeCode = null, string $imageType = 'jpg'): string
	{
		$productId = $this->product_id;

		return sprintf(
			'data://dbtechEcommerce/productIcons/%d/%d.' . $imageType,
			floor($productId / 1000),
			$productId
		);
	}

	/**
	 * @param string|null $sizeCode
	 * @param bool $canonical
	 * @return string|null
	 */
	public function getIconUrl(?string $sizeCode = null, bool $canonical = false): ?string
	{
		$app = $this->app();

		if ($this->icon_date)
		{
			$group = floor($this->product_id / 1000);
			return $app->applyExternalDataUrl(
				"dbtechEcommerce/productIcons/{$group}/{$this->product_id}.{$this->icon_extension}?{$this->icon_date}",
				$canonical
			);
		}
		
		return null;
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canView(&$error = null): bool
	{
		if (!$this->Category)
		{
			return false;
		}
		
		if (!$this->hasPermission('view'))
		{
			return false;
		}
		
		if ($this->parent_product_id
			&& $this->Parent
			&& !$this->Parent->hasPermission('view')
		) {
			return false;
		}

		$visitor = \XF::visitor();

		if ($this->product_state == 'moderated')
		{
			if (
				(!$visitor->user_id || $visitor->user_id != $this->user_id)
				&& !$this->hasPermission('viewModerated')
			) {
				return false;
			}
		}
		elseif ($this->product_state == 'deleted')
		{
			if (!$this->hasPermission('viewDeleted'))
			{
				return false;
			}
		}
		
		return true;
	}

	/**
	 * @param License|null $license
	 * @param null $error
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function canPurchase(License $license = null, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();

		if ($visitor->user_id == $this->user_id)
		{
			return false;
		}

		if (!$this->isVisible())
		{
			return false;
		}

		if (!$this->hasPermission('purchase'))
		{
			return false;
		}

		if ($license)
		{
			if ($license->isLifetime())
			{
				return false;
			}

			if ($license->user_id != $visitor->user_id)
			{
				return false;
			}
		}

		$handler = $this->getHandler();
		if ($handler && !$handler->canPurchase($this))
		{
			return false;
		}

		return true;
	}

	/**
	 * @param License|null $license
	 * @param null $error
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function canPurchaseAllAccess(License $license = null, &$error = null): bool
	{
		if ($license)
		{
			// If someone is viewing within a license context, they can't get All-Access copy
			return false;
		}

		if (!$this->is_all_access || empty($this->all_access_group_ids))
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_this_product_not_part_of_all_access');
			return false;
		}

		if (!$this->hasRequiredUserGroups($error))
		{
			// $error will be populated by this function
			return false;
		}

		if ($this->AllAccessLicense)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_you_already_own_this_license_as_part_of_pass');
			return false;
		}

		return $this->canPurchase($license, $error);
	}

	/**
	 * @param License|null $license
	 * @param null $error
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function canPurchaseAddOns(License $license = null, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if ($visitor->user_id == $this->user_id)
		{
			return false;
		}
		
		if (!$this->isVisible())
		{
			return false;
		}
		
		if (!$this->hasPermission('purchase'))
		{
			return false;
		}
		
		$buyableAddOns = false;
		foreach ($this->Children as $child)
		{
			// Don't include $license here as we're just doing a surface check for whether we have permission
			$buyableAddOns = $buyableAddOns || $child->canPurchase();
		}
		
		if (!$buyableAddOns)
		{
			return false;
		}
		
		if ($license)
		{
			if ($license->user_id != $visitor->user_id)
			{
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @return mixed
	 */
	public function canViewDeletedContent(): bool
	{
		return $this->hasPermission('viewDeleted');
	}
	
	/**
	 * @return bool
	 */
	public function canViewModeratedContent(): bool
	{
		$visitor = \XF::visitor();
		if ($this->hasPermission('viewModerated'))
		{
			return true;
		}
		
		return $visitor->user_id && $this->user_id == $visitor->user_id;
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canDownload(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		return (
			$this->hasPermission('download')
			|| ($visitor->user_id && $this->user_id == $visitor->user_id)
		);
	}

	/**
	 * @param bool $checkDownloadIfRequired
	 * @param null $error
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function canRate(bool $checkDownloadIfRequired = true, &$error = null): bool
	{
		if (!$this->isVisible())
		{
			return false;
		}
		
		if ($this->isAddOn())
		{
			return false;
		}
		
		$visitor = \XF::visitor();
		if (!$visitor->user_id || $visitor->user_id == $this->user_id)
		{
			return false;
		}
		
		if (!$this->hasPermission('rate') || !$this->hasPermission('download'))
		{
			// if you can't download, you can't rate it
			return false;
		}
		
		if (
			$checkDownloadIfRequired
			&& $this->hasDownloadFunctionality()
			&& $this->app()->options()->dbtechEcommerceRequireDownloadToRate
			&& !$this->ProductDownloads[$visitor->user_id]
		) {
			$error = \XF::phraseDeferred('dbtech_ecommerce_you_only_rate_product_downloaded');
			return false;
		}
		
		return true;
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canEdit(&$error = null): bool
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return false;
		}
		
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
		
		return (
			$this->user_id == $visitor->user_id
			&& $this->hasPermission('updateOwn')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canEditIcon(&$error = null): bool
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id)
		{
			if ($this->hasPermission('updateOwn'))
			{
				return true;
			}
			
			if (!$this->icon_date && $this->creation_date > \XF::$time - 3 * 3600)
			{
				// allow an icon to be set shortly after product creation, even if not editable since you can't
				// specify an icon during creation
				return true;
			}
		}
		
		return false;
	}

	/**
	 * @param null $error
	 *
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public function canReleaseUpdate(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		if (
			!$visitor->user_id
			|| !$this->isVisible()
			|| !$this->hasDownloadFunctionality()
		) {
			return false;
		}
		
		return $visitor->user_id == $this->user_id ?
			$this->hasPermission('updateOwn') :
			$this->hasPermission('updateAny');
	}

	/**
	 * @param null $error
	 *
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public function canAddAddOn(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		if (
			!$visitor->user_id
			|| !$this->isVisible()
			|| !$this->hasAddonFunctionality()
			|| $this->isAddOn()
		) {
			return false;
		}
		
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
		
		return (
			$this->user_id == $visitor->user_id
			&& $this->hasPermission('updateOwn')
		);
	}

	/**
	 * @param null $error
	 *
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public function canChangeParent(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		if (
			!$visitor->user_id
			|| !$this->isVisible()
			|| !$this->hasLicenseFunctionality()
			|| !$this->isAddOn()
		) {
			return false;
		}
		
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
		
		return (
			$this->user_id == $visitor->user_id
			&& $this->hasPermission('updateOwn')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canMove(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		if ($this->isAddOn())
		{
			return false;
		}
		
		return (
			$visitor->user_id
			&& $this->hasPermission('editAny')
		);
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canChangeDiscussionThread(&$error = null): bool
	{
		$visitor = \XF::visitor();

		if ($this->isAddOn())
		{
			return false;
		}

		return (
			$visitor->user_id
			&& $this->hasPermission('editAny')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canReassign(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id
			&& $this->hasPermission('reassign')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return mixed|null
	 */
	public function canBookmarkContent(&$error = null): ?bool
	{
		return $this->isVisible();
	}
	
	/**
	 * @param string $type
	 * @param null $error
	 *
	 * @return bool|mixed
	 */
	public function canDelete(string $type = 'soft', &$error = null): bool
	{
		$visitor = \XF::visitor();
		
		if ($type != 'soft')
		{
			return $this->hasPermission('hardDeleteAny');
		}
		
		if ($this->hasPermission('deleteAny'))
		{
			return true;
		}
		
		return (
			$this->user_id == $visitor->user_id
			&& $this->hasPermission('deleteOwn')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canUndelete(&$error = null): bool
	{
		$visitor = \XF::visitor();
		return $visitor->user_id && $this->hasPermission('undelete');
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canApproveUnapprove(&$error = null): bool
	{
		return (
			\XF::visitor()->user_id
			&& $this->hasPermission('approveUnapprove')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canWatch(&$error = null): bool
	{
		$visitor = \XF::visitor();
		
		// don't let authors watch as only they can update anyway
		return (
			$visitor->user_id
			&& $visitor->user_id != $this->user_id
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canEditTags(&$error = null): bool
	{
		$category = $this->Category;
		return $category && $category->canEditTags($this, $error);
	}

	/**
	 * @return bool
	 */
	public function canViewProductImages(): bool
	{
		return $this->hasPermission('viewProductAttach');
	}

	/**
	 * @return bool
	 */
	public function canUploadAndManageAttachments(): bool
	{
		return $this->hasPermission('uploadProductAttach');
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canReact(&$error = null): bool
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->product_state != 'visible')
		{
			return false;
		}

		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}
		
		return $this->hasPermission('react');
	}
	
	/**
	 * @return Entity
	 */
	public function getNewDownload(): Entity
	{
		$download = $this->_em->create('DBTech\eCommerce:Download');
		
		$download->product_id = $this->_getDeferredValue(function (): ?int
		{
			return $this->product_id;
		}, 'save');
		
		return $download;
	}
	
	/**
	 * @return int
	 * @throws \InvalidArgumentException
	 */
	public function getRealReleaseCount(): int
	{
		if (!$this->canViewDeletedContent() && !$this->canViewModeratedContent())
		{
			return $this->release_count;
		}
		
		/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
		$downloadRepo = $this->repository('DBTech\eCommerce:Download');
		return $downloadRepo->findDownloadsInProduct($this)->total();
	}
	
	/**
	 * @return int
	 * @throws \InvalidArgumentException
	 */
	public function getRealReviewCount(): int
	{
		if (!$this->canViewDeletedContent())
		{
			return $this->review_count;
		}
		
		/** @var \DBTech\eCommerce\Repository\ProductRating $ratingRepo */
		$ratingRepo = $this->repository('DBTech\eCommerce:ProductRating');
		return $ratingRepo->findReviewsInProduct($this)->total();
	}
	
	/**
	 * @return array
	 */
	public function getProductDownloadIds(): array
	{
		return $this->db()->fetchAllColumn('
			SELECT download_id
			FROM xf_dbtech_ecommerce_download
			WHERE product_id = ?
			ORDER BY release_date
		', $this->product_id);
	}
	
	/**
	 * @return array
	 */
	public function getProductRatingIds(): array
	{
		return $this->db()->fetchAllColumn('
			SELECT product_rating_id
			FROM xf_dbtech_ecommerce_product_rating
			WHERE product_id = ?
			ORDER BY rating_date
		', $this->product_id);
	}
	
	/**
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getUserLicenses(): \XF\Mvc\Entity\AbstractCollection
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->user_id)
		{
			return $this->_em->getEmptyCollection();
		}
		
		return $this->finder('DBTech\eCommerce:License')
			->where('product_id', $this->product_id)
			->where('user_id', $visitor->user_id)
			->fetch()
			;
	}
	
	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function ensureCostExists(): bool
	{
		if (!$this->Costs->count())
		{
			/** @var \DBTech\eCommerce\Entity\ProductCost $productCost */
			$productCost = $this->_em->create('DBTech\eCommerce:ProductCost');
			$productCost->product_id = $this->product_id;
			$productCost->cost_amount = 0;
			$productCost->length_amount = 0;
			$productCost->length_unit = '';
			$productCost->save();
		}
		
		return true;
	}

	/**
	 * @param string $context
	 * @param string $linkPrefix
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function renderOptions(string $context, string $linkPrefix): string
	{
		$handler = $this->getHandler();
		if (!$handler)
		{
			return '';
		}

		return $handler->renderOptions($this, $context, $linkPrefix);
	}
	
	/**
	 * @return bool
	 */
	public function rebuildCounters(): bool
	{
		$this->rebuildLastReleaseInfo();
		$this->rebuildReleaseCount();
		$this->rebuildReviewCount();
		$this->rebuildRating();
		$this->rebuildDownloadCount();
		$this->rebuildFullDownloadCount();
		$this->rebuildLicenseCount();
		$this->rebuildPurchaseCount();
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public function rebuildLastReleaseInfo(): bool
	{
		$lastUpdate = $this->db()->fetchRow("
			SELECT *
			FROM xf_dbtech_ecommerce_download
			WHERE product_id = ?
				AND download_state = 'visible'
			ORDER BY release_date DESC
			LIMIT 1
		", $this->product_id);
		if (!$lastUpdate)
		{
			return false;
		}
		
		$this->last_update = $lastUpdate['release_date'];
		$this->latest_version_id = $lastUpdate['download_id'];
		
		return true;
	}
	
	/**
	 * @return bool|mixed|null
	 */
	public function rebuildReleaseCount()
	{
		$this->release_count = $this->db()->fetchOne("
			SELECT COUNT(download_id)
			FROM xf_dbtech_ecommerce_download
			WHERE product_id = ?
				AND download_state = 'visible'
				AND release_date <= UNIX_TIMESTAMP()
		", [$this->product_id]);
		
		return $this->release_count;
	}
	
	/**
	 * @return bool|mixed|null
	 */
	public function rebuildReviewCount()
	{
		$this->review_count = $this->db()->fetchOne("
			SELECT COUNT(product_rating_id)
				FROM xf_dbtech_ecommerce_product_rating
				WHERE product_id = ?
					AND is_review = 1
					AND rating_state = 'visible'
		", $this->product_id);
		
		return $this->review_count;
	}
	
	/**
	 *
	 */
	public function rebuildRating()
	{
		$rating = $this->db()->fetchRow("
			SELECT COUNT(product_rating_id) AS total,
				SUM(rating) AS sum
			FROM xf_dbtech_ecommerce_product_rating
			WHERE product_id = ?
				AND count_rating = 1
				AND rating_state = 'visible'
		", $this->product_id);
		
		$this->rating_sum = $rating['sum'] ?: 0;
		$this->rating_count = $rating['total'] ?: 0;
	}
	
	/**
	 *
	 */
	protected function updateRatingAverage()
	{
		$threshold = self::RATING_WEIGHTED_THRESHOLD;
		$average = self::RATING_WEIGHTED_AVERAGE;
		
		$this->rating_weighted = ($threshold * $average + $this->rating_sum) / ($threshold + $this->rating_count);
		
		if ($this->rating_count)
		{
			$this->rating_avg = $this->rating_sum / $this->rating_count;
		}
		else
		{
			$this->rating_avg = 0;
		}
	}
	
	/**
	 * @return bool|mixed|null
	 */
	public function rebuildDownloadCount()
	{
		$this->download_count = $this->db()->fetchOne('
			SELECT COUNT(DISTINCT user_id)
			FROM xf_dbtech_ecommerce_product_download
			WHERE product_id = ?
		', $this->product_id);
		
		return $this->download_count;
	}
	
	/**
	 * @return bool|mixed|null
	 */
	public function rebuildFullDownloadCount()
	{
		$this->full_download_count = $this->db()->fetchOne('
			SELECT COUNT(download_log_id)
			FROM xf_dbtech_ecommerce_download_log
			WHERE product_id = ?
		', $this->product_id);
		
		return $this->full_download_count;
	}

	/**
	 * @return bool|mixed|null
	 */
	public function rebuildLicenseCount()
	{
		$this->license_count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_dbtech_ecommerce_license
			WHERE product_id = ?
				AND license_state = 'visible'
		", $this->product_id);

		return $this->license_count;
	}

	/**
	 * @param License $license
	 */
	public function licenseAdded(License $license)
	{
		$this->license_count++;
	}

	/**
	 * @param License $license
	 */
	public function licenseDataChanged(License $license)
	{
	}

	/**
	 * @param License $license
	 */
	public function licenseRemoved(License $license)
	{
		$this->license_count--;
	}

	/**
	 * @return bool|mixed|null
	 */
	public function rebuildPurchaseCount()
	{
		$this->purchase_count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_dbtech_ecommerce_order_item AS order_item
			LEFT JOIN xf_dbtech_ecommerce_order AS product_order USING(order_id)
			WHERE order_item.product_id = ?
				AND product_order.order_state = 'completed'
		", $this->product_id);

		return $this->purchase_count;
	}

	/**
	 *
	 */
	public function purchaseAdded()
	{
		$this->purchase_count++;
	}

	/**
	 *
	 */
	public function purchaseRemoved()
	{
		$this->purchase_count--;
	}
	
	/**
	 * @param Download $download
	 */
	public function releaseAdded(Download $download)
	{
		$this->release_count++;
		
		if ($download->release_date >= $this->last_update)
		{
			$this->last_update = $download->release_date;
		}
		
		$latestVersion = $this->LatestVersion;
		
		if (!$latestVersion || $download->release_date >= $latestVersion->release_date)
		{
			$this->latest_version_id = $download->download_id;
		}
		
		unset($this->_getterCache['product_download_ids']);
	}
	
	/**
	 * @param Download $download
	 */
	public function releaseRemoved(Download $download)
	{
		$this->release_count--;
		
		if (
			$download->release_date == $this->last_update
			|| $download->download_id == $this->latest_version_id
		) {
			$this->rebuildLastReleaseInfo();
		}
		
		unset($this->_getterCache['product_download_ids']);
	}

	/**
	 * @param array $versions
	 * @return bool
	 */
	protected function verifyProductVersions(array &$versions): bool
	{
		foreach ($versions AS $value => &$text)
		{
			$text = trim((string)$text);

			if ($text === '')
			{
				$this->error(\XF::phrase('dbtech_ecommerce_please_enter_text_for_each_version'), 'product_versions');
				return false;
			}

			if ($value === '' || preg_match('#[^a-z0-9_]#i', $value))
			{
				$this->error(\XF::phrase('please_enter_an_id_using_only_alphanumeric'), 'product_versions');
				return false;
			}

			if (strlen($value) > 25)
			{
			//	var_dump('bbbb');

				$this->error(\XF::phrase('please_enter_value_using_x_characters_or_fewer', ['count' => 25]));
				return false;
			}
		}

		return true;
	}
	
	/**
	 * @param int $nodeId
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function verifyNodeId(&$nodeId, $key = 'node_id'): bool
	{
		if (!$nodeId)
		{
			// Allow not entering a forum
			return true;
		}
		
		$forum = $this->_em->find('XF:Forum', $nodeId);
		if (!$forum)
		{
			$this->error(\XF::phrase('please_select_valid_forum'), $key);
			return false;
		}
		
		return true;
	}
	
	/**
	 * @param float $amount
	 * @param int|null $userId
	 *
	 * @throws \XF\Db\Exception
	 */
	protected function adjustUserProductCountIfNeeded(float $amount, ?int $userId = null)
	{
		if ($userId === null)
		{
			$userId = $this->user_id;
		}
		
		if ($userId)
		{
			$this->db()->query('
				UPDATE xf_user
				SET dbtech_ecommerce_product_count = GREATEST(0, CAST(dbtech_ecommerce_product_count AS SIGNED) + ?)
				WHERE user_id = ?
			', [$amount, $userId]);
		}
	}

	/**
	 * @throws \XF\Db\Exception
	 * @throws \Exception
	 */
	protected function productMadeVisible()
	{
		$this->adjustUserProductCountIfNeeded(1);
		
		if ($this->discussion_thread_id
			&& $this->Discussion
			&& $this->Discussion->discussion_type == 'dbtech_ecommerce_product'
		) {
			$thread = $this->Discussion;
			
			switch ($this->app()->options()->dbtechEcommerceContentDeleteThreadAction['action'])
			{
				case 'delete':
					$thread->discussion_state = 'visible';
					break;
				
				case 'close':
					$thread->discussion_open = true;
					break;
			}
			
			$thread->title = $this->getExpectedThreadTitle();
			$thread->saveIfChanged($saved, false, false);
		}
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->recalculateReactionIsCounted('dbtech_ecommerce_product', $this->product_id);
		$reactionRepo->recalculateReactionIsCounted('dbtech_ecommerce_download', $this->product_download_ids);
	}

	/**
	 * @param bool $hardDelete
	 *
	 * @throws \XF\Db\Exception
	 * @throws \Exception
	 */
	protected function productHidden(bool $hardDelete = false)
	{
		$this->adjustUserProductCountIfNeeded(-1);
		
		if ($this->discussion_thread_id
			&& $this->Discussion
			&& $this->Discussion->discussion_type == 'dbtech_ecommerce_product'
		) {
			$thread = $this->Discussion;
			
			switch ($this->app()->options()->dbtechEcommerceContentDeleteThreadAction['action'])
			{
				case 'delete':
					$thread->discussion_state = 'deleted';
					break;
				
				case 'close':
					$thread->discussion_open = false;
					break;
			}
			
			$thread->title = $this->getExpectedThreadTitle();
			$thread->saveIfChanged($saved, false, false);
		}
		
		if (!$hardDelete)
		{
			// on hard delete the reactions will be removed which will do this
			/** @var \XF\Repository\Reaction $reactionRepo */
			$reactionRepo = $this->repository('XF:Reaction');
			$reactionRepo->fastUpdateReactionIsCounted('dbtech_ecommerce_download', $this->product_download_ids, false);
		}
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('dbtech_ecommerce_download', $this->product_download_ids);
		$alertRepo->fastDeleteAlertsForContent('dbtech_ecommerce_rating', $this->product_rating_ids);
	}
	
	/**
	 * @param Category $from
	 * @param Category $to
	 */
	protected function productMoved(Category $from, Category $to)
	{
	}

	/**
	 * @throws \InvalidArgumentException
	 * @throws \XF\Db\Exception
	 */
	protected function productReassigned()
	{
		if ($this->product_state == 'visible')
		{
			$this->adjustUserProductCountIfNeeded(-1, $this->getExistingValue('user_id'));
			$this->adjustUserProductCountIfNeeded(1);
		}
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function productInsertedVisible()
	{
		$this->adjustUserProductCountIfNeeded(1);
	}
	
	/**
	 *
	 */
	protected function submitHamData()
	{
		/** @var \XF\Spam\ContentChecker $submitter */
		$submitter = $this->app()->container('spam.contentHamSubmitter');
		$submitter->submitHam('dbtech_ecommerce_product', $this->product_id);
	}
	
	/**
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function updateCategoryRecord()
	{
		if (!$this->Category)
		{
			return;
		}
		
		$category = $this->Category;
		
		if ($this->isUpdate() && $this->isChanged('product_category_id'))
		{
			// moved, trumps the rest
			if ($this->product_state == 'visible')
			{
				$category->productAdded($this);
				$category->save();
			}
			
			if ($this->getExistingValue('product_state') == 'visible')
			{
				/** @var Category $oldCategory */
				$oldCategory = $this->getExistingRelation('Category');
				if ($oldCategory)
				{
					$oldCategory->productRemoved($this);
					$oldCategory->save();
				}
			}
			
			return;
		}
		
		// check for entering/leaving visible
		$visibilityChange = $this->isStateChanged('product_state', 'visible');
		if ($visibilityChange == 'enter')
		{
			$category->productAdded($this);
			$category->save();
		}
		elseif ($visibilityChange == 'leave')
		{
			$category->productRemoved($this);
			$category->save();
		}
		elseif ($this->isUpdate() && $this->product_state == 'visible')
		{
			$category->productDataChanged($this);
			$category->save();
		}
	}

	/**
	 *
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	protected function _preSave()
	{
		if ($this->prefix_id && $this->isChanged(['prefix_id', 'product_category_id']) && !$this->Category->isPrefixValid($this->prefix_id))
		{
			$this->prefix_id = 0;
		}
		
		if (!$this->user_id)
		{
			/** @var \XF\Entity\User $user */
			if (
				$this->app()->options()->dbtechEcommerceDefaultProductOwner
				&& $user = $this->_em->find('XF:User', $this->app()->options()->dbtechEcommerceDefaultProductOwner)
			) {
				$this->user_id = $user->user_id;
				$this->username = $user->username;
			}
			else
			{
				$visitor = \XF::visitor();
				$this->user_id = $visitor->user_id;
				$this->username = $visitor->username;
			}
		}
		
		if ($this->isUpdate() && $this->isChanged('parent_product_id') && $this->getOption('verify_parent'))
		{
			$parentValid = $this->getProductRepo()->createProductTree()->isNewParentValid(
				$this->getExistingValue('product_id'),
				$this->parent_product_id
			);
			if (!$parentValid)
			{
				$this->error(\XF::phrase('dbtech_ecommerce_please_select_valid_parent_product'), 'parent_product_id');
			}
		}

		/** @var \DBTech\eCommerce\Entity\Product $parentProduct */
		if (
			$this->isAddOn()
			&& ($parentProduct = $this->em()->find('DBTech\eCommerce:Product', $this->parent_product_id))
			&& $parentProduct->isAddOn()
		) {
			$this->error(\XF::phrase('dbtech_ecommerce_cannot_create_nested_addon_products'), 'parent_product_id');
		}
		
		if (
			$this->hasDownloadFunctionality()
			&& (
				(
					$this->isInsert()
					&& !$this->product_versions
				)
				||
				(
					is_array($this->product_versions)
					&& !$this->product_versions
				)
			)
		) {
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_at_least_one_version'), 'product_versions', false);
		}
		
		if ($this->isInsert() || $this->isChanged(['rating_sum', 'rating_count']))
		{
			$this->updateRatingAverage();
		}
	}
	
	/**
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('product_state', 'visible');
		$approvalChange = $this->isStateChanged('product_state', 'moderated');
		$deletionChange = $this->isStateChanged('product_state', 'deleted');
		
		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->productMadeVisible();
				
				if ($approvalChange)
				{
					$this->submitHamData();
				}
			}
			elseif ($visibilityChange == 'leave')
			{
				$this->productHidden();
			}
			
			if ($this->isChanged('product_category_id'))
			{
				/** @var \DBTech\eCommerce\Entity\Category $oldCategory */
				$oldCategory = $this->getExistingRelation('Category');
				if ($oldCategory && $this->Category)
				{
					$this->productMoved($oldCategory, $this->Category);
				}
			}
			
			if ($deletionChange == 'leave' && $this->DeletionLog)
			{
				$this->DeletionLog->delete();
			}
			
			if ($deletionChange == 'leave' && $this->Discussion)
			{
				$this->Discussion->discussion_state = 'visible';
				$this->Discussion->save();
			}
			
			if ($approvalChange == 'leave' && $this->ApprovalQueue)
			{
				$this->ApprovalQueue->delete();
			}
		}
		else
		{
			// insert
			if ($this->product_state == 'visible')
			{
				$this->productInsertedVisible();
			}
		}
		
		if ($this->isUpdate())
		{
			if ($this->isChanged('user_id'))
			{
				$this->productReassigned();
			}

			if ($this->isChanged('discussion_thread_id'))
			{
				if ($this->getExistingValue('discussion_thread_id'))
				{
					/** @var \XF\Entity\Thread $oldDiscussion */
					$oldDiscussion = $this->getExistingRelation('Discussion');
					if ($oldDiscussion && $oldDiscussion->discussion_type == 'dbtech_ecommerce_product')
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
					$this->Discussion->discussion_type = 'dbtech_ecommerce_product';
					$this->Discussion->save(false, false);
				}
			}
			
			if (
				!$this->isAddOn()
				&& (
					$this->isChanged('product_category_id')
					|| $this->isChanged('product_type')
					|| $this->isChanged('product_versions')
					|| $this->isChanged('product_filters')
					|| $this->isChanged('shipping_zones')
				)
			) {
				$productTree = $this->getProductRepo()->createProductTree(null, $this->product_id);
				foreach ($productTree->children() as $childProduct)
				{
					$childProduct->record->bulkSet([
						'product_category_id' => $this->product_category_id,
						'product_type' => $this->product_type,
						'product_versions' => $this->product_versions,
						'product_filters' => $this->product_filters,
						'shipping_zones' => $this->shipping_zones
					]);
					$childProduct->record->saveIfChanged();
				}
			}
		}
		
		if ($this->discussion_thread_id)
		{
			$newThreadTitle = $this->getExpectedThreadTitle();
			if (
				$this->Discussion
				&& $this->Discussion->discussion_type == 'dbtech_ecommerce_product'
				&& $newThreadTitle != $this->Discussion->title
			) {
				$this->Discussion->title = $newThreadTitle;
				$this->Discussion->saveIfChanged($saved, false, false);
			}
		}
		
		if ($approvalChange == 'enter')
		{
			/** @var \XF\Entity\ApprovalQueue $approvalQueue */
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->creation_date;
			$approvalQueue->save();
		}
		elseif ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}
		
		if ($this->isInsert() || $this->isChanged('product_versions'))
		{
			$this->createVersionPhrases();
		}
		
		if ($this->isUpdate())
		{
			$downloadVersions = $this->finder('DBTech\eCommerce:DownloadVersion')
				->where('product_id', $this->product_id)
				->where('product_version', '!=', array_keys($this->product_versions))
				->fetch();
			
			/** @var \DBTech\eCommerce\Entity\DownloadVersion $downloadVersion */
			foreach ($downloadVersions as $downloadVersion)
			{
				$downloadVersion->delete();
			}
		}
		
		if (!$this->isAddOn())
		{
			$this->updateCategoryRecord();
		}
		
		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('dbtech_ecommerce_product', $this);
		}
	}
	
	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _postDelete()
	{
		if ($this->product_state == 'visible')
		{
			$this->productHidden(true);
		}
		
		if ($this->Category && $this->product_state == 'visible')
		{
			$this->Category->productRemoved($this);
			$this->Category->save();
		}
		
		if ($this->product_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->product_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('dbtech_ecommerce_product', $this, 'delete_hard');
		}
		
		$db = $this->db();
		
		//$db->delete('xf_dbtech_ecommerce_product_feature', 'product_id = ?', $this->product_id);
		$db->delete('xf_dbtech_ecommerce_product_watch', 'product_id = ?', $this->product_id);
		$db->delete('xf_dbtech_ecommerce_product_welcome_email', 'product_id = ?', $this->product_id);

		$this->deleteVersionPhrases(array_keys($this->product_versions));
		
		$this->app()->jobManager()->enqueue('DBTech\eCommerce:ProductDeleteCleanUp', [
			'productId' => $this->product_id,
			'title' => $this->title
		]);

		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('dbtech_ecommerce_product', $this->product_id);

		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('dbtech_ecommerce_product', $this->product_id);

		/** @var \DBTech\eCommerce\Service\Product\Icon $iconService */
		$iconService = $this->app()->service('DBTech\eCommerce:Product\Icon', $this);
		$iconService->deleteIconForProductDelete();
	}
	
	/**
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function createVersionPhrases()
	{
		foreach ($this->product_versions as $version => $title)
		{
			/** @var \XF\Entity\Phrase $phrase */
			$phrase = $this->getMasterVersionPhrase($version);
			$phrase->phrase_text = $title;
			$phrase->save();
		}
	}
	
	/**
	 * @param array $versions
	 */
	protected function deleteVersionPhrases(array $versions)
	{
		$phraseNames = $this->getVersionPhraseNames($versions);
		
		$versionPhrases = $this->finder('XF:Phrase')
			->where('title', $phraseNames)
			->fetch();
		
		foreach ($versionPhrases AS $phrase)
		{
			$phrase->delete();
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
		
		if ($this->product_state == 'deleted')
		{
			return false;
		}
		
		$this->product_state = 'deleted';
		
		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;
		
		$this->save();
		
		$productTree = $this->getProductRepo()->createProductTree(null, $this->product_id);
		foreach ($productTree->children() as $childProduct)
		{
			// Also soft delete all children
			$childProduct->record->softDelete($reason, $byUser);
		}
		
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
		
		if ($this->product_state == 'visible')
		{
			return false;
		}
		
		$this->product_state = 'visible';
		$this->save();
		
		return true;
	}
	
	/**
	 *
	 */
	public function rebuildProductFieldValuesCache()
	{
		$this->repository('DBTech\eCommerce:ProductField')->rebuildProductFieldValuesCache($this->product_id);
	}
	
	/**
	 *
	 */
	public function rebuildShippingZoneCache()
	{
		$this->repository('DBTech\eCommerce:ShippingZoneProductMap')
			->rebuildProductAssociationCache([$this->product_id])
		;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\ProductWelcomeEmail
	 */
	public function getWelcomeEmail(): ProductWelcomeEmail
	{
		$welcomeEmail = $this->WelcomeEmail;
		if (!$welcomeEmail)
		{
			$welcomeEmail = $this->_em->create('DBTech\eCommerce:ProductWelcomeEmail');
			$welcomeEmail->product_id = $this->_getDeferredValue(function () { return $this->product_id; }, 'save');
		}

		return $welcomeEmail;
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
		$route = $canonical ? 'canonical:dbtech-ecommerce' : 'dbtech-ecommerce';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}

	/**
	 * @return string|null
	 */
	public function getContentPublicRoute(): ?string
	{
		return 'dbtech-ecommerce';
	}

	/**
	 * @param string $context
	 *
	 * @return \XF\Phrase
	 */
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('dbtech_ecommerce_product_x', ['title' => $this->full_title]);
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-see self::addReactionStateToApiResult()
	 *
	 * @api-type Product
	 *
	 * @api-out str $username
	 * @api-out int $release_count
	 * @api-out int $review_count
	 * @api-out bool $is_watching
	 * @api-out Category $Category <cond> If the "skip_category" option is not passed to the API Result generation.
	 * @api-out Download $LatestVersion <cond> If the "with_latest_version" option is passed to the API Result generation.
	 * @api-out User $User The user who owns the product
	 * @api-out str $icon_url
	 * @api-out str $full_title
	 * @api-out str $tagline
	 * @api-out array $product_fields
	 * @api-out array $tags
	 * @api-out str $prefix
	 * @api-out bool $can_edit
	 * @api-out bool $can_edit_tags
	 * @api-out bool $can_edit_icon
	 * @api-out bool $can_soft_delete
	 * @api-out bool $can_hard_delete
	 * @api-out bool $can_react
	 * @api-out bool $can_download
	 * @api-out bool $can_view_product_images
	 * @api-out str $product_page_url Link to the product's page in the store.
	 * @api-out array $product_versions Key-value pair of product versions.
	 */
	protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result,
		$verbosity = self::VERBOSITY_NORMAL,
		array $options = []
	) {
		$result->username = $this->User ? $this->User->username : $this->username;
		
		if ($verbosity > self::VERBOSITY_NORMAL)
		{
			if ($this->attach_count)
			{
				// note that we allow viewing of thumbs and metadata, regardless of permissions, when viewing the
				// content an attachment is connected to
				$result->includeRelation('Attachments');
			}
			
			$this->addReactionStateToApiResult($result);
			
			$result->release_count = $this->real_release_count;
			$result->review_count = $this->real_review_count;
		}
		
		$visitor = \XF::visitor();
		
		if ($visitor->user_id)
		{
			$result->is_watching = isset($this->Watch[$visitor->user_id]);
		}
		
		if (!empty($options['skip_category']))
		{
			$result->skipRelation('Category');
		}
		
		if (!empty($options['with_latest_version']))
		{
			$result->includeRelation('LatestVersion');
		}
		
		$result->icon_url = $this->getIconUrl(null, true);
		
		$result->includeGetter('full_title');
		$result->includeGetter('tagline');
		
		$result->product_fields = $this->product_fields->getNamedFieldValues($this->Category->field_cache);
		$result->tags = array_column($this->tags, 'tag');
		
		if ($this->prefix_id)
		{
			$result->prefix = \XF::phrase('dbtech_ecommerce_product_prefix.' . $this->prefix_id);
		}
		
		$result->can_edit = $this->canEdit();
		$result->can_edit_tags = $this->canEditTags();
		$result->can_edit_icon = $this->canEditIcon();
		$result->can_soft_delete = $this->canDelete();
		$result->can_hard_delete = $this->canDelete('hard');
		$result->can_react = $this->canReact();
		$result->can_download = $this->canDownload();
		$result->can_view_product_images = $this->canViewProductImages();
		
		$result->product_page_url = $this->app()->router('public')->buildLink('canonical:dbtech-ecommerce', $this);
		
		$result->product_versions = $this->getVersionLabels($this->product_versions);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product';
		$structure->shortName = 'DBTech\eCommerce:Product';
		$structure->contentType = 'dbtech_ecommerce_product';
		$structure->primaryKey = 'product_id';
		$structure->columns = [
			'product_id'                => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'title'                     => [
				'type'      => self::STR,
				'maxLength' => 100,
				'required'  => 'please_enter_valid_title',
				'censor'    => true,
				'api'       => true
			],
			'parent_product_id'         => ['type' => self::UINT, 'required' => true, 'default' => 0, 'api' => true],
			'product_category_id'       => ['type' => self::UINT, 'required' => true, 'api' => true],
			'product_state'             => [
				'type'          => self::STR,
				'default'       => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted'],
				'api'           => true
			],
			'creation_date'             => ['type' => self::UINT, 'default' => \XF::$time, 'api' => true],
			'last_update'               => ['type' => self::UINT, 'default' => \XF::$time, 'api' => true],
			'latest_version_id'         => ['type' => self::UINT, 'default' => 0],
			'is_paid'                   => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'is_featured'               => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'is_discountable'           => ['type' => self::BOOL, 'default' => true, 'api' => true],
			'is_listed'                 => ['type' => self::BOOL, 'default' => true, 'api' => true],
			'welcome_email'             => ['type' => self::BOOL, 'default' => false],
			'is_all_access'             => ['type' => self::BOOL, 'default' => true, 'api' => true],
			'all_access_group_ids'      => ['type' => self::JSON_ARRAY, 'default' => [], 'api' => true],
			'user_id'                   => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'username'                  => [
				'type'      => self::STR,
				'maxLength' => 50,
				'required'  => 'please_enter_valid_name'
			],
			'ip_id'                     => ['type' => self::UINT, 'default' => 0],
			'warning_id'                => ['type' => self::UINT, 'default' => 0],
			'warning_message'           => ['type' => self::STR, 'default' => '', 'api' => true],
			'requirements'              => ['type' => self::JSON_ARRAY, 'default' => [], 'api' => true],
			'description_full'          => ['type' => self::STR, 'default' => '', 'api' => true],
			'product_specification'     => ['type' => self::STR, 'default' => '', 'api' => true],
			'copyright_info'            => ['type' => self::STR, 'default' => '', 'api' => true],
			'attach_count'              => ['type' => self::UINT, 'max' => 65535, 'forced' => true, 'default' => 0],
			'product_type'              => [
				'type'    => self::STR,
				'default' => 'dbtech_ecommerce_digital',
				'api'     => true
			],
			'product_type_data'         => ['type' => self::JSON_ARRAY, 'default' => [], 'api' => true],
			'license_prefix'            => [
				'type'      => self::STR,
				'maxLength' => 75,
				'required'  => 'dbtech_ecommerce_please_enter_valid_license_prefix',
				'unique'    => 'dbtech_ecommerce_license_prefixes_must_be_unique',
				'match'     => 'alphanumeric_hyphen',
				'api'       => true
			],
			'product_versions'          => ['type' => self::JSON_ARRAY, 'default' => []],
			'has_demo'                  => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'extra_group_ids'           => [
				'type'    => self::LIST_COMMA,
				'default' => [],
				'list'    => ['type' => 'posint', 'unique' => true, 'sort' => SORT_NUMERIC]
			],
			'temporary_extra_group_ids' => [
				'type'    => self::LIST_COMMA,
				'default' => [],
				'list'    => ['type' => 'posint', 'unique' => true, 'sort' => SORT_NUMERIC]
			],
			'support_node_id'           => ['type' => self::UINT, 'default' => 0],
			'thread_node_id'            => ['type' => self::UINT, 'default' => 0],
			'thread_prefix_id'          => ['type' => self::UINT, 'default' => 0],
			'discussion_thread_id'      => ['type' => self::UINT, 'default' => 0],
			'field_cache'               => ['type' => self::JSON_ARRAY, 'default' => []],
			'product_fields'            => ['type' => self::JSON_ARRAY, 'default' => []],
			'product_filters'           => ['type' => self::JSON_ARRAY, 'default' => []],
			'cost_cache'                => ['type' => self::JSON_ARRAY, 'default' => []],
			'shipping_zones'            => ['type' => self::JSON_ARRAY, 'default' => []],
			'download_count'            => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'full_download_count'       => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'rating_count'              => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'rating_sum'                => ['type' => self::UINT, 'default' => 0, 'forced' => true],
			'rating_avg'                => ['type' => self::FLOAT, 'default' => 0, 'api' => true],
			'rating_weighted'           => ['type' => self::FLOAT, 'default' => 0, 'api' => true],
			'release_count'             => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'review_count'              => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'license_count'             => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'purchase_count'            => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'icon_date'                 => ['type' => self::UINT, 'default' => 0],
			'icon_extension'            => ['type' => self::STR, 'default' => 'jpg'],
			'prefix_id'                 => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'tags'                      => ['type' => self::JSON_ARRAY, 'default' => []],
			'global_branding_free'      => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'branding_free'             => ['type' => self::UINT, 'default' => 0, 'api' => true]
		];
		$structure->behaviors = [
			'XF:PermissionRebuildable'   => [
				'permissionContentType' => $structure->contentType
			],
			'XF:Taggable'                => ['stateField' => 'product_state'],
			'XF:Reactable'               => ['stateField' => 'product_state'],
			'XF:Indexable'               => [
				'checkForUpdates' => [
					'product_category_id',
					'description_full',
					'product_specification',
					'prefix_id',
					'tags',
					'copyright_info',
					'parent_product_id',
					'creation_date',
					'product_state'
				]
			],
			'XF:IndexableContainer'      => [
				'childContentType' => 'dbtech_ecommerce_download',
				'childIds'         => function (Product $product): array
				{
					return $product->product_download_ids;
				},
				'checkForUpdates'  => ['product_category_id', 'product_state', 'prefix_id']
			],
			'XF:NewsFeedPublishable'     => [
				'usernameField' => 'username',
				'dateField'     => 'creation_date'
			],
			'XF:CustomFieldsHolder'      => [
				'column'           => 'product_fields',
				'valueTable'       => 'xf_dbtech_ecommerce_product_field_value',
				'checkForUpdates'  => ['product_category_id'],
				'getAllowedFields' => function (Product $product): array
				{
					return $product->Category ? $product->Category->field_cache : [];
				}
			],
			'XF:ContentVotableContainer' => [
				'childContentType' => 'dbtech_ecommerce_rating',
				'childIds'         => function (Product $product): array
				{
					return $product->product_rating_ids;
				},
				'stateField'       => 'product_state'
			],
		];
		$structure->getters = [
			//			'title' => true,
			'full_title'           => true,
			'tagline'              => true,
			'description'          => true,
			'product_page_url'     => true,
			'starting_price'       => true,
			'starting_cost'        => true,
			'product_fields'       => true,
			'real_release_count'   => true,
			'real_review_count'    => true,
			'product_download_ids' => true,
			'product_rating_ids'   => true,
			'UserLicenses'         => true
		];
		$structure->relations = [
			/*
			'MasterTitle' => [
				'entity' => 'XF:Phrase',
				'type' => self::TO_ONE,
				'conditions' => [
					['language_id', '=', 0],
					['title', '=', 'dbtech_ecommerce_product.', '$product_id']
				]
			],
			*/
			'MasterTagline'     => [
				'entity'        => 'XF:Phrase',
				'type'          => self::TO_ONE,
				'conditions'    => [
					['language_id', '=', 0],
					['title', '=', 'dbtech_ecommerce_product_tag.', '$product_id']
				],
				'cascadeDelete' => true
			],
			'MasterDescription' => [
				'entity'        => 'XF:Phrase',
				'type'          => self::TO_ONE,
				'conditions'    => [
					['language_id', '=', 0],
					['title', '=', 'dbtech_ecommerce_product_desc.', '$product_id']
				],
				'cascadeDelete' => true
			],
			'Category'          => [
				'entity'     => 'DBTech\eCommerce:Category',
				'type'       => self::TO_ONE,
				'conditions' => [
					['category_id', '=', '$product_category_id']
				],
				'primary'    => true,
				'api'        => true
			],
			'Sale'              => [
				'entity'     => 'DBTech\eCommerce:ProductSale',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
			'User'              => [
				'entity'     => 'XF:User',
				'type'       => self::TO_ONE,
				'conditions' => 'user_id',
				'primary'    => true,
				'api'        => true
			],
			'ThreadForum'       => [
				'entity'     => 'XF:Forum',
				'type'       => self::TO_ONE,
				'conditions' => [
					['node_id', '=', '$thread_node_id']
				],
				'primary'    => true,
				'with'       => 'Node'
			],
			'SupportForum'      => [
				'entity'     => 'XF:Forum',
				'type'       => self::TO_ONE,
				'conditions' => [
					['node_id', '=', '$support_node_id']
				],
				'primary'    => true,
				'with'       => 'Node'
			],
			'Discussion'        => [
				'entity'     => 'XF:Thread',
				'type'       => self::TO_ONE,
				'conditions' => [
					['thread_id', '=', '$discussion_thread_id']
				],
				'primary'    => true
			],
			'Attachments'       => [
				'entity'     => 'XF:Attachment',
				'type'       => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_product'],
					['content_id', '=', '$product_id']
				],
				'with'       => 'Data',
				'order'      => 'attach_date'
			],
			'Permissions'       => [
				'entity'     => 'XF:PermissionCacheContent',
				'type'       => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_product'],
					['content_id', '=', '$product_id']
				],
				'key'        => 'permission_combination_id',
				'proxy'      => true
			],
			'TempFeatures'      => [
				'entity'     => 'DBTech\eCommerce:ProductFeatureTemp',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id',
				'key'        => 'feature_key'
			],
			'Ratings'           => [
				'entity'     => 'DBTech\eCommerce:ProductRating',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id',
				'key'        => 'user_id'
			],
			'Parent'            => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_ONE,
				'conditions' => [
					['product_id', '=', '$parent_product_id']
				],
				'primary'    => true
			],
			'Children'          => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_MANY,
				'conditions' => [
					['parent_product_id', '=', '$product_id']
				],
				'with'       => [
					'Sale'
				]
			],
			'Costs'             => [
				'entity'        => 'DBTech\eCommerce:ProductCost',
				'type'          => self::TO_MANY,
				'conditions'    => 'product_id',
				'order'         => 'cost_amount',
				'cascadeDelete' => true,
				'with'          => [
					'Product',
					'Product.Sale'
				]
			],
			'Licenses'          => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id',
				'order'      => ['purchase_date', 'DESC']
			],
			'AllAccessLicense'          => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_ONE,
				'conditions' => [
					'product_id',
					['user_id', '=', \XF::visitor()->user_id],
					['required_user_group_ids', '!=', '[]']
				]
			],
			'Downloads'         => [
				'entity'     => 'DBTech\eCommerce:Download',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id',
				'order'      => ['release_date', 'DESC']
			],
			'LatestVersion'     => [
				'entity'     => 'DBTech\eCommerce:Download',
				'type'       => self::TO_ONE,
				'conditions' => [
					['download_id', '=', '$latest_version_id']
				],
				'primary'    => true
			],
			'ProductDownloads'  => [
				'entity'     => 'DBTech\eCommerce:ProductDownload',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id',
				'key'        => 'user_id'
			],
			'DownloadLog'       => [
				'entity'     => 'DBTech\eCommerce:DownloadLog',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id'
			],
			'PurchaseLog'       => [
				'entity'     => 'DBTech\eCommerce:PurchaseLog',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id'
			],
			'ShippingZones'     => [
				'entity'        => 'DBTech\eCommerce:ShippingZoneProductMap',
				'type'          => self::TO_MANY,
				'conditions'    => 'product_id',
				'key'           => 'shipping_zone_id',
				'cascadeDelete' => true
			],
			'WelcomeEmail'      => [
				'entity'     => 'DBTech\eCommerce:ProductWelcomeEmail',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
			'Prefix'            => [
				'entity'     => 'DBTech\eCommerce:ProductPrefix',
				'type'       => self::TO_ONE,
				'conditions' => 'prefix_id',
				'primary'    => true
			],
			'Watch'             => [
				'entity'     => 'DBTech\eCommerce:ProductWatch',
				'type'       => self::TO_MANY,
				'conditions' => 'product_id',
				'key'        => 'user_id'
			],
			'DeletionLog'       => [
				'entity'     => 'XF:DeletionLog',
				'type'       => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_product'],
					['content_id', '=', '$product_id']
				],
				'primary'    => true
			],
			'ApprovalQueue'     => [
				'entity'     => 'XF:ApprovalQueue',
				'type'       => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_product'],
					['content_id', '=', '$product_id']
				],
				'primary'    => true
			]
		];

		$structure->withAliases = [
			'full'          => [
				'User',
				'AllAccessLicense',
				'SupportForum',
				'SupportForum.Node',
				'SupportForum.Node.Permissions|' . \XF::visitor()->permission_combination_id,
				'Permissions|' . \XF::visitor()->permission_combination_id,
				'LatestVersion',
				function (): ?array
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return [
							'Watch|' . $userId
						];
					}

					return null;
				},
				function (array $withParams): ?array
				{
					if (!empty($withParams['category']))
					{
						return ['Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id];
					}

					return null;
				},
				function (array $withParams): ?array
				{
					$userId = \XF::visitor()->user_id;
					if (!empty($withParams['category']) && $userId)
					{
						return ['Category.Watch|' . $userId];
					}

					return null;
				},
				function (): ?string
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return 'Reactions|' . $userId;
					}

					return null;
				}
			],
			'fullCategory'  => [
				'full',
				function (): array
				{
					$with = ['Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id];

					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						$with[] = 'Category.Watch|' . $userId;
					}

					return $with;
				}
			],
			'permissionSet' => [
				function (): array
				{
					$visitor = \XF::visitor();
					return [
						'Permissions|' . $visitor->permission_combination_id,
						'Category',
						'Category.Permissions|' . $visitor->permission_combination_id
					];
				}
			],
			'api'           => [
				'User.api',
				'Category.api',
				'Permissions|' . \XF::visitor()->permission_combination_id,
				'Category.Permissions|' . \XF::visitor()->permission_combination_id,
				function (): ?array
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return ['Watch|' . $userId];
					}

					return null;
				},
				function (): ?string
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return 'Reactions|' . $userId;
					}

					return null;
				}
			]
		];

		$structure->options = [
			'verify_parent' => true,
			'log_moderator' => true
		];

		static::addBookmarkableStructureElements($structure);
		static::addReactableStructureElements($structure);

		return $structure;
	}

	/**
	 *
	 */
	protected function _setupDefaults()
	{
		if (\XF::options()->dbtechEcommerceCustomerGroup)
		{
			$this->temporary_extra_group_ids = [\XF::options()->dbtechEcommerceCustomerGroup => \XF::options()->dbtechEcommerceCustomerGroup];
		}
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
}