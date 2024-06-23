<?php

namespace XenAddons\Showcase\XF\Service\Thread;

use XF\Entity\Thread;

class ConvertToItem extends \XF\Service\AbstractService
{
	/**
	 * @var \XF\Entity\Thread
	 */
	protected $thread;
	
	protected $newItemTags;
	
	/**
	 * @var \XenAddons\Showcase\Service\Item\Create|null
	 */
	protected $itemCreator;
	
	protected $newItemPrefix = 0;
	
	protected $newItemState = 'visible';
	
	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, Thread $thread)
	{
		parent::__construct($app);
		$this->thread = $thread;
	}

	public function getThread()
	{
		return $this->thread;
	}
	
	public function setNewItemTags($tags)
	{
		$this->newItemTags = $tags;
	}
	
	public function setNewItemPrefix($itemPrefixId)
	{
		$this->newItemPrefix = $itemPrefixId;
	}
	
	public function setNewItemState($itemState)
	{
		$this->newItemState = $itemState;
	}
	
	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}
	
	public function convertToItem(\XenAddons\Showcase\Entity\Category $targetCategory)
	{
		$thread = $this->thread;
		
		$creator = $this->setupNewItemCreation($targetCategory);
		if ($creator && $creator->validate())
		{
			$item = $creator->save();
		
			$this->itemCreator = $creator;
		
			$this->afterNewItemCreated($item);
		
			$this->sendNotifications($item);
		
			return $item;
		}
		
		return false;
	}
	
	protected function setupNewItemCreation(\XenAddons\Showcase\Entity\Category $category)
	{
		$thread = $this->thread;
		$firstPost = $thread->FirstPost;
	
		/** @var \XenAddons\Showcase\Service\Item\Create $creator */
		$creator = $this->service('XenAddons\Showcase:Item\Create', $category);
		$creator->setIsAutomated();
		$creator->setIsConvertFromThread();
		$creator->setUser($thread->User);

		$itemTitle = $this->app->stringFormatter()->wholeWordTrim($thread->title, 100);
	
		$creator->setContent($itemTitle, $firstPost->message, false);
		$creator->setItemState($this->newItemState);
		$creator->setPrefix($this->newItemPrefix);
		$creator->setTags($this->newItemTags);
	
		return $creator;
	}
	
	protected function afterNewItemCreated(\XenAddons\Showcase\Entity\Item $item)
	{
		$thread = $this->thread;
		$thread->discussion_type = 'sc_item';
		
		$newItemState = $this->newItemState;
		if($newItemState != 'visible')
		{
			$thread->discussion_state = 'moderated';
		}
		
		if ($item->Category->thread_node_id)
		{
			$thread->node_id = $item->Category->thread_node_id;
			$thread->prefix_id = $item->Category->thread_prefix_id;
		}
		
		$thread->save();

		// if there are any first post attachments, reassign them to the new item and set a cover image if there are any image attachments
		$firstPost = $thread->FirstPost;
		if ($firstPost->attach_count)
		{
			$item->cover_image_id = $this->getCoverImageId($firstPost);
			
			$attachCount = $this->db()->update(
				'xf_attachment',
				[
					'content_id' => $item->item_id,
					'content_type' => 'sc_item'
				],
				"content_id = $firstPost->post_id AND content_type = 'post'"
			);
		
			$item->attach_count += $attachCount;
			
			$firstPost->attach_count = 0;
		}
		
		// Convert First Post Reactions to Item Reactions
		$reactionCount = $this->db()->update(
			'xf_reaction_content',
			[
				'content_id' => $item->item_id,
				'content_type' => 'sc_item'
			],
			"content_id = $firstPost->post_id AND content_type = 'post'"
		);

		// set some misc item related data from thread data and first post data
		$item->reaction_score = $firstPost->reaction_score;
		$item->reactions = $firstPost->reactions;
		$item->reaction_users = $firstPost->reaction_users;
		$item->create_date = $thread->post_date;
		$item->view_count = $thread->view_count;
		$item->discussion_thread_id = $thread->thread_id;
		$item->save();

		// set some misc first post related data (unset reactions data for first post and modify the first post message)
		$firstPost->reaction_score = 0;
		$firstPost->reactions = [];
		$firstPost->reaction_users = [];
		$firstPost->message = $this->geNewtFirstPostMessage($item);	
		$firstPost->save();
		
		// Mark the new item as read for both the moderator that is performing the convert action as well as the thread author
		$this->repository('XenAddons\Showcase:Item')->markItemReadByVisitor($item);
		$this->repository('XenAddons\Showcase:Item')->markItemReadByUser($item, $thread->User);
		
		// set the watch state of the item for the thread author
		$this->repository('XenAddons\Showcase:ItemWatch')->autoWatchScItem($item, $thread->User, true);
	}
	
	protected function getCoverImageId(\XF\Entity\Post $firstPost)
	{
		$attachments = $firstPost->Attachments;
	
		foreach ($attachments AS $key => $attachment)
		{
			if ($attachment['thumbnail_url'])
			{
				return $attachment['attachment_id'];
				break;
			}
		}
		
		return 0;
	}	
	
	protected function geNewtFirstPostMessage(\XenAddons\Showcase\Entity\Item $item)
	{
		$category = $item->Category;
	
		$snippet = $this->app->bbCode()->render(
			$this->app->stringFormatter()->wholeWordTrim($item->message, 500),
			'bbCodeClean',
			'post',
			null
		);
	
		$phrase = \XF::phrase('xa_sc_item_thread_create', [
			'title' => $item->title_,
			'term' => $category->content_term ?: \XF::phrase('xa_sc_item'),
			'term_lower' => $category->content_term ? strtolower($category->content_term) : strtolower(\XF::phrase('xa_sc_item')),
			'username' => $item->User ? $item->User->username : $item->username,
			'snippet' => $snippet,
			'item_link' => $this->app->router('public')->buildLink('canonical:showcase', $item)
		]);
	
		return $phrase->render('raw');
	}
		
	public function sendNotifications(\XenAddons\Showcase\Entity\Item $item)
	{
		if ($this->itemCreator)
		{
			$this->itemCreator->sendNotifications();
		}
		
		if ($this->alert && $item->user_id != \XF::visitor()->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
			$itemRepo = $this->repository('XenAddons\Showcase:Item');
			$itemRepo->sendModeratorActionAlert($item, 'converted_thread', $this->alertReason);
		}
	}	
	
}	