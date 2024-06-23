<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Category;

/**
 * Class Create
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Category */
	protected $category;

	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;
	
	/** @var string */
	protected $productType;
	
	/** @var int */
	protected $parentProductId;

	/** @var string */
	protected $description;

	/** @var string */
	protected $tagline;

	/** @var \DBTech\eCommerce\Service\Product\MessagePreparer */
	protected $descriptionPreparer;

	/** @var \DBTech\eCommerce\Service\Product\MessagePreparer */
	protected $specificationPreparer;

	/** @var \DBTech\eCommerce\Service\Product\MessagePreparer */
	protected $copyrightPreparer;

	/** @var \XF\Service\Thread\Creator|null */
	protected $threadCreator;

	/** @var string */
	protected $attachmentHash;

	/** @var array */
	protected $availableFields;
	
	/** @var array */
	protected $availableFilters;
	
	/** @var array */
	protected $shippingZones;

	/** @var array */
	protected $costs = [];

	/** @var bool */
	protected $logIp = true;

	/** @var \XF\Service\Tag\Changer */
	protected $tagChanger;

	/** @var bool */
	protected $performValidations = true;
	
	/**
	 * Create constructor.
	 *
	 * @param \XF\App $app
	 * @param Category $category
	 * @param string $productType
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(\XF\App $app, Category $category, string $productType)
	{
		parent::__construct($app);
		$this->category = $category;
		$this->productType = $productType;
		$this->setupDefaults();
	}
	
	/**
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function setupDefaults()
	{
		$product = $this->category->getNewProduct($this->productType);

		$this->product = $product;

		$this->descriptionPreparer = $this->service('DBTech\eCommerce:Product\MessagePreparer', $this->product, 'description_full', $this);
		$this->specificationPreparer = $this->service('DBTech\eCommerce:Product\MessagePreparer', $this->product, 'product_specification', $this);
		$this->copyrightPreparer = $this->service('DBTech\eCommerce:Product\MessagePreparer', $this->product, 'copyright_info', $this);

		$this->tagChanger = $this->service('XF:Tag\Changer', 'dbtech_ecommerce_product', $this->category);

		$visitor = \XF::visitor();
		$this->product->user_id = $visitor->user_id;
		$this->product->username = $visitor->username;

		$this->product->product_state = $this->category->getNewContentState();
	}

	/**
	 * @return Category
	 */
	public function getCategory(): Category
	{
		return $this->category;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Product
	 */
	public function getProduct(): \DBTech\eCommerce\Entity\Product
	{
		return $this->product;
	}
	
	/**
	 * @return MessagePreparer
	 */
	public function getDescriptionPreparer(): MessagePreparer
	{
		return $this->descriptionPreparer;
	}
	
	/**
	 * @return MessagePreparer
	 */
	public function getSpecificationPreparer(): MessagePreparer
	{
		return $this->specificationPreparer;
	}
	
	/**
	 * @return MessagePreparer
	 */
	public function getCopyrightPreparer(): MessagePreparer
	{
		return $this->copyrightPreparer;
	}

	/**
	 * @param bool $perform
	 *
	 * @return $this
	 */
	public function setPerformValidations(bool $perform): Create
	{
		$this->performValidations = $perform;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getPerformValidations(): bool
	{
		return $this->performValidations;
	}

	/**
	 * @return $this
	 */
	public function setIsAutomated(): Create
	{
		$this->logIp(false);
		$this->setPerformValidations(false);

		return $this;
	}

	/**
	 * @param string $hash
	 *
	 * @return $this
	 */
	public function setAttachmentHash(string $hash): Create
	{
		$this->attachmentHash = $hash;

		return $this;
	}

	/**
	 * @param string $tagline
	 *
	 * @return $this
	 */
	public function setTagLine(string $tagline): Create
	{
		$this->tagline = $tagline;

		return $this;
	}

	/**
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setDescription(string $description): Create
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTagline(): string
	{
		return $this->tagline;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param int $parentProductId
	 *
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 *
	 * @return $this
	 */
	public function setParentProduct(int $parentProductId): Create
	{
		$product = $this->product;

		// Used for validations later
		$this->parentProductId = $parentProductId;

		/** @var \DBTech\eCommerce\Entity\Product $parentProduct */
		$parentProduct = $this->em()->find('DBTech\eCommerce:Product', $parentProductId);
		if ($parentProduct && $parentProduct->hasLicenseFunctionality() && !$parentProduct->isAddOn())
		{
			$product->parent_product_id = $parentProduct->product_id;
			$product->product_type = $parentProduct->product_type;
			$product->product_versions = $parentProduct->product_versions;
			$product->product_filters = $parentProduct->product_filters;

			$product->hydrateRelation('Parent', $parentProduct);
		}

		return $this;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle(string $title): Create
	{
		$this->product->title = $title;

		return $this;
	}
	
	/**
	 * @param string $description
	 * @param string $specification
	 * @param string $copyright
	 * @param bool $format
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function setContent(
		string $description,
		string $specification,
		string $copyright,
		bool $format = true
	): bool {
		return (
			$this->descriptionPreparer->setMessage($description, $format, $this->performValidations)
			&& $this->specificationPreparer->setMessage($specification, $format, $this->performValidations)
			&& $this->copyrightPreparer->setMessage($copyright, $format, $this->performValidations)
		);
	}

	/**
	 * @param int $prefixId
	 *
	 * @return $this
	 */
	public function setPrefix(int $prefixId): Create
	{
		$this->product->prefix_id = $prefixId;

		return $this;
	}

	/**
	 * @param string $tags
	 *
	 * @return $this
	 */
	public function setTags(string $tags): Create
	{
		if ($this->tagChanger->canEdit() || !$this->performValidations)
		{
			$this->tagChanger->setEditableTags($tags);
		}

		return $this;
	}

	/**
	 * @param string $requirements
	 *
	 * @return $this
	 */
	public function setRequirements(string $requirements): Create
	{
		/** @var \XF\Repository\Tag $tagRepos */
		$tagRepos = $this->repository('XF:Tag');
		$this->product->requirements = $tagRepos->splitTagList($requirements);

		return $this;
	}

	/**
	 * @param array $versions
	 * @param array $versionsText
	 *
	 * @return $this
	 */
	public function setVersions(array $versions, array $versionsText): Create
	{
		$productVersions = [];

		foreach ($versions AS $key => $choice)
		{
			if (isset($versionsText[$key]) && $versionsText[$key] !== '')
			{
				$productVersions[$choice] = $versionsText[$key];
			}
		}

		$this->product->product_versions = $productVersions;

		return $this;
	}

	/**
	 * @param array $availableFields
	 *
	 * @return $this
	 */
	public function setAvailableFields(array $availableFields): Create
	{
		$this->availableFields = $availableFields;

		return $this;
	}

	/**
	 * @param array $availableFilters
	 *
	 * @return $this
	 */
	public function setAvailableFilters(array $availableFilters): Create
	{
		$this->availableFilters = $availableFilters;

		return $this;
	}

	/**
	 * @param array $shippingZones
	 *
	 * @return $this
	 */
	public function setShippingZones(array $shippingZones): Create
	{
		$this->shippingZones = $shippingZones;

		return $this;
	}

	/**
	 * @param array $productFields
	 *
	 * @return $this
	 */
	public function setProductFields(array $productFields): Create
	{
		$product = $this->product;
		
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $product->product_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, 'user')
			->filterOnly($this->category->field_cache);
		
		$productFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
		
		if ($productFieldsShown)
		{
			$fieldSet->bulkSet($productFields, $productFieldsShown);
		}

		return $this;
	}

	/**
	 * @param array $email
	 *
	 * @return \DBTech\eCommerce\Service\Product\Create
	 */
	public function setWelcomeEmail(array $email): Create
	{
		$product = $this->product;

		$welcomeEmail = $product->getWelcomeEmail();
		$welcomeEmail->bulkSet($email);

		$product->addCascadedSave($welcomeEmail);

		return $this;
	}

	/**
	 * @param array $costs
	 *
	 * @return $this
	 */
	public function setProductCosts(array $costs): Create
	{
		$this->costs = $costs;

		return $this;
	}

	/**
	 * @param int $costId
	 * @param array $cost
	 *
	 * @return $this
	 */
	public function addProductCost(int $costId, array $cost): Create
	{
		$this->costs[$costId] = $cost;

		return $this;
	}

	/**
	 * @param bool $logIp
	 *
	 * @return $this
	 */
	public function logIp(bool $logIp): Create
	{
		$this->logIp = $logIp;

		return $this;
	}

	/**
	 *
	 */
	public function checkForSpam()
	{
		if ($this->product->product_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->descriptionPreparer->checkForSpam();
			$this->specificationPreparer->checkForSpam();
			$this->copyrightPreparer->checkForSpam();
		}
	}

	/**
	 * @throws \Exception
	 */
	protected function finalSetup()
	{
		/** @var \DBTech\eCommerce\Entity\Product product */
		$product = $this->product;
		
		if (!$product->hasLicenseFunctionality())
		{
			// Small hack to avoid having to implement required/unique checks separately
			$product->license_prefix = 'L-PH-' . \XF::generateRandomString(30) . '-';
		}
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function _validate(): array
	{
		$this->finalSetup();

		/** @var \DBTech\eCommerce\Entity\Product product */
		$product = $this->product;

		if (!$product->user_id)
		{
			/** @var \XF\Validator\Username $validator */
			$validator = $this->app->validator('Username');
			$product->username = $validator->coerceValue($product->username);

			if ($this->performValidations && !$validator->isValid($product->username, $error))
			{
				return [
					$validator->getPrintableErrorValue($error)
				];
			}
		}

		if (!count($this->costs))
		{
			return [
				\XF::phraseDeferred('dbtech_ecommerce_you_must_specify_at_lease_one_pricing_tier')
			];
		}

		if ($this->parentProductId && $product->Parent->isAddOn())
		{
			return [
				\XF::phraseDeferred('dbtech_ecommerce_please_select_valid_parent_product')
			];
		}

		$product->preSave();
		$errors = $product->getErrors();

		if ($this->performValidations)
		{
			if (!$product->prefix_id
				&& $this->category->require_prefix
				&& $this->category->getUsablePrefixes()
			) {
				$errors[] = \XF::phraseDeferred('please_select_a_prefix');
			}

			if ($this->tagChanger->canEdit())
			{
				$tagErrors = $this->tagChanger->getErrors();
				if ($tagErrors)
				{
					$errors = array_merge($errors, $tagErrors);
				}
			}
		}

		return $errors;
	}
	
	/**
	 * @return \DBTech\eCommerce\Entity\Product
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): \DBTech\eCommerce\Entity\Product
	{
		$product = $this->product;

		$db = $this->db();
		$db->beginTransaction();

		$this->beforeInsert();
		$this->descriptionPreparer->beforeInsert();
		$this->specificationPreparer->beforeInsert();
		$this->copyrightPreparer->beforeInsert();

		$product->save(true, false);

		$this->afterInsert();
		$this->descriptionPreparer->afterInsert();
		$this->specificationPreparer->afterInsert();
		$this->copyrightPreparer->afterInsert();

		if ($this->tagChanger->canEdit())
		{
			$this->tagChanger
				->setContentId($product->product_id, true)
				->save($this->performValidations);
		}

		$db->commit();

		return $product;
	}

	/**
	 * @param \XF\Entity\Forum $forum
	 *
	 * @return \XF\Service\Thread\Creator
	 * @throws \Exception
	 */
	protected function setupProductThreadCreation(\XF\Entity\Forum $forum): \XF\Service\Thread\Creator
	{
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setIsAutomated();

		$creator->setContent($this->product->getExpectedThreadTitle(), $this->getThreadMessage(), false);
		$creator->setPrefix($this->category->thread_prefix_id);

		$thread = $creator->getThread();
		$thread->bulkSet([
			'discussion_type' => 'dbtech_ecommerce_product',
			'discussion_state' => $this->product->product_state
		]);

		return $creator;
	}

	/**
	 * @return string
	 */
	protected function getThreadMessage(): string
	{
		$product = $this->product;

		$phraseParams = [
			'title' => $product->title,
			'tag_line' => $product->tagline,
			'username' => $product->User ? $product->User->username : $product->username,
			'product_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $product),
		];

		$phraseParams['description'] = $this->app->bbCode()->render(
			$product->description_full,
			'bbCodeClean',
			'post',
			null
		);

		$phraseParams['extendedInfo'] = $this->app->bbCode()->render(
			$product->product_specification,
			'bbCodeClean',
			'post',
			null
		);

		$phrase = \XF::phrase('dbtech_ecommerce_product_thread_create', $phraseParams);

		return $phrase->render('raw');
	}

	/**
	 * @param \XF\Entity\Thread $thread
	 */
	protected function afterProductThreadCreated(\XF\Entity\Thread $thread)
	{
		/** @var \XF\Repository\Thread $threadRepo */
		$threadRepo = $this->repository('XF:Thread');
		$threadRepo->markThreadReadByVisitor($thread);

		/** @var \XF\Repository\ThreadWatch $threadWatchRepo */
		$threadWatchRepo = $this->repository('XF:ThreadWatch');
		$threadWatchRepo->autoWatchThread($thread, \XF::visitor(), true);
	}

	/**
	 *
	 */
	public function sendNotifications()
	{
		if ($this->product->isVisible())
		{
			/** @var \DBTech\eCommerce\Service\Product\Notify $notifier */
			$notifier = $this->service('DBTech\eCommerce:Product\Notify', $this->product);
			$notifier->setMentionedUserIds($this->_getMentionedUserIds());
			$notifier->notifyAndEnqueue(3);
		}

		if ($this->threadCreator)
		{
			$this->threadCreator->sendNotifications();
		}
	}

	/**
	 * @return array
	 */
	protected function _getMentionedUserIds(): array
	{
		return array_merge(
			$this->descriptionPreparer->getMentionedUserIds(),
			$this->specificationPreparer->getMentionedUserIds(),
			$this->copyrightPreparer->getMentionedUserIds()
		);
	}

	/**
	 *
	 */
	public function beforeInsert()
	{
	}
	
	/**
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function afterInsert()
	{
		$category = $this->category;
		$product = $this->product;

		$tagline = $product->getMasterTaglinePhrase();
		$tagline->phrase_text = $this->tagline;
		$tagline->save();

		$description = $product->getMasterDescriptionPhrase();
		$description->phrase_text = $this->description;
		$description->save();

		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}

		$this->associateOrderFields($this->availableFields);
		$this->associateProductCosts($this->costs);
		
		if ($this->availableFilters)
		{
			$this->associateProductFilters($this->availableFilters);
		}
		
		if ($this->shippingZones)
		{
			$this->associateShippingZones($this->shippingZones);
		}

		// Digital downloads aren't really active until the first download is added so only do this for other products
		if (
			!$product->hasDownloadFunctionality()
			&& $category->thread_node_id
			&& $category->ThreadForum
		) {
			$creator = $this->setupProductThreadCreation($category->ThreadForum);
			if ($creator && $creator->validate())
			{
				$thread = $creator->save();
				$product->fastUpdate('discussion_thread_id', $thread->thread_id);
				$this->threadCreator = $creator;

				$this->afterProductThreadCreated($thread);
			}
		}

		if ($this->logIp)
		{
			$ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
			$this->writeIpLog($ip);
		}

		/** @var \XF\Spam\ContentChecker $checker */
		$checker = $this->app->spam()->contentChecker();
		$checker->logContentSpamCheck('dbtech_ecommerce_product', $product->product_id);
		$checker->logSpamTrigger('dbtech_ecommerce_product', $product->product_id);
	}
	
	/**
	 * @param string $hash
	 * @throws \LogicException
	 */
	protected function associateAttachments(string $hash)
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->product;

		/** @var \XF\Service\Attachment\Preparer $inserter */
		$inserter = $this->service('XF:Attachment\Preparer');
		$associated = $inserter->associateAttachmentsWithContent($hash, 'dbtech_ecommerce_product', $product->product_id);
		if ($associated)
		{
			$product->fastUpdate('attach_count', $product->attach_count + $associated);
		}
	}

	/**
	 * @param array $fieldIds
	 */
	protected function associateOrderFields(array $fieldIds)
	{
		/** @var \DBTech\eCommerce\Repository\OrderFieldMap $repo */
		$repo = $this->repository('DBTech\eCommerce:OrderFieldMap');
		$repo->updateContentAssociations($this->product->product_id, $fieldIds);
	}
	
	/**
	 * @param array $filterIds
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function associateProductFilters(array $filterIds)
	{
		/** @var \DBTech\eCommerce\Repository\ProductFilterMap $repo */
		$repo = $this->repository('DBTech\eCommerce:ProductFilterMap');
		$repo->updateProductAssociations($this->product->product_id, $filterIds);
	}
	
	/**
	 * @param array $shippingZoneIds
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function associateShippingZones(array $shippingZoneIds)
	{
		/** @var \DBTech\eCommerce\Repository\ShippingZoneProductMap $repo */
		$repo = $this->repository('DBTech\eCommerce:ShippingZoneProductMap');
		$repo->updateProductAssociations($this->product->product_id, $shippingZoneIds);
	}
	
	/**
	 * @param array $costs
	 *
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function associateProductCosts(array $costs)
	{
		/** @var \DBTech\eCommerce\Repository\ProductCost $repo */
		$repo = $this->repository('DBTech\eCommerce:ProductCost');
		$repo->updateContentAssociations($this->product->product_id, $costs);
	}
	
	/**
	 * @param string $ip
	 * @throws \LogicException
	 */
	protected function writeIpLog(string $ip)
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->product;

		/** @var \XF\Repository\Ip $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		$ipEnt = $ipRepo->logIp($product->user_id, $ip, 'dbtech_ecommerce_product', $product->product_id);
		if ($ipEnt)
		{
			$product->fastUpdate('ip_id', $ipEnt->ip_id);
		}
	}
}