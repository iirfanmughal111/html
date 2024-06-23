<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Product
 * @package DBTech\eCommerce\Admin\Controller
 */
class Product extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomProduct');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceProductsPerPage;
//		$perPage = 10;

		$productFinder = $this->getProductRepo()
			->findProductsForList()
			->where('parent_product_id', 0)
			->limitByPage($page, $perPage)
		;

		$filter = $this->filter('_xfFilter', [
			'text' => 'str',
			'prefix' => 'bool'
		]);
		if (strlen($filter['text']))
		{
			$productFinder->whereOr(
				[
					$productFinder->columnUtf8('title'),
					'LIKE',
					$productFinder->escapeLike($filter['text'], $filter['prefix'] ? '?%' : '%?%')
				],
				[
					$productFinder->columnUtf8('MasterDescription.phrase_text'),
					'LIKE',
					$productFinder->escapeLike($filter['text'], $filter['prefix'] ? '?%' : '%?%')

				]
			);
		}

		$total = $productFinder->total();
		$this->assertValidPage($page, $perPage, $total, 'dbtech-ecommerce/products');

		$products = $productFinder->fetch();
		$productIds = $products->keys();

		$products = $products->merge(
			$this->getProductRepo()
				->findProductsForList()
				->where('parent_product_id', $productIds)
				->fetch()
		);

		/** @var \XF\Repository\PermissionEntry $permissionEntryRepo */
		$permissionEntryRepo = $this->repository('XF:PermissionEntry');
		$customPermissions = $permissionEntryRepo->getContentWithCustomPermissions('dbtech_ecommerce_product');

		$viewParams = [
			'tree' => $this->getProductRepo()->createProductTree($products),
			'customPermissions' => $customPermissions,

			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
		];
		return $this->view('DBTech\eCommerce:Product\Listing', 'dbtech_ecommerce_product_list', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionEditIcon(ParameterBag $params)
	{
		$product = $this->assertProductExists($params->product_id);

		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Product\Icon $iconService */
			$iconService = $this->service('DBTech\eCommerce:Product\Icon', $product);

			$action = $this->filter('icon_action', 'str');

			if ($action == 'delete')
			{
				$iconService->deleteIcon();
			}
			elseif ($action == 'custom')
			{
				$upload = $this->request->getFile('upload', false, false);
				if ($upload)
				{
					if (!$iconService->setImageFromUpload($upload))
					{
						return $this->error($iconService->getError());
					}

					if (!$iconService->updateIcon())
					{
						return $this->error(\XF::phrase('dbtech_ecommerce_new_icon_could_not_be_applied_try_later'));
					}
				}
			}

			return $this->redirect($this->buildLink('dbtech-ecommerce/products') . $this->buildLinkHash($product->product_id));
		}
		
		$viewParams = [
			'product' => $product,
		];
		return $this->view('DBTech\eCommerce:Product\EditIcon', 'dbtech_ecommerce_product_edit_icon', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 */
	protected function productAddEdit(\DBTech\eCommerce\Entity\Product $product): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->em()->find('DBTech\eCommerce:Category', $product->product_category_id);

		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = $this->repository('XF:Attachment');
		$attachmentData = $attachmentRepo->getEditorData('dbtech_ecommerce_product', $product->exists() ? $product : $product->Category);
		
		/** @var \DBTech\eCommerce\Repository\OrderField $fieldRepo */
		$fieldRepo = $this->repository('DBTech\eCommerce:OrderField');
		$availableFields = $fieldRepo->findFieldsForList()->fetch();
		
		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');
		
		if ($product->ThreadForum)
		{
			$threadPrefixes = $product->ThreadForum->getPrefixesGrouped();
		}
		else
		{
			$threadPrefixes = [];
		}
		
		if ($product->exists())
		{
			/** @var \XF\Service\Tag\Changer $tagger */
			$tagger = $this->service('XF:Tag\Changer', 'dbtech_ecommerce_product', $product);
			
			$grouped = $tagger->getExistingTagsByEditability();
		}
		else
		{
			$grouped = [
				'editable' => null,
				'uneditable' => null,
			];
		}
		
		$viewParams = [
			'product' => $product,
			'category' => $category,
			
			'forumOptions' => $nodeRepo->getNodeOptionsData(false, 'Forum'),
			'threadPrefixes' => $threadPrefixes,

			'attachmentData' => $attachmentData,
			'prefixes' => $category->getUsablePrefixes($product->prefix_id),
			
			'editableTags' => $grouped['editable'],
			'uneditableTags' => $grouped['uneditable'],
			
			'availableFields' => $availableFields,
			
			'productOwner' => $product->exists() ? $product->User : $this->em()->find('XF:User', $this->options()->dbtechEcommerceDefaultProductOwner),
		];
		return $this->view('DBTech\eCommerce:Product\Edit', 'dbtech_ecommerce_product_edit', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$product = $this->assertProductExists($params->product_id, ['Category', 'User']);
		return $this->productAddEdit($product);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 *
	 * @return \DBTech\eCommerce\Service\Product\Create
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupProductCreate(\DBTech\eCommerce\Entity\Category $category): \DBTech\eCommerce\Service\Product\Create
	{
		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$fullDescription = $editorPlugin->fromInput('description_full');
		$specification = $editorPlugin->fromInput('product_specification');
		$copyright = $editorPlugin->fromInput('copyright_info');
		
		/** @var \DBTech\eCommerce\Service\Product\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Product\Create', $category, $this->filter('product_type', 'str'));
		$creator->setPerformValidations(false);
		
		$product = $creator->getProduct();
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'is_featured' => 'bool',
			'is_discountable' => 'bool',
			'is_listed' => 'bool',
			'welcome_email' => 'bool',
			'is_all_access' => 'bool',
			'all_access_group_ids' => 'array-uint',
			'license_prefix' => 'str',
			'product_type_data' => 'array',
			'has_demo' => 'bool',
			'extra_group_ids' => 'array-uint',
			'temporary_extra_group_ids' => 'array-uint',
			'support_node_id' => 'uint',
			'thread_node_id' => 'uint',
			'thread_prefix_id' => 'uint',
			'branding_free' => 'uint',
			'global_branding_free' => 'uint',
		]);
		$product->bulkSet($bulkInput);
		
		$creator->setParentProduct($this->filter('parent_product_id', 'uint'));
		
		$creator->setTagLine($this->filter('tagline', 'str'));
		$creator->setDescription($this->filter('description', 'str'));
		
		$creator->setContent($fullDescription, $specification, $copyright);
		
		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId && $category->isPrefixUsable($prefixId))
		{
			$creator->setPrefix($prefixId);
		}
		
		$creator->setTags($this->filter('tags', 'str'));
		
		$creator->setRequirements($this->filter('requirements', 'str'));
		
		if (!$product->isAddOn())
		{
			$creator->setVersions(
				$this->filter('product_version', 'array-str'),
				$this->filter('product_version_text', 'array-str')
			);
		}
		
		$creator->setAttachmentHash($this->filter('attachment_hash', 'str'));

		$filterIds = $this->filter('available_filters', 'array-str');
		$creator->setAvailableFilters($filterIds);

		$fieldIds = $this->filter('available_fields', 'array-str');
		$creator->setAvailableFields($fieldIds);

		$welcomeEmail = $this->filter('welcome_email_options', 'array');
		$creator->setWelcomeEmail($welcomeEmail);
		
		$costInput = $this->filter([
			'cost_title' => 'array-str',
			'cost_amount' => 'array-float',
			'renewal_type' => 'array-str',
			'renewal_amount' => 'array-float',
			'length_type' => 'array-str',
			'length_amount' => 'array-str',
			'length_unit' => 'array-str',
			'stock' => 'array-uint',
			'weight' => 'array-float',
			'cost_description' => 'array-str',
			'highlighted' => 'uint'
		]);
		$costIds = array_keys($costInput['cost_amount']);
		
		foreach ($costIds as $costId)
		{
			if ($product->hasLicenseFunctionality())
			{
				$creator->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId] ?? '',
					'cost_amount' => $costInput['cost_amount'][$costId],
					'renewal_type' => $costInput['renewal_type'][$costId] ?? null,
					'renewal_amount' => isset($costInput['renewal_type'][$costId]) && isset($costInput['renewal_amount'][$costId])
						? $costInput['renewal_amount'][$costId]
						: null,
					'stock' => 0,
					'weight' => 0.00,
					'length_amount' => $costInput['length_type'][$costId] == 'permanent'
						? 0
						: $costInput['length_amount'][$costId],
					'length_unit' => $costInput['length_type'][$costId] == 'permanent' ? '' : $costInput['length_unit'][$costId],
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
			else
			{
				$creator->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId],
					'cost_amount' => $costInput['cost_amount'][$costId],
					'stock' => $product->hasStockFunctionality() ? $costInput['stock'][$costId] : 0,
					'weight' => $product->hasWeight() ? $costInput['weight'][$costId] : 0.00,
					'length_amount' => 0,
					'length_unit' => '',
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
		}
		
		$productFields = $this->filter('product_fields', 'array');
		$creator->setProductFields($productFields);
		
		$shippingZones = $this->filter('shipping_zones', 'array-uint');
		$creator->setShippingZones($shippingZones);
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Product\Create $creator
	 */
	protected function finalizeProductCreate(\DBTech\eCommerce\Service\Product\Create $creator)
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $creator->getProduct();
		
		if (!$product->parent_product_id)
		{
			$creator->sendNotifications();
		}
		
		if (\XF::visitor()->user_id)
		{
			if ($product->product_state == 'moderated')
			{
				$this->session()->setHasContentPendingApproval();
			}
		}
	}
	
	/**
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd()
	{
		$categoryRepo = $this->getCategoryRepo();
		$categoryList = $categoryRepo->findCategoryList();
		if (!$categoryList->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_category_before_continuing')));
		}
		
		$copyProductId = $this->filter('source_product_id', 'uint');
		if ($copyProductId)
		{
			$product = $this->assertProductExists($copyProductId);
			
			$copyProduct = $product->toArray(false);
			foreach ([
				'product_id',
				'icon_date'
			] as $key)
			{
				unset($copyProduct[$key]);
			}
			
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $this->em()->create('DBTech\eCommerce:Product');
			$product->bulkSet($copyProduct);
			
			$product->hydrateRelation('Category', $product->Category);
		}
		else
		{
			$categoryId = $this->filter('category_id', 'uint');
			if ($categoryId)
			{
				$category = $this->assertCategoryExists($categoryId);
				
				$productType = $this->filter('product_type', 'str');
				if ($productType)
				{
					if ($productType == 'physical')
					{
						$shippingZoneRepo = $this->getShippingZoneRepo();
						if (!$shippingZoneRepo->findShippingZonesForList()->total())
						{
							throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_shipping_zone_before_continuing')));
						}
					}
					
					/** @var \DBTech\eCommerce\Entity\Product $product */
					$product = $category->getNewProduct($productType);
				}
				else
				{
					$viewParams = [
						'category'   => $category,
					];
					return $this->view('DBTech\eCommerce:Product\AddChooser\Type', 'dbtech_ecommerce_product_add_chooser_type', $viewParams);
				}
			}
			else
			{
				$categoryTree = $categoryRepo->createCategoryTree($categoryList->fetch());
				$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);
				
				$productType = $this->filter('product_type', 'str');
				if ($productType)
				{
					foreach ($categoryExtras as &$extra)
					{
						$extra['product_type'] = $productType;
					}
				}
				
				$viewParams = [
					'categoryTree'   => $categoryTree,
					'categoryExtras' => $categoryExtras
				];
				return $this->view('DBTech\eCommerce:Product\AddChooser', 'dbtech_ecommerce_product_add_chooser', $viewParams);
			}
		}
		
		return $this->productAddEdit($product);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\Product\Edit
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupProductEdit(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\Product\Edit
	{
		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$description = $editorPlugin->fromInput('description_full');
		$specification = $editorPlugin->fromInput('product_specification');
		$copyright = $editorPlugin->fromInput('copyright_info');
		
		/** @var \DBTech\eCommerce\Service\Product\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Product\Edit', $product);
		$editor->setPerformValidations(false);
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'is_featured' => 'bool',
			'is_discountable' => 'bool',
			'is_listed' => 'bool',
			'welcome_email' => 'bool',
			'is_all_access' => 'bool',
			'all_access_group_ids' => 'array-uint',
			'license_prefix' => 'str',
			'product_type_data' => 'array',
			'has_demo' => 'bool',
			'extra_group_ids' => 'array-uint',
			'temporary_extra_group_ids' => 'array-uint',
			'support_node_id' => 'uint',
			'thread_node_id' => 'uint',
			'thread_prefix_id' => 'uint',
			'branding_free' => 'uint',
			'global_branding_free' => 'uint',
		]);
		$editor->getProduct()->bulkSet($bulkInput);
		
		$editor->setTagLine($this->filter('tagline', 'str'));
		$editor->setDescription($this->filter('description', 'str'));
		
		$editor->setContent($description, $specification, $copyright);
		
		$productFields = $this->filter('product_fields', 'array');
		$editor->setProductFields($productFields);

		$welcomeEmail = $this->filter('welcome_email_options', 'array');
		$editor->setWelcomeEmail($welcomeEmail);
		
		$shippingZones = $this->filter('shipping_zones', 'array-uint');
		$editor->setShippingZones($shippingZones);
		
		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId != $product->prefix_id && !$product->Category->isPrefixUsable($prefixId))
		{
			$prefixId = 0; // not usable, just blank it out
		}
		$editor->setPrefix($prefixId);
		
		$editor->setTags($this->filter('tags', 'str'));
		
		$editor->setRequirements($this->filter('requirements', 'str'));
		
		if (!$product->isAddOn())
		{
			$editor->setVersions(
				$this->filter('product_version', 'array-str'),
				$this->filter('product_version_text', 'array-str')
			);
		}
		
		$editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		
		$filterIds = $this->filter('available_filters', 'array-str');
		$editor->setAvailableFilters($filterIds);
		
		$fieldIds = $this->filter('available_fields', 'array-str');
		$editor->setAvailableFields($fieldIds);
		
		$costInput = $this->filter([
			'cost_title' => 'array-str',
			'cost_amount' => 'array-float',
			'renewal_type' => 'array-str',
			'renewal_amount' => 'array-float',
			'length_type' => 'array-str',
			'length_amount' => 'array-str',
			'length_unit' => 'array-str',
			'stock' => 'array-uint',
			'weight' => 'array-float',
			'cost_description' => 'array-str',
			'highlighted' => 'uint'
		]);
		$costIds = array_keys($costInput['cost_amount']);
		
		foreach ($costIds as $costId)
		{
			if ($product->hasLicenseFunctionality())
			{
				$editor->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId] ?? '',
					'cost_amount' => $costInput['cost_amount'][$costId],
					'renewal_type' => $costInput['renewal_type'][$costId] ?? null,
					'renewal_amount' => isset($costInput['renewal_type'][$costId]) && isset($costInput['renewal_amount'][$costId])
						? $costInput['renewal_amount'][$costId]
						: null,
					'stock' => 0,
					'weight' => 0.00,
					'length_amount' => $costInput['length_type'][$costId] == 'permanent' ? 0 : $costInput['length_amount'][$costId],
					'length_unit' => $costInput['length_type'][$costId] == 'permanent' ? '' : $costInput['length_unit'][$costId],
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
			else
			{
				$editor->addProductCost($costId, [
					'title' => $costInput['cost_title'][$costId],
					'cost_amount' => $costInput['cost_amount'][$costId],
					'stock' => $product->hasStockFunctionality() ? $costInput['stock'][$costId] : 0,
					'weight' => $product->hasWeight() ? $costInput['weight'][$costId] : 0.00,
					'length_amount' => 0,
					'length_unit' => '',
					'description' => $costInput['cost_description'][$costId],
					'highlighted' => $costInput['highlighted'] == $costId ? 1 : 0
				]);
			}
		}
		if ($this->filter('author_alert', 'bool') && $product->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->product_id)
		{
			$product = $this->assertProductExists($params->product_id);
			
			$editor = $this->setupProductEdit($product);
			$editor->checkForSpam();
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$editor->save();
			$this->finalizeProductEdit($editor);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/products') . $this->buildLinkHash($product->product_id));
		}
		
		$categoryId = $this->filter('category_id', 'uint');
		$category = $this->assertCategoryExists($categoryId);
		
		$userName = $this->filter('username', 'str');
		
		/** @var \XF\Entity\User $user **/
		$user = $this->finder('XF:User')->where('username', $userName)->fetchOne();
		if (!$user)
		{
			throw $this->exception($this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName])));
		}
		
		$product = \XF::asVisitor($user, function () use ($category): \DBTech\eCommerce\Entity\Product
		{
			$creator = $this->setupProductCreate($category);
			$creator->checkForSpam();
			
			if (!$creator->validate($errors))
			{
				throw $this->exception($this->error($errors));
			}
			
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $creator->save();
			$this->finalizeProductCreate($creator);
			
			return $product;
		});
		
		return $this->redirect($this->buildLink('dbtech-ecommerce/products') . $this->buildLinkHash($product->product_id));
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Product\Edit $editor
	 */
	protected function finalizeProductEdit(\DBTech\eCommerce\Service\Product\Edit $editor)
	{
	}

	/**
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionAddOnAdd()
	{
		$productRepo = $this->getProductRepo();
		if (!$productRepo->findProductsForList()->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_product_before_continuing')));
		}
		
		$productName = $this->filter('product', 'str');
		$parentProductId = $this->filter('parent_product_id', 'uint');
		
		if ($productName)
		{
			/** @var \DBTech\eCommerce\Finder\Product $finder */
			$finder = $this->finder('DBTech\eCommerce:Product');
			$finder->searchText($productName, false, false, true);
			
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$parentProduct = $finder->fetchOne();
			if ($parentProduct)
			{
				return $this->redirect($this->buildLink('dbtech-ecommerce/products/add-on/add', null, ['parent_product_id' => $parentProduct->product_id]));
			}
		}
		elseif ($parentProductId)
		{
			/** @var \DBTech\eCommerce\Entity\Product $parentProduct */
			$parentProduct = $this->em()->find('DBTech\eCommerce:Product', $parentProductId);
			if ($parentProduct && $parentProduct->hasAddonFunctionality() && !$parentProduct->isAddOn())
			{
				/** @var \DBTech\eCommerce\Entity\Product $product */
				$product = $parentProduct->getNewAddOn();
				
				return $this->productAddEdit($product);
			}
		}
		
		return $this->view('DBTech\eCommerce:Product\AddOn\AddChooser', 'dbtech_ecommerce_product_addon_add_chooser');
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function actionReassign(ParameterBag $params)
	{
		$product = $this->assertProductExists($params->product_id);
		
		if ($this->isPost())
		{
			$userName = $this->filter('username', 'str');
			
			/** @var \XF\Entity\User $user */
			$user = $this->em()->findOne('XF:User', ['username' => $userName]);
			if (!$user)
			{
				return $this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName]));
			}
			
			$canTargetView = \XF::asVisitor($user, function () use ($product): bool
			{
				return $product->canView();
			});
			if (!$canTargetView)
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_new_owner_must_be_able_to_view_this_product'));
			}
			
			/** @var \DBTech\eCommerce\Service\Product\Reassign $reassigner */
			$reassigner = $this->service('DBTech\eCommerce:Product\Reassign', $product);
			
			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}
			
			$reassigner->reassignTo($user);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/products', $product) . $this->buildLinkHash($product->product_id));
		}
		
		$viewParams = [
			'product' => $product,
			'category' => $product->Category
		];
		return $this->view('DBTech\eCommerce:Product\Reassign', 'dbtech_ecommerce_product_reassign', $viewParams);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$product = $this->assertProductExists($params->product_id);
		
		$deleteParams = [];
		if (!$this->isPost())
		{
			$deleteParams['numDownloads'] = $this->finder('DBTech\eCommerce:Download')->where('product_id', $product->product_id)->total();
			$deleteParams['numLicenses'] = $this->finder('DBTech\eCommerce:License')->where('product_id', $product->product_id)->total();
			
			$productRepo = $this->getProductRepo();
			$productTree = $productRepo->createProductTree(null, $product->product_id);
			
			$deleteParams['hasChildren'] = (bool)($productTree->countChildren() > 0);
		}
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Delete');
		return $plugin->actionDeleteWithState(
			$product,
			'product_state',
			'DBTech\eCommerce:Product\Delete',
			'dbtech_ecommerce_product',
			$this->buildLink('dbtech-ecommerce/products/delete', $product),
			$this->buildLink('dbtech-ecommerce/products/edit', $product),
			$this->buildLink('dbtech-ecommerce/products'),
			$product->full_title,
			true,
			true,
			'dbtech_ecommerce_product_delete',
			$deleteParams
		);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\Category $category
	 *
	 * @return \DBTech\eCommerce\Service\Product\Move
	 */
	protected function setupProductMove(\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Category $category): \DBTech\eCommerce\Service\Product\Move
	{
		$options = $this->filter([
			'notify_watchers' => 'bool',
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			'prefix_id' => 'uint'
		]);
		
		/** @var \DBTech\eCommerce\Service\Product\Move $mover */
		$mover = $this->service('DBTech\eCommerce:Product\Move', $product);
		
		if ($options['author_alert'])
		{
			$mover->setSendAlert(true, $options['author_alert_reason']);
		}
		
		if ($options['notify_watchers'])
		{
			$mover->setNotifyWatchers();
		}
		
		if ($options['prefix_id'] !== null)
		{
			$mover->setPrefix($options['prefix_id']);
		}
		
		$mover->addExtraSetup(function (\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Category $category)
		{
			$product->title = $this->filter('title', 'str');
		});
		
		return $mover;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionMove(ParameterBag $params)
	{
		$product = $this->assertProductExists($params->product_id);
		
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $product->Category;
		
		if ($this->isPost())
		{
			$targetCategoryId = $this->filter('target_category_id', 'uint');
			
			/** @var \DBTech\eCommerce\Entity\Category $targetCategory */
			$targetCategory = $this->app()->em()->find('DBTech\eCommerce:Category', $targetCategoryId);
			if (!$targetCategory)
			{
				return $this->error(\XF::phrase('requested_category_not_found'));
			}
			
			$this->setupProductMove($product, $targetCategory)->move($targetCategory);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/products', $product) . $this->buildLinkHash($product->product_id));
		}
		
		$viewParams = [
			'product' => $product,
			'category' => $category,
			'prefixes' => $category->getUsablePrefixes(),
			'categoryTree' => $this->getCategoryRepo()->createCategoryTree()
		];
		return $this->view('DBTech\eCommerce:Product\Move', 'dbtech_ecommerce_product_move', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\Product $targetProduct
	 *
	 * @return \DBTech\eCommerce\Service\Product\AddOnMove
	 * @throws \LogicException
	 */
	protected function setupProductAddOnMove(\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Product $targetProduct): \DBTech\eCommerce\Service\Product\AddOnMove
	{
		$options = $this->filter([
			'notify_watchers' => 'bool',
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			'prefix_id' => 'uint'
		]);
		
		/** @var \DBTech\eCommerce\Service\Product\AddOnMove $mover */
		$mover = $this->service('DBTech\eCommerce:Product\AddOnMove', $product);
		
		if ($options['author_alert'])
		{
			$mover->setSendAlert(true, $options['author_alert_reason']);
		}
		
		if ($options['notify_watchers'])
		{
			$mover->setNotifyWatchers();
		}
		
		$mover->addExtraSetup(function (\DBTech\eCommerce\Entity\Product $product, \DBTech\eCommerce\Entity\Product $targetProduct)
		{
			$product->title = $this->filter('title', 'str');
			$product->save();
		});
		
		return $mover;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionAddOnMove(ParameterBag $params)
	{
		$product = $this->assertProductExists($params->product_id);
		
		$productName = $this->filter('product', 'str');
		$targetProductId = $this->filter('target_product_id', 'uint');
		
		if ($productName)
		{
			/** @var \DBTech\eCommerce\Finder\Product $finder */
			$finder = $this->finder('DBTech\eCommerce:Product');
			$finder->searchText($productName, false, false, true);
			
			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $finder->fetchOne();
			if ($targetProduct && $targetProduct->hasAddonFunctionality() && !$targetProduct->isAddOn())
			{
				$this->setupProductAddOnMove($product, $targetProduct)->move($targetProduct);
				
				return $this->redirect($this->buildLink('dbtech-ecommerce/products', $product) . $this->buildLinkHash($product->product_id));
			}
		}
		elseif ($targetProductId)
		{
			/** @var \DBTech\eCommerce\Entity\Product $targetProduct */
			$targetProduct = $this->em()->find('DBTech\eCommerce:Product', $targetProductId);
			if ($targetProduct && $targetProduct->hasAddonFunctionality() && !$targetProduct->isAddOn())
			{
				$this->setupProductAddOnMove($product, $targetProduct)->move($targetProduct);
				
				return $this->redirect($this->buildLink('dbtech-ecommerce/products', $product) . $this->buildLinkHash($product->product_id));
			}
		}
		
		$viewParams = [
			'product' => $product,
			'prefixes' => $product->Category->getUsablePrefixes()
		];
		return $this->view('DBTech\eCommerce:Product\AddOnMove', 'dbtech_ecommerce_product_addon_move', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionCostRow(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		$this->assertPostOnly();

		$product = $params->product_id ? $this->assertProductExists($params->product_id) : null;

		$json = [];

		$delete = $this->filter('delete', 'uint');
		if ($delete)
		{
			$productCost = $this->assertProductCostExists($delete);

			$productCost->delete();

			$json['delete'] = $delete;
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\ProductCost $productCost */
			$productCost = $this->em()->create('DBTech\eCommerce:ProductCost');
			$productCost->set('product_id', $params->product_id ?: 0, ['forceSet' => true]);
			$productCost->product_type = $this->filter('product_type', 'str');
			$productCost->title = '';
			$productCost->cost_amount = 5;
			$productCost->stock = 0;
			$productCost->weight = 0;
			$productCost->length_amount = 0;
			$productCost->length_unit = '';
			$productCost->save();

			$json['cost'] = $productCost->toArray();
		}

		$reply = $this->redirect($this->buildLink('dbtech-ecommerce/products/edit', $product));
		$reply->setJsonParams($json);

		return $reply;
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAutoComplete(): \XF\Mvc\Reply\AbstractReply
	{
		$q = ltrim($this->filter('q', 'str', ['no-trim']));
		
		if ($q !== '' && \mb_strlen($q) >= 2)
		{
			/** @var \DBTech\eCommerce\Finder\Product $productFinder */
			$productFinder = $this->finder('DBTech\eCommerce:Product');
			$productFinder = $productFinder->searchText($q);
			
			$productType = $this->filter('product_type', 'str');
			if ($productType)
			{
				$productFinder->where('product_type', $productType);
			}
			
			$noChildren = $this->filter('no_children', 'bool');
			if ($noChildren)
			{
				$productFinder->where('parent_product_id', 0);
			}
			
			$products = $productFinder->fetch(10);
		}
		else
		{
			$products = [];
			$q = '';
		}
		
		$viewParams = [
			'q' => $q,
			'products' => $products
		];
		return $this->view('DBTech\eCommerce:Product\Find', '', $viewParams);
	}
	
	/**
	 * @return \DBTech\eCommerce\ControllerPlugin\ProductPermission
	 */
	protected function getProductPermissionPlugin(): \DBTech\eCommerce\ControllerPlugin\ProductPermission
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\ProductPermission $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:ProductPermission');
		$plugin->setFormatters('DBTech\eCommerce:Product\Permission%s', 'dbtech_ecommerce_product_permission_%s');
		$plugin->setRoutePrefix('dbtech-ecommerce/products/permissions');
		
		return $plugin;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionPermissions(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		return $this->getProductPermissionPlugin()->actionList($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionPermissionsEdit(ParameterBag $params)
	{
		return $this->getProductPermissionPlugin()->actionEdit($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 */
	public function actionPermissionsSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		return $this->getProductPermissionPlugin()->actionSave($params);
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Product
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertProductExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Product
	{
		return $this->assertRecordExists('DBTech\eCommerce:Product', $id, $with, $phraseKey);
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Category
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCategoryExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Category
	{
		return $this->assertRecordExists('DBTech\eCommerce:Category', $id, $with, $phraseKey);
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\ProductCost
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertProductCostExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\ProductCost
	{
		return $this->assertRecordExists('DBTech\eCommerce:ProductCost', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('DBTech\eCommerce:Category');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\ShippingZone|\XF\Mvc\Entity\Repository
	 */
	protected function getShippingZoneRepo()
	{
		return $this->repository('DBTech\eCommerce:ShippingZone');
	}
}