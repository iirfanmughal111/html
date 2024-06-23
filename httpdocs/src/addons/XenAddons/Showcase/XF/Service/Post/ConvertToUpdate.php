<?php

namespace XenAddons\Showcase\XF\Service\Post;

use XF\Entity\Post;

class ConvertToUpdate extends \XF\Service\AbstractService
{
	/**
	 * @var \XF\Entity\Post
	 */
	protected $post;
	
	/**
	 * @var \XenAddons\Showcase\Service\Item\AddUpdate|null
	 */
	protected $updateCreator;
	
	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, Post $post)
	{
		parent::__construct($app);
		$this->post = $post;
	}

	public function getPost()
	{
		return $this->post;
	}
	
	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}
	
	public function convertToUpdate(\XenAddons\Showcase\Entity\Item $item, $updateTitle = '')
	{
		$post = $this->post;
		
		$creator = $this->setupNewUpdateCreation($item, $updateTitle);
		if ($creator && $creator->validate())
		{
			$update = $creator->save();
		
			$this->updateCreator = $creator;
		
			$this->afterNewUpdateCreated($update);
		
			$this->sendNotifications($update);
		
			return $update;
		}
		
		return false;
	}
	
	protected function setupNewUpdateCreation(\XenAddons\Showcase\Entity\Item $item, $updateTitle)
	{
		$post = $this->post;
	
		/** @var \XenAddons\Showcase\Service\Item\AddUpdate $creator */
		$creator = $this->service('XenAddons\Showcase:Item\AddUpdate', $item);
		
		$creator->setIsAutomated();
		$creator->setIsConvertFromPost();
		$creator->setUser($post->User);
		$creator->setTitle($updateTitle);
		$creator->setMessage($post->message, false); 
	
		$update = $creator->getUpdate();
	
		$update->update_state = 'visible'; 
	
		return $creator;
	}
	
	protected function afterNewUpdateCreated(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		$post = $this->post;
		$thread = $post->Thread;

		// if there are any post attachments, reassign them to the new update 
		if ($post->attach_count)
		{
			$attachCount = $this->db()->update(
				'xf_attachment',
				[
					'content_id' => $update->item_update_id,
					'content_type' => 'sc_update'
				],
				"content_id = $post->post_id AND content_type = 'post'"
			);
		
			$update->attach_count += $attachCount;
			
			$post->attach_count = 0;
		}
		
		// convert post reactions to update reactions
		$reactionCount = $this->db()->update(
			'xf_reaction_content',
			[
				'content_id' => $update->item_update_id,
				'content_type' => 'sc_update'
			],
			"content_id = $post->post_id AND content_type = 'post'"
		);
		
		$update->reaction_score = $post->reaction_score;
		$update->reactions = $post->reactions;
		$update->reaction_users = $post->reaction_users;

		$post->reaction_score = 0;
		$post->reactions = [];
		$post->reaction_users = [];

		$post->message = $this->geNewtPostMessage($update);	
		$post->save();

		$update->update_date = $post->post_date;
		$update->save();  
		
		// Mark the item as read for both the moderator that is performing the convert action as well as the Post author
		$this->repository('XenAddons\Showcase:Item')->markItemReadByVisitor($update->Item);
		$this->repository('XenAddons\Showcase:Item')->markItemReadByUser($update->Item, $post->User);
		
		// set the watch state of the item for the thread author
		$this->repository('XenAddons\Showcase:ItemWatch')->autoWatchScItem($update->Item, $post->User, true);
	}
	
	protected function geNewtPostMessage(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		$item = $update->Item;
		$category = $item->Category;
		
		$snippet = $this->app->bbCode()->render(
			$this->app->stringFormatter()->wholeWordTrim($update->message, 500),
			'bbCodeClean',
			'post',
			null
		);
		
		$phrase = \XF::phrase('xa_sc_item_thread_new_item_update', [
			'username' => $update->User ? $update->User->username : $update->username,
			'update_title' => $update->title_,
			'snippet' => $snippet,
			'update_link' => $this->app->router('public')->buildLink('canonical:showcase/update', $update),
			'item_title' => $item->title_,
			'item_link' => $this->app->router('public')->buildLink('canonical:showcase', $item),
			'term' => $category->content_term ?: \XF::phrase('xa_sc_item'),
			'term_lower' => $category->content_term ? strtolower($category->content_term) : strtolower(\XF::phrase('xa_sc_item')),
		]);		
	
		return $phrase->render('raw');
	}
		
	public function sendNotifications(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		if ($this->updateCreator)
		{
			$this->updateCreator->sendNotifications();
		}
		
		if ($this->alert && $update->user_id != \XF::visitor()->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
			$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
			$updateRepo->sendModeratorActionAlert($update, 'converted_post', $this->alertReason);
		}
	}	
	
}	