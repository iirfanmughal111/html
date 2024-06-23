<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Account
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Account extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertRegistrationRequired();
	}
	
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\Account $accountPlugin */
		$accountPlugin = $this->plugin('DBTech\eCommerce:Account');
		$listParams = $accountPlugin->getCoreListData();
		
		$this->assertValidPage(
			$listParams['page'],
			$listParams['perPage'],
			$listParams['total'],
			'dbtech-ecommerce/account'
		);
		$this->assertCanonicalUrl($this->buildLink(
			'dbtech-ecommerce/account',
			null,
			['page' => $listParams['page']]
		));
		
		$viewParams = [
		
		];
		$viewParams += $listParams;

		return $this->view('DBTech\eCommerce:Account\Index', 'dbtech_ecommerce_account_order_list', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionOrder(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$order = $this->assertValidOrder($params->order_id);
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view('DBTech\eCommerce:Account\Order', 'dbtech_ecommerce_account_order', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionFilters()
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\Account $accountPlugin */
		$accountPlugin = $this->plugin('DBTech\eCommerce:Account');
		
		return $accountPlugin->actionFilters();
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionAddressBook()
	{
		if ($this->isPost())
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce/account/address-book'));
		}
		
		$viewParams = [
			'addresses' => $this->getAddressRepo()
				->findAddressesForList()
				->fetch()
		];
		
		return $this->view('DBTech\eCommerce:Account\Index', 'dbtech_ecommerce_account_address_list', $viewParams);
	}
	
	
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAddressView(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Address $address */
		$address = $this->assertAddressExists($params->address_id);
		
		$viewParams = [
			'address' => $address
		];
		return $this->view('DBTech\eCommerce:Address\View', 'dbtech_ecommerce_account_address_view', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Address $address
	 * @return \XF\Mvc\Reply\View
	 */
	protected function addressAddEdit(\DBTech\eCommerce\Entity\Address $address): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'address' => $address
		];
		return $this->view('DBTech\eCommerce:Address\Edit', 'dbtech_ecommerce_account_address_edit', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAddressEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Address $address */
		$address = $this->assertAddressExists($params->address_id);
		
		if (!$address->canEdit($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		return $this->addressAddEdit($address);
	}
	
	/**
	 * @return \DBTech\eCommerce\Service\Address\Create
	 */
	protected function setupAddressCreate(): \DBTech\eCommerce\Service\Address\Create
	{
		/** @var \DBTech\eCommerce\Service\Address\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Address\Create');
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'business_title' => 'str',
			'business_co' => 'str',
			'address1' => 'str',
			'address2' => 'str',
			'address3' => 'str',
			'address4' => 'str',
			'country_code' => 'str',
			'sales_tax_id' => 'str',
			'is_default' => 'bool',
		]);
		$creator->getAddress()->bulkSet($bulkInput);
		
		return $creator;
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAddressAdd(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Address $address */
		$address = $this->em()->create('DBTech\eCommerce:Address');
		
		return $this->addressAddEdit($address);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Address $address
	 *
	 * @return \DBTech\eCommerce\Service\Address\Edit
	 */
	protected function setupAddressEdit(\DBTech\eCommerce\Entity\Address $address): \DBTech\eCommerce\Service\Address\Edit
	{
		/** @var \DBTech\eCommerce\Service\Address\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Address\Edit', $address);
		$editor->setSendAlert(false);
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'business_title' => 'str',
			'business_co' => 'str',
			'address1' => 'str',
			'address2' => 'str',
			'address3' => 'str',
			'address4' => 'str',
			'country_code' => 'str',
			'sales_tax_id' => 'str',
			'is_default' => 'bool',
		]);
		$editor->getAddress()->bulkSet($bulkInput);
		
		return $editor;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAddressSave(ParameterBag $params)
	{
		$this->assertPostOnly();
		
		if ($params->address_id)
		{
			/** @var \DBTech\eCommerce\Entity\Address $address */
			$address = $this->assertAddressExists($params->address_id);
			
			if (!$address->canEdit($error))
			{
				throw $this->exception($this->noPermission($error));
			}
			
			/** @var \DBTech\eCommerce\Service\Address\Edit $editor */
			$editor = $this->setupAddressEdit($address);
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
		}
		else
		{
			/** @var \DBTech\eCommerce\Service\Address\Create $creator */
			$creator = $this->setupAddressCreate();
			
			if (!$creator->validate($errors))
			{
				throw $this->exception($this->error($errors));
			}
			
			/** @var \DBTech\eCommerce\Entity\Address $address */
			$address = $creator->save();
		}
		
		
		return $this->redirect($this->buildLink('dbtech-ecommerce/account/address-book') . $this->buildLinkHash($address->address_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionAddressDelete(ParameterBag $params)
	{
		$address = $this->assertAddressExists($params->address_id);
		
		if (!$address->canDelete($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Address\Delete $deleter */
			$deleter = $this->service('DBTech\eCommerce:Address\Delete', $address);
			$deleter->delete();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/account/address-book'));
		}

		$viewParams = [
			'address' => $address
		];
		return $this->view('DBTech\eCommerce:Address\Delete', 'dbtech_ecommerce_account_address_delete', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionGetAddressDescription(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\DescLoaderWithPermission $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:DescLoaderWithPermission');
		return $plugin->actionLoadDescription('DBTech\eCommerce:Address');
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionOrderEdit(ParameterBag $params)
	{
		$order = $this->assertValidOrder($params->order_id);
		
		if ($this->isPost())
		{
			$addressId = $this->filter('address_id', 'uint');
			if ($addressId)
			{
				/** @var \DBTech\eCommerce\Entity\Address $address */
				$address = $this->assertAddressExists($addressId);
			}
			else
			{
				/** @var \DBTech\eCommerce\Service\Address\Create $creator */
				$creator = $this->setupAddressCreate();
				
				if (!$creator->validate($errors))
				{
					throw $this->exception($this->error($errors));
				}
				
				/** @var \DBTech\eCommerce\Entity\Address $address */
				$address = $creator->save();
			}
			
			$order->fastUpdate('address_id', $address->address_id);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/account'));
		}
		
		$viewParams = [
			'order' => $order,
			'addresses' => $this->getAddressRepo()->getAddressTitlePairs()
		];
		return $this->view('DBTech\eCommerce:Account\Order\Edit', 'dbtech_ecommerce_account_order_edit', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionInvoice(ParameterBag $params)
	{
		$order = $this->assertValidOrder($params->order_id);
		if (!$order->isCompleted())
		{
			throw $this->exception($this->noPermission());
		}
		
		/** @var \DBTech\eCommerce\Service\Order\Invoice $invoicer */
		$invoicer = \XF::app()->service('DBTech\eCommerce:Order\Invoice', $order, $order->User);
		$invoicer->generate();
		
		$this->setResponseType('raw');
		
		$viewParams = [
			'order' => $order,
			'filename' => $invoicer->getInvoiceFileName(),
			'abstractPath' => $invoicer->getInvoiceAbstractPath()
		];
		return $this->view('DBTech\eCommerce:Account\Invoice\View', '', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\AbstractReply
	 */
	public function actionApiKey(): \XF\Mvc\Reply\AbstractReply
	{
		if (!\XF::options()->dbtechEcommerceEnableApi)
		{
			return $this->notFound();
		}

		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->DBTechEcommerceApiKey)
		{
			$apiKey = $this->em()->create('XF:ApiKey');
			$apiKey->setOption('dbtech_ecommerce_is_automated', true);
			
			/** @var \XF\Service\ApiKey\Manager $keyManager */
			$keyManager = $this->service('XF:ApiKey\Manager', $apiKey);
			
			$keyManager->setTitle('[eCommerce] User ID: ' . $visitor->user_id);
			$keyManager->setActive(true);
			$keyManager->setScopes(false, [
				'dbtech_ecommerce_download:read',
				'dbtech_ecommerce_license:read',
				'dbtech_ecommerce_product:read',
			]);
			$keyManager->setKeyType('user', $visitor);
			
			if (!$keyManager->validate($errors))
			{
				return $this->error($errors);
			}
			
			$keyManager->save();
			
			$visitor->fastUpdate('dbtech_ecommerce_api_key', $apiKey->api_key_id);
			$visitor->hydrateRelation('DBTechEcommerceApiKey', $apiKey);
		}
		
		return $this->view('DBTech\eCommerce:Account\ApiKey', 'dbtech_ecommerce_account_api_key');
	}

	/**
	 * @param array $activities
	 *
	 * @return bool|\XF\Phrase
	 */
	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('dbtech_ecommerce_viewing_account');
	}

	/**
	 * @param int|null $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Address
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertAddressExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Address
	{
		/** @var \DBTech\eCommerce\Entity\Address $record */
		$record = $this->assertRecordExists('DBTech\eCommerce:Address', $id, $with, $phraseKey);
		
		if ($record->user_id != \XF::visitor()->user_id)
		{
			if (!$phraseKey)
			{
				$phraseKey = 'requested_page_not_found';
			}
			
			throw $this->exception(
				$this->notFound(\XF::phrase($phraseKey))
			);
		}
		
		return $record;
	}


	/**
	 * @param int|null $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Order
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertValidOrder(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Order
	{
		/** @var \DBTech\eCommerce\Entity\Order $order */
		$order = $this->assertRecordExists('DBTech\eCommerce:Order', $id, $with, $phraseKey);
		
		if ($order->user_id != \XF::visitor()->user_id)
		{
			if (!$phraseKey)
			{
				$phraseKey = 'requested_page_not_found';
			}
			
			throw $this->exception(
				$this->notFound(\XF::phrase($phraseKey))
			);
		}
		
		return $order;
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Address|\XF\Mvc\Entity\Repository
	 */
	protected function getAddressRepo()
	{
		return $this->repository('DBTech\eCommerce:Address');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Order|\XF\Mvc\Entity\Repository
	 */
	protected function getOrderRepo()
	{
		return $this->repository('DBTech\eCommerce:Order');
	}
}