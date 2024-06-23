<?php

namespace FS\Escrow\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;


class Withdraw extends AbstractHandler
{
	 
	protected function canActionContent(Entity $content, &$error = null): bool
	{
		return $content->canApproveUnapprove($error);
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
        return [];
		// $visitor = \XF::visitor();
		// return [];
	}
	

	public function actionApprove(\FS\Escrow\Entity\WithdrawRequest $request)
	{
		$approver = \XF::service('FS\Escrow:Escrow\Approve', $request);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->approve();
	}
		
	public function actionDelete(\FS\Escrow\Entity\WithdrawRequest $request)
	{
		$this->quickUpdate($request, 'request_state', 'deleted');
	}
}