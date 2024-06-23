<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class Distributor
 * @package DBTech\eCommerce\Admin\Controller
 */
class Distributor extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomDistributor');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$distributors = $this->getDistributorRepo()->findDistributorsForList()->fetch();

		$viewParams = [
			'distributors' => $distributors,
		];
		return $this->view('DBTech\eCommerce:Distributor\Listing', 'dbtech_ecommerce_distributor_list', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Distributor $distributor
	 * @return \XF\Mvc\Reply\View
	 */
	protected function distributorAddEdit(\DBTech\eCommerce\Entity\Distributor $distributor): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'distributor' => $distributor,
			'nextCounter' => count($distributor->available_products) + 1,
		];
		return $this->view('DBTech\eCommerce:Distributor\Edit', 'dbtech_ecommerce_distributor_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Distributor $distributor */
		$distributor = $this->assertDistributorExists($params->user_id);
		return $this->distributorAddEdit($distributor);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		$productRepo = $this->getProductRepo();
		if (!$productRepo->findProductsForList()->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_product_before_continuing')));
		}
		
		/** @var \DBTech\eCommerce\Entity\Distributor $distributor */
		$distributor = $this->em()->create('DBTech\eCommerce:Distributor');
		
		return $this->distributorAddEdit($distributor);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Distributor $distributor
	 *
	 * @return FormAction
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function distributorSaveProcess(\DBTech\eCommerce\Entity\Distributor $distributor): FormAction
	{
		$form = $this->formAction();
		
		$input = $this->filter([
			'license_length_amount' => 'uint',
			'license_length_unit' => 'str',
		]);
		
		$userName = $this->filter('username', 'str');
		
		if ($userName)
		{
			/** @var \XF\Entity\User $user **/
			$user = $this->finder('XF:User')->where('username', $userName)->fetchOne();
			if (!$user)
			{
				throw $this->exception($this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName])));
			}
			$input['user_id'] = $user->user_id;
		}
		
		$form->basicEntitySave($distributor, $input);
		
		$availableProducts = [];
		$args = $this->filter('available_products', 'array');
		foreach ($args AS $arg)
		{
			if (empty($arg['product_id']))
			{
				continue;
			}
			$availableProducts[] = $this->filterArray($arg, [
				'product_id' => 'uint',
				'available_licenses' => 'int'
			]);
		}

		$form->complete(function () use ($distributor, $availableProducts)
		{
			/** @var \DBTech\eCommerce\Repository\ProductDistributor $repo */
			$repo = $this->repository('DBTech\eCommerce:ProductDistributor');
			$repo->updateContentAssociations($distributor->user_id, $availableProducts);
		});
		
		return $form;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		$this->assertPostOnly();
		
		if ($params->user_id)
		{
			/** @var \DBTech\eCommerce\Entity\Distributor $distributor */
			$distributor = $this->assertDistributorExists($params->user_id);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Distributor $distributor */
			$distributor = $this->em()->create('DBTech\eCommerce:Distributor');
		}
		
		$this->distributorSaveProcess($distributor)->run();

		return $this->redirect($this->buildLink('dbtech-ecommerce/distributors') . $this->buildLinkHash($distributor->user_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$distributor = $this->assertDistributorExists($params->user_id);
		
		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$distributor,
			$this->buildLink('dbtech-ecommerce/distributors/delete', $distributor),
			$this->buildLink('dbtech-ecommerce/distributors/edit', $distributor),
			$this->buildLink('dbtech-ecommerce/distributors'),
			$distributor->User->username
		);
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Distributor
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertDistributorExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Distributor
	{
		return $this->assertRecordExists('DBTech\eCommerce:Distributor', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Distributor|\XF\Mvc\Entity\Repository
	 */
	protected function getDistributorRepo()
	{
		return $this->repository('DBTech\eCommerce:Distributor');
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