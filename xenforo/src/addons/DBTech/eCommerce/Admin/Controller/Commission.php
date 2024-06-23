<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class Commission
 * @package DBTech\eCommerce\Admin\Controller
 */
class Commission extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomCommission');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$commissions = $this->getCommissionRepo()->findCommissionsForList()->fetch();

		$viewParams = [
			'commissions' => $commissions,
		];
		return $this->view('DBTech\eCommerce:Commission\Listing', 'dbtech_ecommerce_commission_list', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Commission $commission
	 * @return \XF\Mvc\Reply\View
	 */
	protected function commissionAddEdit(\DBTech\eCommerce\Entity\Commission $commission): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'commission' => $commission,
			'nextCounter' => count($commission->product_commissions) + 1,
		];
		return $this->view('DBTech\eCommerce:Commission\Edit', 'dbtech_ecommerce_commission_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Commission $commission */
		$commission = $this->assertCommissionExists($params->commission_id);
		return $this->commissionAddEdit($commission);
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
		
		/** @var \DBTech\eCommerce\Entity\Commission $commission */
		$commission = $this->em()->create('DBTech\eCommerce:Commission');
		
		return $this->commissionAddEdit($commission);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Commission $commission
	 *
	 * @return FormAction
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function commissionSaveProcess(\DBTech\eCommerce\Entity\Commission $commission): FormAction
	{
		$form = $this->formAction();
		
		$input = $this->filter([
			'name' => 'str',
			'email' => 'str',
		]);
		
		$userName = $this->filter('username', 'str');
		
		if ($userName)
		{
			/** @var \XF\Entity\User $user * */
			$user = $this->finder('XF:User')->where('username', $userName)->fetchOne();
			if (!$user)
			{
				throw $this->exception($this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName])));
			}
			$input['user_id'] = $user->user_id;
		}
		
		$form->basicEntitySave($commission, $input);
		
		$dateInput = $this->filter([
			'date' => 'datetime',
			'time' => 'str',
		]);
		$form->setup(function () use ($dateInput, $commission)
		{
			if (!$dateInput['date'] && !$dateInput['time'])
			{
				// We want to allow this to be applied retroactively
				$commission->last_paid_date = 0;
			}
			else
			{
				$language = \XF::language();
				
				$dateTime = new \DateTime('@' . $dateInput['date']);
				$dateTime->setTimezone($language->getTimeZone());
				
				if (!$dateInput['time'] OR strpos($dateInput['time'], ':') === false)
				{
					// We didn't have a valid time string
					$hours = $language->date($commission->last_paid_date, 'H');
					$minutes = $language->date($commission->last_paid_date, 'i');
				}
				else
				{
					[$hours, $minutes] = explode(':', $dateInput['time']);
					
					// Sanitise hours and minutes to a maximum of 23:59
					$hours = min(intval($hours), 23);
					$minutes = min(intval($minutes), 59);
				}
				
				// Finally set it
				$dateTime->setTime($hours, $minutes);
				
				$commission->last_paid_date = max(0, $dateTime->getTimestamp());
			}
		});
		
		$productCommissions = [];
		$args = $this->filter('product_commissions', 'array');
		foreach ($args AS $arg)
		{
			if (empty($arg['product_id']))
			{
				continue;
			}
			$productCommissions[] = $this->filterArray($arg, [
				'product_id' => 'uint',
				'commission_value' => 'float',
				'commission_type' => 'str'
			]);
		}

		$form->complete(function () use ($commission, $productCommissions)
		{
			/** @var \DBTech\eCommerce\Repository\ProductCommission $repo */
			$repo = $this->repository('DBTech\eCommerce:ProductCommission');
			$repo->updateContentAssociations($commission->commission_id, $productCommissions);
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
		
		if ($params->commission_id)
		{
			/** @var \DBTech\eCommerce\Entity\Commission $commission */
			$commission = $this->assertCommissionExists($params->commission_id);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Commission $commission */
			$commission = $this->em()->create('DBTech\eCommerce:Commission');
		}
		
		$this->commissionSaveProcess($commission)->run();

		return $this->redirect($this->buildLink('dbtech-ecommerce/commissions') . $this->buildLinkHash($commission->commission_id));
	}
	
	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$commission = $this->assertCommissionExists($params->commission_id);
		
		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$commission,
			$this->buildLink('dbtech-ecommerce/commissions/delete', $commission),
			$this->buildLink('dbtech-ecommerce/commissions/edit', $commission),
			$this->buildLink('dbtech-ecommerce/commissions'),
			$commission->name
		);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\CommissionPayment $commissionPayment
	 *
	 * @return FormAction
	 */
	protected function commissionPaymentSaveProcess(\DBTech\eCommerce\Entity\CommissionPayment $commissionPayment): FormAction
	{
		$form = $this->formAction();
		
		$input = $this->filter([
			'commission_id' => 'uint',
			'payment_amount' => 'float'
		]);
		$input['user_id'] = \XF::visitor()->user_id;
		
		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$input['message'] = $editorPlugin->fromInput('message');
		
		$form->basicEntitySave($commissionPayment, $input);
		
		$dateInput = $this->filter([
			'date' => 'datetime',
			'time' => 'str',
		]);
		$form->setup(function () use ($dateInput, $commissionPayment)
		{
			$language = \XF::language();
			
			$dateTime = new \DateTime('@' . $dateInput['date']);
			$dateTime->setTimezone($language->getTimeZone());
			
			if (!$dateInput['time'] OR strpos($dateInput['time'], ':') === false)
			{
				// We didn't have a valid time string
				$hours = $language->date($commissionPayment->payment_date, 'H');
				$minutes = $language->date($commissionPayment->payment_date, 'i');
			}
			else
			{
				[$hours, $minutes] = explode(':', $dateInput['time']);
				
				// Sanitise hours and minutes to a maximum of 23:59
				$hours = min(intval($hours), 23);
				$minutes = min(intval($minutes), 59);
			}
			
			// Finally set it
			$dateTime->setTime($hours, $minutes);
			
			$commissionPayment->payment_date = $dateTime->getTimestamp();
		});
		
		$form->complete(function () use ($commissionPayment)
		{
			/** @var \XF\Repository\Ip $ipRepo */
			$ipRepo = $this->repository('XF:Ip');
			$ipEnt = $ipRepo->logIp(\XF::visitor()->user_id, $this->request->getIp(), 'dbtech_ecommerce_comm', $commissionPayment->commission_payment_id, 'record_payment');
			if ($ipEnt)
			{
				$commissionPayment->fastUpdate('ip_id', $ipEnt->ip_id);
			}
		});
		
		
		return $form;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionPayment(ParameterBag $params)
	{
		$commission = $this->assertCommissionExists($params->commission_id);
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Entity\CommissionPayment $commissionPayment */
			$commissionPayment = $this->em()->create('DBTech\eCommerce:CommissionPayment');
			
			$this->commissionPaymentSaveProcess($commissionPayment)->run();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/commissions') . $this->buildLinkHash($params->commission_id));
		}
		else
		{
			$productCommissions = $this->finder('DBTech\eCommerce:ProductCommissionValue')
				->where('commission_id', $params->commission_id)
				->keyedBy('product_id')
				->fetch();
			
			$applicablePurchases = $this->finder('DBTech\eCommerce:PurchaseLog')
				->with('Product')
				->where('product_id', $productCommissions->keys())
				->where('log_date', '>', $commission->last_paid_date)
				->order('log_date', 'DESC')
				->fetch();
			
			$amountOwed = 0;
			
			/** @var \DBTech\eCommerce\Entity\PurchaseLog $purchase */
			foreach ($applicablePurchases as $purchase)
			{
				/** @var \DBTech\eCommerce\Entity\ProductCommissionValue $productCommission */
				$productCommission = $productCommissions[$purchase->product_id];
				
				$amountOwed += $productCommission->getCommission($purchase);
			}
			
			$viewParams = [
				'commission' => $commission,
				'purchases' => $applicablePurchases,
				'commissions' => $productCommissions,
				'amountOwed' => $amountOwed
			];
			return $this->view('DBTech\eCommerce:Commission\Payment', 'dbtech_ecommerce_commission_payment', $viewParams);
		}
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionFindPayments(): \XF\Mvc\Reply\AbstractReply
	{
		$productCommissions = $this->finder('DBTech\eCommerce:ProductCommissionValue')
			->with('Commission')
			->fetch()
			->groupBy('commission_id', 'product_id');
		
		$commissions = $this->finder('DBTech\eCommerce:Commission')
			->where('commission_id', array_keys($productCommissions))
			->fetch();
		
		$amountOwed = [];
		foreach ($productCommissions as $commissionId => $products)
		{
			if (!isset($commissions[$commissionId]))
			{
				continue;
			}
			
			/** @var \DBTech\eCommerce\Entity\Commission $commission */
			$commission = $commissions[$commissionId];
			
			$applicablePurchases = $this->finder('DBTech\eCommerce:PurchaseLog')
				->where('product_id', array_keys($products))
				->where('log_date', '>', $commission->last_paid_date);
			
			if (!$applicablePurchases->total())
			{
				unset($commissions[$commissionId]);
				continue;
			}
			
			$amountOwed[$commissionId] = 0;
			
			/** @var \DBTech\eCommerce\Entity\PurchaseLog $purchase */
			foreach ($applicablePurchases->fetch() as $purchase)
			{
				/** @var \DBTech\eCommerce\Entity\ProductCommissionValue $productCommission */
				$productCommission = $products[$purchase->product_id];
				
				$amountOwed[$commissionId] += $productCommission->getCommission($purchase);
			}
		}
		
		$viewParams = [
			'commissions' => $commissions,
			'amountOwed' => $amountOwed,
		];
		return $this->view('DBTech\eCommerce:Commission\FindPayments', 'dbtech_ecommerce_commission_find_payments', $viewParams);
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Commission
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCommissionExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Commission
	{
		return $this->assertRecordExists('DBTech\eCommerce:Commission', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Commission|\XF\Mvc\Entity\Repository
	 */
	protected function getCommissionRepo()
	{
		return $this->repository('DBTech\eCommerce:Commission');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
}