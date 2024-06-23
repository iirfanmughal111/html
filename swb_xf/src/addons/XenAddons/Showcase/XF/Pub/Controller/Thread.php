<?php

namespace XenAddons\Showcase\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
	public function actionIndex(ParameterBag $params)
	{
		$reply = parent::actionIndex($params);

		if ($reply instanceof \XF\Mvc\Reply\View && $reply->getParam('posts'))
		{
			$scItemRepo = $this->repository('XenAddons\Showcase:Item');
			$scItemRepo->addItemEmbedsToContent($reply->getParam('posts'));
		}

		return $reply;
	}
	
	// This was initially implemented in Showcase 3.2.14 and is currently considered Beta/Unsupported. 
	// Contact Bob at Xenaddons.com or XenForo.com for questions concerning this beta feature. 
	
	public function actionConvertThreadToScItem(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id);
		if (!$thread->canConvertThreadToScItem($error))
		{
			return $this->noPermission($error);
		}
		
		if ($this->isPost())
		{
			$categoryId = $this->filter('target_category_id', 'int');
			
			$category = $this->app()->em()->find('XenAddons\Showcase:Category', $categoryId);
			if (!$category)
			{
				throw new \InvalidArgumentException("Invalid target category ($categoryId)");
			}
			
			/** @var \XenAddons\Showcase\XF\Service\Thread\ConvertToItem $converter */
			$converter = $this->service('XenAddons\Showcase\XF:Thread\ConvertToItem', $thread);
			$converter->setNewItemTags($this->filter('tags', 'str'));

			if ($this->filter('new_item_prefix_id', 'int'))
			{
				$converter->setNewItemPrefix($this->filter('new_item_prefix_id', 'int'));
			}
				
			if ($this->filter('new_item_state', 'str'))
			{
				$converter->setNewItemState($this->filter('new_item_state', 'str'));
			}
			
			if ($this->filter('author_alert', 'bool'))
			{
				$converter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
			
			$item = $converter->convertToItem($category);
				
			if ($item)
			{
				return $this->redirect($this->buildLink('showcase', $item));
			}

			return $this->redirect($this->buildLink('threads', $thread)); 
		}
		else
		{
			$categoryRepo = $this->app->repository('XenAddons\Showcase:Category');
			$categoryTree = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
			
			/** @var \XenAddons\Showcase\Repository\ItemPrefix $prefixRepo */
			$prefixRepo = $this->repository('XenAddons\Showcase:ItemPrefix');
			$availablePrefixes = $prefixRepo->findPrefixesForList()->fetch();
			$availablePrefixes = $availablePrefixes->pluckNamed('title', 'prefix_id');
			$prefixListData = $prefixRepo->getPrefixListData();
			
			/** @var \XF\Service\Tag\Changer $tagger */
			$tagger = $this->service('XF:Tag\Changer', 'thread', $thread);
			$grouped = $tagger->getExistingTagsByEditability();
			
			$viewParams = [
				'thread' => $thread,
				'forum' => $thread->Forum,
	
				'categoryTree' => $categoryTree,
				
				'itemPrefixes' => $availablePrefixes,
				
				'editableTags' => $grouped['editable'],
				'uneditableTags' => $grouped['uneditable']
			];
			
			return $this->view('XF:Thread\ConvertThreadToScItem', 'xa_sc_convert_thread_to_item', $viewParams);
		}	
	}
}