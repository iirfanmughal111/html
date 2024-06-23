<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class ConvertToThread extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;
	
	protected $newThreadTags;
	
	/**
	 * @var \XF\Service\Thread\Creator|null
	 */
	protected $threadCreator;

	protected $alert = false;
	protected $alertReason = '';
	
	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}
	
	public function setNewThreadTags($tags)
	{
		$this->newThreadTags = $tags;
	}
	
	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}
	
	public function convertToThread(\XF\Entity\Forum $targetForum, $prefixId = 0)
	{
		$item = $this->item;
		$existingThread = $item->Discussion;
		
		if ($existingThread)
		{
			// convert item to existing thread
			
			$existingThread->node_id = $targetForum->node_id;
			$existingThread->prefix_id = $prefixId;
			$existingThread->title = $this->app->stringFormatter()->wholeWordTrim($item->title, 100);
			$existingThread->discussion_type = 'discussion';
			$existingThread->save();
			
			$firstPostMessage = $item->message;
			
			if ($item->message_s2)
			{
				if ($item->Category->title_s2)
				{
					$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s2 . '[/b]';
				}
				$firstPostMessage .= "\r\n \r\n" . $item->message_s2;
			}
			if ($item->message_s3)
			{
				if ($item->Category->title_s3)
				{
					$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s3 . '[/b]';
				}
				$firstPostMessage .= "\r\n \r\n" . $item->message_s3;
			}
			if ($item->message_s4)
			{
				if ($item->Category->title_s4)
				{
					$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s4 . '[/b]';
				}
				$firstPostMessage .= "\r\n \r\n" . $item->message_s4;
			}
			if ($item->message_s5)
			{
				if ($item->Category->title_s5)
				{
					$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s5 . '[/b]';
				}
				$firstPostMessage .= "\r\n \r\n" . $item->message_s5;
			}
			if ($item->message_s6)
			{
				if ($item->Category->title_s6)
				{
					$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s6 . '[/b]';
				}
				$firstPostMessage .= "\r\n \r\n" . $item->message_s6;
			}			
			
			$firstPost = $existingThread->FirstPost;
			$firstPost->message = $firstPostMessage; 
			
			$firstPost->save();
			
			$this->afterExistingThreadUpdated($existingThread);
			
			return $existingThread; 
		}
		else
		{
			// convert item to new thread

			$creator = $this->setupNewThreadCreation($targetForum, $prefixId);
			if ($creator && $creator->validate())
			{
				$thread = $creator->save();
				
				$this->threadCreator = $creator;
		
				$this->afterNewThreadCreated($thread);
				
				$this->sendNotifications($thread);
				
				return $thread;
			}
		}
		
		return false;
	}
	
	protected function afterExistingThreadUpdated(\XF\Entity\Thread $thread)
	{
		$item = $this->item;
	
		// reassign thread to item author
		$thread->user_id = $item->user_id;
		$thread->username = $item->username;
		$thread->save();
	
		// reassign first post to item author
		$firstPost = $thread->FirstPost;
		$firstPost->user_id = $item->user_id;
		$firstPost->username = $item->username;
	
		// if there are any item attachments, reassign them to first post of the existing thread
		if ($item->attach_count)
		{
			$attachCount = $this->db()->update(
				'xf_attachment',
				[
					'content_id' => $firstPost->post_id,
					'content_type' => 'post'
				],
				"content_id = $item->item_id AND content_type = 'sc_item'"
			);
				
			$firstPost->attach_count += $attachCount;
		}
	
		$firstPost->save();
		
		// Mark the existing post as read for both the moderator that is performing the action as well as the item author
		$this->repository('XF:Thread')->markThreadReadByVisitor($thread);
		$this->repository('XF:Thread')->markThreadReadByUser($thread, $item->User);
		
		// set the watch state of the thread for the item author 
		$this->repository('XF:ThreadWatch')->autoWatchThread($thread, $item->User, true);
		
		$thread->rebuildFirstPostInfo();
		$thread->rebuildLastPostInfo();
		$thread->save();
		
		$thread->Forum->rebuildLastPost();
		$thread->Forum->save();
	}
	
	protected function setupNewThreadCreation(\XF\Entity\Forum $forum, $prefixId = 0)
	{
		$item = $this->item;
		$category = $item->Category;
		
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setIsAutomated();
		
		$threadTitle = $this->app->stringFormatter()->wholeWordTrim($item->title, 100);
		
		$firstPostMessage = $item->message;
			
		if ($item->message_s2)
		{
			if ($item->Category->title_s2)
			{
				$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s2 . '[/b]';
			}
			$firstPostMessage .= "\r\n \r\n" . $item->message_s2;
		}
		if ($item->message_s3)
		{
			if ($item->Category->title_s3)
			{
				$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s3 . '[/b]';
			}
			$firstPostMessage .= "\r\n \r\n" . $item->message_s3;
		}
		if ($item->message_s4)
		{
			if ($item->Category->title_s4)
			{
				$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s4 . '[/b]';
			}
			$firstPostMessage .= "\r\n \r\n" . $item->message_s4;
		}
		if ($item->message_s5)
		{
			if ($item->Category->title_s5)
			{
				$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s5 . '[/b]';
			}
			$firstPostMessage .= "\r\n \r\n" . $item->message_s5;
		}
		if ($item->message_s6)
		{
			if ($item->Category->title_s6)
			{
				$firstPostMessage .= "\r\n \r\n [b]" . $item->Category->title_s6 . '[/b]';
			}
			$firstPostMessage .= "\r\n \r\n" . $item->message_s6;
		}
		
		$creator->setContent($threadTitle, $firstPostMessage, false);
		$creator->setPrefix($prefixId);
		$creator->setTags($this->newThreadTags);
		$creator->setDiscussionTypeAndDataRaw('discussion');  
	
		$thread = $creator->getThread();
		
		$thread->discussion_state = 'visible';
	
		return $creator;
	}
	
	protected function afterNewThreadCreated(\XF\Entity\Thread $thread)
	{
		$item = $this->item;

		// reassign thread to item author
		$thread->user_id = $item->user_id;
		$thread->username = $item->username;
		$thread->last_post_user_id = $item->user_id;
		$thread->last_post_username = $item->username;
		$thread->save();
	
		// reassign first post to item author
		$firstPost = $thread->FirstPost;
		$firstPost->user_id = $item->user_id;
		$firstPost->username = $item->username;
	
		// if there are any item attachments, reassign them to first post of new thread
		if ($item->attach_count)
		{
			$attachCount = $this->db()->update(
				'xf_attachment',
				[
					'content_id' => $firstPost->post_id,
					'content_type' => 'post'
				],
				"content_id = $item->item_id AND content_type = 'sc_item'"
			);
				
			$firstPost->attach_count += $attachCount;
		}
		
		$firstPost->save();

		// Mark the existing post as read for both the moderator that is performing the action as well as the item author
		$this->repository('XF:Thread')->markThreadReadByVisitor($thread);
		$this->repository('XF:Thread')->markThreadReadByUser($thread, $item->User);
		
		// set the watch state of the thread for the item author
		$this->repository('XF:ThreadWatch')->autoWatchThread($thread, $item->User, true);
		
		$thread->rebuildFirstPostInfo();
		$thread->rebuildLastPostInfo();
		$thread->save();
		
		$thread->Forum->rebuildLastPost();
		$thread->Forum->save();
	}	
	
	public function sendNotifications(\XF\Entity\Thread $thread)
	{
		if ($this->threadCreator)
		{
			$this->threadCreator->sendNotifications();
		}
		
		if ($this->alert && $thread->user_id != \XF::visitor()->user_id)
		{
			/** @var \XF\Repository\Thread $threadRepo */
			$threadRepo = $this->repository('XF:Thread');
			$threadRepo->sendModeratorActionAlert($thread, 'converted_sc_item', $this->alertReason);
		}
	}
	
}	