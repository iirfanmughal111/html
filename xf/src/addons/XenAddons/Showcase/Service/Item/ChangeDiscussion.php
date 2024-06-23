<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class ChangeDiscussion extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}
	
	public function disconnectDiscussion()
	{
		$this->item->discussion_thread_id = 0;
		$this->item->save();

		return true;
	}

	public function changeThreadByUrl($threadUrl, $checkPermissions = true, &$error = null)
	{
		$threadRepo = $this->repository('XF:Thread');
		$thread = $threadRepo->getThreadFromUrl($threadUrl, null, $threadFetchError);
		if (!$thread)
		{
			$error = $threadFetchError;
			return false;
		}

		return $this->changeThreadTo($thread, $checkPermissions, $error);
	}

	public function changeThreadTo(\XF\Entity\Thread $thread, $checkPermissions = true, &$error = null)
	{
		if ($checkPermissions && !$thread->canView($viewError))
		{
			$error = $viewError ?: \XF::phrase('do_not_have_permission');
			return false;
		}

		if ($thread->thread_id === $this->item->discussion_thread_id)
		{
			return true;
		}

		if ($thread->discussion_type != \XF\ThreadType\AbstractHandler::BASIC_THREAD_TYPE)
		{
			$error = \XF::phrase('xa_sc_new_item_discussion_thread_must_be_standard_thread');
			return false;
		}

		$this->item->discussion_thread_id = $thread->thread_id;
		$this->item->save();

		return true;
	}
	
	public function createDiscussion()
	{
		$item = $this->item;
		$category = $item->Category;
	
		if ($category->thread_node_id && $category->ThreadForum)
		{
			$creator = $this->setupItemThreadCreation($category->ThreadForum);
			if ($creator && $creator->validate())
			{
				$thread = $creator->save();
				$item->fastUpdate('discussion_thread_id', $thread->thread_id);
				$this->threadCreator = $creator;
	
				$this->afterItemThreadCreated($thread, $item);
			}
		}
		
		return true;
	}	
	
	protected function setupItemThreadCreation(\XF\Entity\Forum $forum)
	{
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setIsAutomated();
	
		$creator->setContent($this->item->getExpectedThreadTitle(), $this->getThreadMessage(), false);
		$creator->setPrefix($this->item->Category->thread_prefix_id);
	
		$creator->setDiscussionTypeAndDataRaw('sc_item');
	
		$thread = $creator->getThread();
		$thread->discussion_state = $this->item->item_state;
	
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
	
	protected function afterItemThreadCreated(\XF\Entity\Thread $thread, \XenAddons\Showcase\Entity\Item $item)
	{
		$this->repository('XF:Thread')->markThreadReadByVisitor($thread);
		$this->repository('XF:ThreadWatch')->autoWatchThread($thread, \XF::visitor(), true);
		
		$thread->user_id = $item->user_id;
		$thread->username = $item->username;
		$thread->save();
			
		$firstPost = $thread->FirstPost;
		$firstPost->user_id = $item->user_id;
		$firstPost->username = $item->username;
		$firstPost->save();
		
		$thread->rebuildFirstPostInfo();
		$thread->rebuildLastPostInfo();
		$thread->save();
		
		$thread->Forum->rebuildLastPost();
		$thread->Forum->save();
	}	

}