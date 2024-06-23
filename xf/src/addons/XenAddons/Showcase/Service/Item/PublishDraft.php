<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class PublishDraft extends \XF\Service\AbstractService
{
	/**
	 * @var Item
	 */
	protected $item;

	protected $notifyRunTime = 3;
	
	/**
	 * @var \XF\Service\Thread\Creator|null
	 */
	protected $threadCreator;
	
	protected $createAssociatedThread = true;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function setNotifyRunTime($time)
	{
		$this->notifyRunTime = $time;
	}

	public function publishDraft($isAutomated = false)
	{
		if (
			$this->item->item_state == 'draft' 
			|| $this->item->item_state == 'awaiting'
		)
		{
			if ($isAutomated)
			{
				// TODO for now, set this to visible... in the future, check the item authors permissions on whether to bypass queue or not
				$this->item->item_state = 'visible';
			}
			else
			{
				$this->item->item_state = $this->item->Category->getNewItemState();
			}

			$this->item->create_date = \XF::$time;
			$this->item->edit_date = \XF::$time;
			$this->item->last_update = \XF::$time;
			$this->item->save();

			$this->onPublishDraft($isAutomated);
			
			return true;
		}
		else
		{
			return false;
		}
	}

	protected function onPublishDraft()
	{
		$item = $this->item;
		$category = $item->Category;
		
		if ($item->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\Item\Notify $notifier */
			$notifier = $this->service('XenAddons\Showcase:Item\Notify', $item, 'item');
			$notifier->notifyAndEnqueue($this->notifyRunTime);
		}
		
		// Create an associated discussion thread for this newly published item (if the category is configured to do so)
		if (
			$category->thread_node_id
			&& $category->ThreadForum
		)
		{
			$actionUser = $this->getItemActionUser($item);
				
			return \XF::asVisitor($actionUser, function() use ($item, $category, $actionUser)
			{
				$creator = $this->setupItemThreadCreation($category->ThreadForum);
				if (!$creator->validate($errors))
				{
					$error = reset($errors);
					$itemId = $this->item->item_id;
					\XF::logException(new \Exception("Error creating thread for item $itemId: $error"));
					return false;
				}
		
				$db = $this->db();
				$db->beginTransaction();
					
				$creator->save();
		
				$thread = $creator->getThread();
					
				$item->fastUpdate('discussion_thread_id', $thread->thread_id);
					
				$db->commit();
		
				$creator->sendNotifications();
		
				\XF::repository('XF:Thread')->markThreadReadByUser($thread, $actionUser, true);
				\XF::repository('XF:ThreadWatch')->autoWatchThread($thread, $actionUser, true);
		
				return true;
			});
		}		
	}
	
	protected function getItemActionUser(\XenAddons\Showcase\Entity\Item $item)
	{
		if ($item->user_id && $item->User)
		{
			return $item->User;
		}
	
		$userRepo = $this->repository('XF:User');
		$strFormatter = $this->app->stringFormatter();
	
		return $userRepo->getGuestUser($strFormatter->wholeWordTrim($item->title, 50, 0, ''));
	}
	
	protected function setupItemThreadCreation(\XF\Entity\Forum $forum)
	{
		$item = $this->item;
		$category = $item->Category;
	
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setIsAutomated();
	
		$creator->setContent($item->getExpectedThreadTitle(), $this->getThreadMessage(), false);
		$creator->setPrefix($category->thread_prefix_id);
	
		if (
			$category->thread_set_item_tags
			&& $item->tags
		)
		{
			$tagList = [];
			foreach ($item->tags AS $tagId => $tag)
			{
				$tagList[] = $tag['tag'];
			}
			$tagList = implode(', ', $tagList);
				
			$creator->setTags($tagList);
		}
	
		$creator->setDiscussionTypeAndDataRaw('sc_item');
	
		$discussionState = $item->item_state;
	
		$thread = $creator->getThread();
		$thread->discussion_state = $discussionState;
	
		return $creator;
	}
	
	protected function getThreadMessage()
	{
		$item = $this->item;
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
			'item_link' => $this->app->router('public')->buildLink('canonical:showcase', $this->item)
		]);
	
		return $phrase->render('raw');
	}	
}