<?php

namespace FS\Escrow\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
    public function actionIndex(ParameterBag $params)
    {
        $this->assertNotEmbeddedImageRequest();

        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());

        $userId = \XF::visitor()->user_id;

        if ($thread->node_id ==  intval($this->app()->options()->fs_escrow_applicable_forum)) {

            if (!($thread->Escrow->user_id == $userId || $thread->Escrow->to_user == $userId || \XF::visitor()->is_admin)) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_not_allowed"))
                );
            }
        }

        return parent::actionIndex($params);
    }
}
