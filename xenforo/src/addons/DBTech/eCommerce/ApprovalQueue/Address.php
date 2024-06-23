<?php

namespace DBTech\eCommerce\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Address
 *
 * @package DBTech\eCommerce\ApprovalQueue
 */
class Address extends AbstractHandler
{
	/**
	 * @param Entity $content
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function canViewContent(Entity $content, &$error = null): bool
	{
		return true;
	}
	
	/**
	 * @param Entity $content
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function canActionContent(Entity $content, &$error = null): bool
	{
		/** @var $content \DBTech\eCommerce\Entity\Address */
		return $content->canApproveUnapprove($error);
	}
	
	/**
	 * @return array
	 */
	public function getDefaultActions(): array
	{
		return [
			'' => \XF::phrase('do_nothing'),
			'approve' => \XF::phrase('approve'),
			'reject' => \XF::phrase('reject')
		];
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Address $address
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function actionApprove(\DBTech\eCommerce\Entity\Address $address)
	{
		$notify = $this->getInput('notify', $address->address_id);
		
		/** @var \DBTech\eCommerce\Service\Address\Approve $approver */
		$approver = \XF::service('DBTech\eCommerce:Address\Approve', $address);
		$approver->setNotify($notify);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->approve();
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Address $address
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function actionReject(\DBTech\eCommerce\Entity\Address $address)
	{
		$notify = $this->getInput('notify', $address->address_id);
		$reason = $this->getInput('reason', $address->address_id);
		
		/** @var \DBTech\eCommerce\Service\Address\Approve $approver */
		$approver = \XF::service('DBTech\eCommerce:Address\Approve', $address);
		$approver->setNotify($notify);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->setReason($reason);
		$approver->reject();
	}
}