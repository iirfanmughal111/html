<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;

/**
 * Class StoreCredit
 * @package DBTech\eCommerce\Admin\Controller
 */
class StoreCredit extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomCredit');
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	public function actionAdd()
	{
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Entity\StoreCreditLog $storeCreditLog */
			$storeCreditLog = $this->em()->create('DBTech\eCommerce:StoreCreditLog');
			$this->storeCreditSaveProcess($storeCreditLog)->run();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/logs/store-credit'));
		}
		
		return $this->view('DBTech\eCommerce:StoreCredit\Add', 'dbtech_ecommerce_store_credit_add');
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\StoreCreditLog $storeCreditLog
	 *
	 * @return FormAction
	 * @throws \LogicException
	 */
	protected function storeCreditSaveProcess(\DBTech\eCommerce\Entity\StoreCreditLog $storeCreditLog): FormAction
	{
		$form = $this->formAction();
		
		$input = $this->filter([
			'store_credit_amount' => 'int',
		]);
		
		$extraInput = $this->filter([
			'username' => 'str',
		]);
		$form->validate(function (FormAction $form) use ($extraInput, $storeCreditLog)
		{
			/** @var \XF\Entity\User $user **/
			$user = $this->finder('XF:User')->where('username', $extraInput['username'])->fetchOne();
			if ($user)
			{
				$storeCreditLog->user_id = $user->user_id;
			}
			else
			{
				$form->logError(\XF::phrase('requested_user_x_not_found', ['name' => $extraInput['username']]), 'username');
			}
		});
		
		$form->validate(function (FormAction $form) use ($storeCreditLog)
		{
			$storeCreditLog->log_details = [
				'reason' => 'admin_adjust',
				'admin_user_id' => \XF::visitor()->user_id
			];
		});
		
		$form->basicEntitySave($storeCreditLog, $input);
		
		$form->complete(function () use ($storeCreditLog)
		{
			/** @var \XF\Repository\Ip $ipRepo */
			$ipRepo = $this->repository('XF:Ip');
			$ipEnt = $ipRepo->logIp(\XF::visitor()->user_id, $this->request->getIp(), 'dbtech_ecommerce_credit', $storeCreditLog->store_credit_log_id, 'admin_adjust');
			if ($ipEnt)
			{
				$storeCreditLog->fastUpdate('ip_id', $ipEnt->ip_id);
			}
		});
		
		return $form;
	}
}