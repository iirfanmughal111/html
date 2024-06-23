<?php

namespace FS\RegistrationSteps\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

use function count, in_array, intval, is_int, strval;

class Forum extends XFCP_Forum
{
    protected function setupThreadCreate(\XF\Entity\Forum $forum)
	{
        
        if (in_array($forum->node_id,explode(',',$this->app->options()->fs_register_Reviews_ids))){
			$creator = $this->setupThreadCreateForReview($forum);
		}else{
            $creator =  parent::setupThreadCreate($forum);
        }
        return $creator;
    
    }

    protected function setupThreadCreateForReview($forum)
	{
		$title = $this->filter('title', 'str');
		$message = '.';

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


		return $creator;
	}
}