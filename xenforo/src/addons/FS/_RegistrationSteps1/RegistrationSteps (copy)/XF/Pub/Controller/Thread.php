<?php

namespace FS\RegistrationSteps\XF\Pub\Controller;

use XF\Mvc\ParameterBag;


class Thread extends XFCP_Thread
{
    public function actionFeatured(ParameterBag $params)
	{
        $visitor = \XF::visitor();
        if (!($visitor->is_admin || $visitor->is_moderator)){
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_not_allowed"))
            );
        }
        
        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());
        $viewParams = [
            'thread'=> $thread
            ];
            return $this->view('FS\RegistrationSteps', 'fs_register_featured_overlay', $viewParams);
    
    }

    public function actionFeaturedSave(ParameterBag $params)
	{
        $visitor = \XF::visitor();
        if (!($visitor->is_admin || $visitor->is_moderator)){
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_not_allowed"))
            );
        }
        
        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());
        $thread->is_featured = $this->filter('is_featured', 'uint');
        $thread->save();
        return $this->redirect($this->buildLink('threads',$thread),\XF::phrase('fs_register_update_featured_successfully'));

	}

	


}