<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Address
 * @package DBTech\eCommerce\Admin\Controller
 */
class Address extends AbstractController
{
	/**
	 * @param $action
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomAddress');
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

		$page = $this->filterPage();
		$perPage = 20;

		$searcher = $this->searcher('DBTech\eCommerce:Address', $criteria);

		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}

		$searcher->setOrder($order, $direction);

		/** @var \DBTech\eCommerce\Finder\Address $finder */
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);
		
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
			'addresses' => $entries,

			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,

			'criteria' => $searcher->getFilteredCriteria(),
			// 'filter' => $filter['text'],
			'sortOptions' => $searcher->getOrderOptions(),
			'order' => $order,
			'direction' => $direction

		];
		return $this->view('DBTech\eCommerce:Address\Listing', 'dbtech_ecommerce_address_list', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Address $address
	 * @return \XF\Mvc\Reply\View
	 */
	protected function addressAddEdit(\DBTech\eCommerce\Entity\Address $address): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'address' => $address,
		];
		return $this->view('DBTech\eCommerce:Address\Edit', 'dbtech_ecommerce_address_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$address = $this->assertAddressExists($params->address_id);
		return $this->addressAddEdit($address);
	}
	
	/**
	 * @return \DBTech\eCommerce\Service\Address\Create
	 * @throws \Exception
	 */
	protected function setupAddressCreate(): \DBTech\eCommerce\Service\Address\Create
	{
		/** @var \DBTech\eCommerce\Service\Address\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Address\Create');
		$creator->setPerformValidations(false);
		
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
			'address_state' => 'str',
		]);
		$creator->getAddress()->bulkSet($bulkInput);
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Address\Create $creator
	 */
	protected function finalizeAddressCreate(\DBTech\eCommerce\Service\Address\Create $creator)
	{
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
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
		$editor->setPerformValidations(false);
		
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
			'address_state' => 'str',
		]);
		$editor->getAddress()->bulkSet($bulkInput);
		
		if ($this->filter('author_alert', 'bool') && $address->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Address\Edit $editor
	 */
	protected function finalizeAddressEdit(\DBTech\eCommerce\Service\Address\Edit $editor)
	{
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();
		
		if ($params->address_id)
		{
			$address = $this->assertAddressExists($params->address_id);
			
			/** @var \DBTech\eCommerce\Service\Address\Edit $editor */
			$editor = $this->setupAddressEdit($address);
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
			$this->finalizeAddressEdit($editor);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/addresses') . $this->buildLinkHash($address->address_id));
		}
		
		$userName = $this->filter('username', 'str');
		
		/** @var \XF\Entity\User $user **/
		$user = $this->finder('XF:User')->where('username', $userName)->fetchOne();
		if (!$user)
		{
			throw $this->exception($this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName])));
		}
		
		$address = \XF::asVisitor($user, function (): \DBTech\eCommerce\Entity\Address
		{
			/** @var \DBTech\eCommerce\Service\Address\Create $creator */
			$creator = $this->setupAddressCreate();
			
			if (!$creator->validate($errors))
			{
				throw $this->exception($this->error($errors));
			}
		
			/** @var \DBTech\eCommerce\Entity\Address $address */
			$address = $creator->save();
			$this->finalizeAddressCreate($creator);
			
			return $address;
		});
		
		return $this->redirect($this->buildLink('dbtech-ecommerce/addresses') . $this->buildLinkHash($address->address_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$address = $this->assertAddressExists($params->address_id);
		
		$deleteParams = [];
		if (!$this->isPost())
		{
			$deleteParams['numOrders'] = $address->order_count;
		}
		
		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$address,
			$this->buildLink('dbtech-ecommerce/addresses/delete', $address),
			$this->buildLink('dbtech-ecommerce/addresses/edit', $address),
			$this->buildLink('dbtech-ecommerce/addresses'),
			$address->title,
			'dbtech_ecommerce_address_delete',
			$deleteParams
		);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getAddressSearcherParams();

		return $this->view('DBTech\eCommerce:Address\Search', 'dbtech_ecommerce_address_search', $viewParams);
	}

	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getAddressSearcherParams(array $extraParams = []): array
	{
		$searcher = $this->searcher('DBTech\eCommerce:Address');

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
	 * @return \DBTech\eCommerce\Entity\Address
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertAddressExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Address
	{
		return $this->assertRecordExists('DBTech\eCommerce:Address', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Address|\XF\Mvc\Entity\Repository
	 */
	protected function getAddressRepo()
	{
		return $this->repository('DBTech\eCommerce:Address');
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