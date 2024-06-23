<?php

namespace FS\Escrow\XF\Service\Thread;

class Creator extends XFCP_Creator
{
    protected $escrow_id;

    public function setEscrowId($Escrow_id)
    {
        $this->thread->escrow_id = $Escrow_id;
    }

    public function sendNotifications()
    {
        if ($this->thread->node_id !=  intval(\xf::app()->options()->fs_escrow_applicable_forum)) {
            return parent::sendNotifications();
        }
        if ($this->thread->isVisible()) {
            /** @var \XF\Service\Post\Notifier $notifier */
            $notifier = $this->service('XF:Post\Notifier', $this->post, 'thread');
            $notifier->setMentionedUserIds([$this->thread->Escrow->to_user]);
            $notifier->setQuotedUserIds($this->postPreparer->getQuotedUserIds());
            $notifier->notifyAndEnqueue(3);
        }
    }
}
