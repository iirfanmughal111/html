<?php

namespace FS\RegistrationSteps\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

use function count, in_array, intval, is_int, strval;

class Forum extends XFCP_Forum
{
	
	public function actionForum(ParameterBag $params)
	{
      
        $parent =  parent::actionForum($params);
		$featured_threads = $this->finder('XF:Thread')->where('node_id',$params->node_id)->where('is_featured',1)->order('post_date','desc')->fetch(10);
		
        $parent->setParam('featured_threads',$featured_threads);
        return $parent;
    }
	
    protected function setupThreadCreate(\XF\Entity\Forum $forum)
	{
        
        if (in_array($forum->node_id,explode(',',$this->app->options()->fs_register_Reviews_ids)) || in_array($forum->node_id,explode(',',$this->app->options()->fs_register_alert_ids))){
			$creator = $this->setupThreadCreateForReview($forum);
		}else{
            $creator =  parent::setupThreadCreate($forum);
        }
        return $creator;
    
    }

    protected function setupThreadCreateForReview($forum)
	{
		$title = $this->filter('title', 'str');
		$message = $this->plugin('XF:Editor')->fromInput('message');
		$review_type = false;
		if (in_array($forum->node_id,explode(',',$this->app->options()->fs_register_Reviews_ids))){
			$review_type = true;
		}
		
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);

		$isPreRegAction = $forum->canCreateThreadPreReg();
		if ($isPreRegAction)
		{
			// only returns true when pre-reg creating is the only option
			$creator->setIsPreRegAction(true);
		}

		$creator->setDiscussionTypeAndData(
			$this->filter('discussion_type', 'str'),
			$this->request
		);

		$creator->setContent($title, $message);
        

		$prefixId = $this->getPrefixIdIfUsable($forum);
		if ($prefixId)
		{
			$creator->setPrefix($prefixId);
		}

		$canEditTags = \XF::asPreRegActionUserIfNeeded(
			$isPreRegAction,
			function() use ($forum) { return $forum->canEditTags(); }
		);
		if ($canEditTags)
		{
			$creator->setTags($this->filter('tags', 'str'));
		}

		// attachments aren't supported in pre-reg actions
		if ($forum->canUploadAndManageAttachments())
		{
			$creator->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		$setOptions = $this->filter('_xfSet', 'array-bool');
		if ($setOptions)
		{
			$thread = $creator->getThread();

			if (isset($setOptions['discussion_open']) && $thread->canLockUnlock())
			{
				$creator->setDiscussionOpen($this->filter('discussion_open', 'bool'));
			}
			if (isset($setOptions['sticky']) && $thread->canStickUnstick())
			{
				$creator->setSticky($this->filter('sticky', 'bool'));
			}
		}

		$customFields = $this->filter('custom_fields', 'array');
		$creator->setCustomFields($customFields);
        
        //set review_for_user_id
        $user = $this->em()->findOne('XF:User', ['username' => $this->filter('review_for', 'str')]);
        
        if($this->filter('review_for', 'str')==''){
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_user_required"))
            );
        }
        if (!$user){
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_user_not_found"))
            );
        }else if ($review_type && $user->account_type==1 ){
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_can_not_review_male"))
            );
        }
        else if ($user->user_id==\XF::visitor()->user_id){
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_can_not_swb_user_yourself"))
            );
        }
		$creator->setReview_for($user->user_id);
		return $creator;
	}
	
    public function actionPostThread(ParameterBag $params)
	{
        $parent =  parent::actionPostThread($params);
        $review_for = NULL;
		if (in_array($params->node_id,explode(',',$this->app->options()->fs_register_Reviews_ids)) && !$this->isPost()){
            $user = $this->em()->findOne('XF:User', ['user_id' => $this->filter('user_id','uint')]);
			$review_for = $user ? $user->username : NULL;
            $parent->setParam('review_for',$review_for);
		}
        return $parent;
    }
	
	public function actionPostListing($params){
		$forum = NULL;
		if ($params->node_id || $params->node_name)
		{
			$forum = $this->assertViewableForum($params->node_id ?: $params->node_name);
			//return $this->redirectPermanently($this->buildLink('forums', $forum));
		}
		$viewParams = [
		'forum'=>$forum
	];
		return $this->view('FS\RegistrationSteps', 'fs_register_attention_overlay', $viewParams);

	}
}
