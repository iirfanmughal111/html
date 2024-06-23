<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class License
 * @package DBTech\eCommerce\Admin\Controller
 */
class License extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomLicense');
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');

		$currentProduct = null;
		$productId = $this->filter('product_id', 'uint');
		if ($productId)
		{
			$criteria['product_id'] = $productId;
			$currentProduct = $this->em()->find('DBTech\eCommerce:Product', $productId);
		}
		elseif (!empty($criteria['product_id']))
		{
			$currentProduct = $this->em()->find('DBTech\eCommerce:Product', $criteria['product_id']);
		}

		$page = $this->filterPage();
		$perPage = 20;

		$searcher = $this->searcher('DBTech\eCommerce:License', $criteria);

		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}

		$searcher->setOrder($order, $direction);

		/** @var \DBTech\eCommerce\Finder\License $finder */
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);

		if ($currentProduct)
		{
			$finder->where('product_id', $currentProduct->product_id);
		}
		
		// Include user info
		$finder->with('User');

		$filter = $this->filter('_xfFilter', [
			'text' => 'str',
			'prefix' => 'bool'
		]);
		if (strlen($filter['text']))
		{
			$finder->searchText($filter['text'], false, $filter['prefix']);
		}

		$total = $finder->total();
		$entries = $finder->fetch();

		$viewParams = [
			'licenses' => $entries,

			'currentProduct' => $currentProduct,
			'products' => $this->getProductRepo()->getFlattenedProductTree(),

			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,

			'criteria' => $searcher->getFilteredCriteria(),
			// 'filter' => $filter['text'],
			'sortOptions' => $searcher->getOrderOptions(),
			'order' => $order,
			'direction' => $direction

		];
		return $this->view('DBTech\eCommerce:License\Listing', 'dbtech_ecommerce_license_list', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\License $license
	 * @return \XF\Mvc\Reply\View
	 */
	protected function licenseAddEdit(\DBTech\eCommerce\Entity\License $license): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'license' => $license,
			'licenseOwner' => $license->exists() && $license->User ? $license->User->username : '',
		];
		return $this->view('DBTech\eCommerce:License\Edit', 'dbtech_ecommerce_license_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$license = $this->assertLicenseExists($params->license_id);
		return $this->licenseAddEdit($license);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\License\Create
	 * @throws \Exception
	 */
	protected function setupLicenseCreate(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\License\Create
	{
		/** @var \DBTech\eCommerce\Service\License\Create $creator */
		$creator = $this->service('DBTech\eCommerce:License\Create', $product);
		$creator->setPerformValidations(false);
		
		$creator->setLicenseFields($this->filter('license_fields', 'array'), 'admin');
		
		$dateInput = $this->filter([
			'purchase_date' => 'datetime',
			'purchase_time' => 'str'
		]);
		$creator->setPurchaseDate($dateInput['purchase_date'], $dateInput['purchase_time']);
		
		$dateInput = $this->filter([
			'length_type' => 'str',
			'length_amount' => 'uint',
			'length_unit' => 'str',
		]);
		$creator->setDuration($dateInput['length_type'], $dateInput['length_amount'], $dateInput['length_unit']);
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\License\Create $creator
	 *
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function finalizeLicenseCreate(\DBTech\eCommerce\Service\License\Create $creator)
	{
		$license = $creator->getLicense();
		$product = $license->Product;
		
		if ($license->user_id != $product->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\ProductWatch $productWatchRepo */
			$productWatchRepo = $this->repository('DBTech\eCommerce:ProductWatch');
			$productWatchRepo->autoWatchProduct($product, $license->User);
		}
		
		$creator->sendNotifications();
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd()
	{
		$productRepo = $this->getProductRepo();
		if (!$productRepo->findProductsForList()->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_product_before_continuing')));
		}
		
		$productName = $this->filter('product', 'str');
		$productId = $this->filter('product_id', 'uint');
		
		if ($productName)
		{
			/** @var \DBTech\eCommerce\Finder\Product $finder */
			$finder = $this->finder('DBTech\eCommerce:Product');
			$finder->searchText($productName, false, false, true);
			
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $finder->fetchOne();
			if ($product)
			{
				return $this->redirect($this->buildLink('dbtech-ecommerce/licenses/add', null, ['product_id' => $product->product_id]));
			}
		}
		elseif ($productId)
		{
			$product = $this->em()->find('DBTech\eCommerce:Product', $productId);
			if ($product)
			{
				/** @var \DBTech\eCommerce\Entity\License $license */
				$license = $this->em()->create('DBTech\eCommerce:License');
				$license->product_id = $product->product_id;
				
				$license->hydrateRelation('Product', $product);
				
				return $this->licenseAddEdit($license);
			}
		}
		
		return $this->view('DBTech\eCommerce:License\AddChooser', 'dbtech_ecommerce_license_add_chooser');
	}

	/**
	 * @param \DBTech\eCommerce\Entity\License $license
	 *
	 * @return \DBTech\eCommerce\Service\License\Edit
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupLicenseEdit(\DBTech\eCommerce\Entity\License $license): \DBTech\eCommerce\Service\License\Edit
	{
		/** @var \DBTech\eCommerce\Service\License\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:License\Edit', $license);
		
		$editor->setLicenseFields($this->filter('license_fields', 'array'), 'admin');
		
		$dateInput = $this->filter([
			'purchase_date' => 'datetime',
			'purchase_time' => 'str'
		]);
		$editor->setPurchaseDate($dateInput['purchase_date'], $dateInput['purchase_time']);
		
		$dateInput = $this->filter([
			'length_type' => 'str',
			'expiry_date' => 'datetime',
			'expiry_time' => 'str'
		]);
		$editor->setExpiryDate($dateInput['length_type'], $dateInput['expiry_date'], $dateInput['expiry_time']);
		
		if ($this->filter('author_alert', 'bool') && $license->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\License\Edit $editor
	 */
	protected function finalizeLicenseEdit(\DBTech\eCommerce\Service\License\Edit $editor)
	{
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();
		
		if ($params->license_id)
		{
			$license = $this->assertLicenseExists($params->license_id);
			
			$productId = $this->filter('product_id', 'uint');
			if ($productId != $license->product_id)
			{
				/** @var \DBTech\eCommerce\Entity\Product $product */
				$product = $this->em()->find('DBTech\eCommerce:Product', $productId);
				if (!$product)
				{
					throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_requested_product_not_found')));
				}
				
				$oldUserId = $license->user_id;
				
				// We want to mark this license as no longer being valid
				// The reason we do this instead of just changing product_id is to
				// maintain consistency with download logs
				$reason = \XF::phrase('dbtech_ecommerce_replacement_license_issued')->render();
				
				/** @var \DBTech\eCommerce\Service\License\Delete $deleter */
				$deleter = $this->service('DBTech\eCommerce:License\Delete', $license);
				$deleter->setSendAlert(true, $reason);
				$deleter->delete('soft', $reason);
				
				/** @var \XF\ControllerPlugin\InlineMod $inlineModPlugin */
				$inlineModPlugin = $this->plugin('XF:InlineMod');
				$inlineModPlugin->clearIdFromCookie('dbtech_ecommerce_license', $license->license_id);
				
				/** @var \DBTech\eCommerce\Entity\License $license */
				$license = $product->getNewLicense();
				$license->user_id = $oldUserId;
			}
			
			/** @var \DBTech\eCommerce\Service\License\Edit $editor */
			$editor = $this->setupLicenseEdit($license);
			//			$editor->checkForSpam();
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
			$this->finalizeLicenseEdit($editor);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/licenses') . $this->buildLinkHash($license->license_id));
		}
		
		$productId = $this->filter('product_id', 'uint');
		$product = $this->assertProductExists($productId);
		
		$userName = $this->filter('username', 'str');
		
		/** @var \XF\Entity\User $user **/
		$user = $this->finder('XF:User')->where('username', $userName)->fetchOne();
		if (!$user)
		{
			throw $this->exception($this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName])));
		}
		
		$license = \XF::asVisitor($user, function () use ($product): \DBTech\eCommerce\Entity\License
		{
			/** @var \DBTech\eCommerce\Service\License\Create $creator */
			$creator = $this->setupLicenseCreate($product);
			//			$creator->checkForSpam();
			
			if (!$creator->validate($errors))
			{
				throw $this->exception($this->error($errors));
			}
		
			/** @var \DBTech\eCommerce\Entity\License $license */
			$license = $creator->save();
			$this->finalizeLicenseCreate($creator);
			
			return $license;
		});
		
		return $this->redirect($this->buildLink('dbtech-ecommerce/licenses') . $this->buildLinkHash($license->license_id));
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
		$license = $this->assertLicenseExists($params->license_id);
		$product = $license->Product;
		
		if ($this->isPost())
		{
			/** @var \XF\Entity\User $user */
			$user = $this->em()->findOne('XF:User', ['username' => $this->filter('username', 'str')]);
			if (!$user)
			{
				return $this->error(\XF::phrase('requested_user_not_found'));
			}
			
			$canTargetView = \XF::asVisitor($user, function () use ($product): bool
			{
				return $product->canView();
			});
			if (!$canTargetView)
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_new_owner_must_be_able_to_view_this_license'));
			}
			
			/** @var \DBTech\eCommerce\Service\License\Reassign $reassigner */
			$reassigner = $this->service('DBTech\eCommerce:License\Reassign', $license);
			
			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}
			
			$reassigner->reassignTo($user);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/licenses', $license));
		}
		
		$viewParams = [
			'license' => $license,
			'product' => $product
		];
		return $this->view('DBTech\eCommerce:License\Reassign', 'dbtech_ecommerce_license_reassign', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionExtend()
	{
		if ($this->isPost())
		{
			$this->app()->jobManager()->enqueueUnique('dbtechEcomLicenseExtend', 'DBTech\eCommerce:LicenseExtend', [
				'productIds' => array_unique($this->filter('product_ids', 'array-uint')),
				'length_amount' => $this->filter('length_amount', 'uint'),
				'length_unit' => $this->filter('length_unit', 'str'),
				'refresh_expired' => $this->filter('refresh_expired', 'bool')
			]);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/licenses'));
		}
		
		$viewParams = [
			'products' => $this->getProductRepo()->getFlattenedProductTree(),
		];
		return $this->view('DBTech\eCommerce:License\Extend', 'dbtech_ecommerce_license_extend', $viewParams);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$license = $this->assertLicenseExists($params->license_id);
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Delete');
		return $plugin->actionDeleteWithState(
			$license,
			'license_state',
			'DBTech\eCommerce:License\Delete',
			'dbtech_ecommerce_license',
			$this->buildLink('dbtech-ecommerce/licenses/delete', $license),
			$this->buildLink('dbtech-ecommerce/licenses/edit', $license),
			$this->buildLink('dbtech-ecommerce/licenses'),
			$license->title . ' - ' . $license->license_key,
			true,
			true,
			'dbtech_ecommerce_license_delete'
		);
	}
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionView(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$license = $this->assertLicenseExists($params->license_id, ['User']);
		
		$viewParams = [
			'license' => $license
		];
		
		return $this->view('DBTech\eCommerce:License\View', 'dbtech_ecommerce_license_view', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionChangeLog(ParameterBag $params)
	{
		$license = $this->assertLicenseExists($params->license_id);
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \XF\Repository\ChangeLog $changeRepo */
		$changeRepo = $this->repository('XF:ChangeLog');
		$changeFinder = $changeRepo->findChangeLogsByContent('dbtech_ecommerce_license', $license->license_id)->limitByPage($page, $perPage);
		
		/** @var \XF\Entity\ChangeLog[] $changes */
		$changes = $changeFinder->fetch();
		$changeRepo->addDataToLogs($changes);
		
		$viewParams = [
			'license' => $license,
			'changesGrouped' => $changeRepo->groupChangeLogs($changes),
			
			'page' => $page,
			'perPage' => $perPage,
			'total' => $changeFinder->total()
		];
		return $this->view('DBTech\eCommerce:License\ChangeLog', 'dbtech_ecommerce_license_change_log', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getLicenseSearcherParams();

		return $this->view('DBTech\eCommerce:License\Search', 'dbtech_ecommerce_license_search', $viewParams);
	}

	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getLicenseSearcherParams(array $extraParams = []): array
	{
		$searcher = $this->searcher('DBTech\eCommerce:License');

		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		/** @noinspection AdditionOperationOnArraysInspection */
		return $viewParams + $searcher->getFormData() + $extraParams;
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\License
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertLicenseExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\License
	{
		return $this->assertRecordExists('DBTech\eCommerce:License', $id, $with, $phraseKey);
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
	 * @return \DBTech\eCommerce\Repository\License|\XF\Mvc\Entity\Repository
	 */
	protected function getLicenseRepo()
	{
		return $this->repository('DBTech\eCommerce:License');
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
}