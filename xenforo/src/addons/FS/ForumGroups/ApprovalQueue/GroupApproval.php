<?php

namespace FS\ForumGroups\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;


class GroupApproval extends AbstractHandler
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
	

	public function actionApprove(\XF\Entity\Node $request)
	{
		$approver = \XF::service('FS\ForumGroups:ForumGroups\Approve', $request);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->approve();
	}
		
	public function actionDelete(\XF\Entity\Node $request)
	{
		$this->quickUpdate($request, 'node_state', 'deleted');
	}
}