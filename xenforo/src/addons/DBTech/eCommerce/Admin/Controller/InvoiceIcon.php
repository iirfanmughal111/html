<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class InvoiceIcon
 * @package DBTech\eCommerce\Admin\Controller
 */
class InvoiceIcon extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomOrder');
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \Exception
	 */
	public function actionEditIcon(ParameterBag $params)
	{
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\InvoiceIcon $iconService */
			$iconService = $this->service('DBTech\eCommerce:Order\InvoiceIcon');
			
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
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/invoice-icon/edit-icon'));
		}
		
		return $this->view('DBTech\eCommerce:InvoiceIcon\Edit', 'dbtech_ecommerce_invoice_edit_icon');
	}
}