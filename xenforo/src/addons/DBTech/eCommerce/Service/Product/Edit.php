<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Edit
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;

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

	/** @var string */
	protected $attachmentHash;

	/** @var array */
	protected $availableFields;
	
	/** @var array */
	protected $availableFilters;
	
	/** @var array */
	protected $shippingZones;

	/** @var array */
	protected $costs;

	/** @var bool */
	protected $logIp = true;
	
	/** @var \XF\Service\Tag\Changer */
	protected $tagChanger;
	
	/** @var bool */
	protected $performValidations = true;

	/** @var bool */
	protected $alert = false;

	/** @var string */
	protected $alertReason = '';
	
	
	/**
	 * Edit constructor.
	 *
	 * @param \XF\App $app
	 * @param Product $product
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);
		$this->product = $product;
		$this->setupDefaults();
	}
	
	/**
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function setupDefaults()
	{
		$this->descriptionPreparer = $this->service('DBTech\eCommerce:Product\MessagePreparer', $this->product, 'description_full', $this);
		$this->specificationPreparer = $this->service('DBTech\eCommerce:Product\MessagePreparer', $this->product, 'product_specification', $this);
		$this->copyrightPreparer = $this->service('DBTech\eCommerce:Product\MessagePreparer', $this->product, 'copyright_info', $this);
		
		$this->tagChanger = $this->service('XF:Tag\Changer', 'dbtech_ecommerce_product', $this->product);
		$this->tagChanger->setContentId($this->product->product_id);
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Product
	 */
	public function getProduct(): Product
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
	public function setPerformValidations(bool $perform): Edit
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
	public function setIsAutomated(): Edit
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
	public function setAttachmentHash(string $hash): Edit
	{
		$this->attachmentHash = $hash;

		return $this;
	}

	/**
	 * @param string $tagline
	 *
	 * @return $this
	 */
	public function setTagLine(string $tagline): Edit
	{
		$this->tagline = $tagline;

		return $this;
	}

	/**
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setDescription(string $description): Edit
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
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle(string $title): Edit
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
	public function setPrefix(int $prefixId): Edit
	{
		$this->product->prefix_id = $prefixId;

		return $this;
	}

	/**
	 * @param string $tags
	 *
	 * @return $this
	 */
	public function setTags(string $tags): Edit
	{
		if ($this->tagChanger->canEdit() || !$this->performValidations)
		{
			$this->tagChanger->setEditableTags($tags);
		}

		return $this;
	}

	/**
	 * @param string $tags
	 *
	 * @return $this
	 */
	public function addTags(string $tags): Edit
	{
		if ($this->tagChanger->canEdit())
		{
			$this->tagChanger->addTags($tags);
		}

		return $this;
	}

	/**
	 * @param string $tags
	 *
	 * @return $this
	 */
	public function removeTags(string $tags): Edit
	{
		if ($this->tagChanger->canEdit())
		{
			$this->tagChanger->removeTags($tags);
		}

		return $this;
	}

	/**
	 * @param string $requirements
	 *
	 * @return $this
	 */
	public function setRequirements(string $requirements): Edit
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
	public function setVersions(array $versions, array $versionsText): Edit
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
	public function setAvailableFields(array $availableFields): Edit
	{
		$this->availableFields = $availableFields;

		return $this;
	}

	/**
	 * @param array $availableFilters
	 *
	 * @return $this
	 */
	public function setAvailableFilters(array $availableFilters): Edit
	{
		$this->availableFilters = $availableFilters;

		return $this;
	}

	/**
	 * @param array $shippingZones
	 *
	 * @return $this
	 */
	public function setShippingZones(array $shippingZones): Edit
	{
		$this->shippingZones = $shippingZones;

		return $this;
	}

	/**
	 * @param array $productFields
	 * @param bool $subsetUpdate
	 *
	 * @return $this
	 */
	public function setProductFields(array $productFields, bool $subsetUpdate = false): Edit
	{
		$product = $this->product;
		
		$editMode = $product->getFieldEditMode();
		
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $product->product_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, $editMode)
			->filterOnly($product->Category->field_cache);
		
		$productFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
		
		if ($subsetUpdate)
		{
			// only updating the values passed through, so remove anything not present
			foreach ($productFieldsShown AS $k => $fieldName)
			{
				if (!isset($productFields[$fieldName]))
				{
					unset($productFieldsShown[$k]);
				}
			}
		}
		
		if ($productFieldsShown)
		{
			$fieldSet->bulkSet($productFields, $productFieldsShown, $editMode);
		}

		return $this;
	}

	/**
	 * @param array $email
	 *
	 * @return \DBTech\eCommerce\Service\Product\Edit
	 */
	public function setWelcomeEmail(array $email): Edit
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
	public function setProductCosts(array $costs): Edit
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
	public function addProductCost(int $costId, array $cost): Edit
	{
		$this->costs[$costId] = $cost;

		return $this;
	}

	/**
	 * @param bool $logIp
	 *
	 * @return $this
	 */
	public function logIp(bool $logIp): Edit
	{
		$this->logIp = $logIp;

		return $this;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Edit
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

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
		
		if ($this->costs !== null && !count($this->costs))
		{
			return [
				\XF::phraseDeferred('dbtech_ecommerce_you_must_specify_at_lease_one_pricing_tier')
			];
		}

		$product->preSave();
		$errors = $product->getErrors();

		if ($this->performValidations)
		{
			if (!$product->prefix_id
				&& $product->Category->require_prefix
				&& $product->Category->getUsablePrefixes()
				&& !$product->canMove()
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
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): Product
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->product;

		$db = $this->db();
		$db->beginTransaction();

		$this->beforeUpdate();
		$this->descriptionPreparer->beforeUpdate();
		$this->specificationPreparer->beforeUpdate();
		$this->copyrightPreparer->beforeUpdate();

		$product->save(true, false);

		$this->afterUpdate();
		$this->descriptionPreparer->afterUpdate();
		$this->specificationPreparer->afterUpdate();
		$this->copyrightPreparer->afterUpdate();
		
		if ($this->tagChanger->canEdit())
		{
			$this->tagChanger->save($this->performValidations);
		}

		if ($product->isVisible() && $this->alert && $product->user_id != \XF::visitor()->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = $this->repository('DBTech\eCommerce:Product');
			$productRepo->sendModeratorActionAlert($this->product, 'edit', $this->alertReason);
		}

		$db->commit();

		return $product;
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
	public function beforeUpdate()
	{
	}
	
	/**
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function afterUpdate()
	{
		$product = $this->product;

		if ($this->tagline !== null)
		{
			$tagline = $product->getMasterTaglinePhrase();
			$tagline->phrase_text = $this->tagline;
			$tagline->save();
		}

		if ($this->description !== null)
		{
			$description = $product->getMasterDescriptionPhrase();
			$description->phrase_text = $this->description;
			$description->save();
		}

		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}
		
		$this->associateOrderFields($this->availableFields);
		
		if ($this->costs)
		{
			$this->associateProductCosts($this->costs);
		}
		
		if ($this->availableFilters !== null)
		{
			$this->associateProductFilters($this->availableFilters);
		}
		
		if ($this->shippingZones !== null)
		{
			$this->associateShippingZones($this->shippingZones);
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