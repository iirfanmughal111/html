<?php

namespace XenAddons\Showcase\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{
	// Convert Post to Showcase Review is currently considered Beta/Unsupported.
	// Contact Bob at Xenaddons.com or XenForo.com for questions concerning this beta feature.
	
	public function actionConvertPostToScReview(ParameterBag $params)
	{
		$post = $this->assertViewablePost($params->post_id);
		if (!$post->canConvertPostToScReview($error))
		{
			return $this->noPermission($error);
		}
		
		$thread = $post->Thread;
		
		if ($thread)
		{
			/** @var \XenAddons\Showcase\Entity\Item $item */
			$item = \XF::repository('XenAddons\Showcase:Item')->findItemForThread($thread)->fetchOne();
			if ($item && $item->canView())
			{
				// do nothing, continue
			}
			else 
			{
				return $this->noPermission($error);
			}
		}
		else 
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$errors = [];
			$rating = $this->filter('rating', 'uint');
			if (!$rating)
			{
				$errors['rating'] = \XF::phraseDeferred('xa_sc_please_set_rating');
			}
			
			$reviewTitle = $this->filter('title', 'str');
			if ($this->app->options()->xaScRequireReviewTitle && $reviewTitle == '')
			{
				$errors['title'] = \XF::phraseDeferred('xa_sc_please_set_review_title');
			}
			
			// Since this is considered an automated process, normal validation in the add review service is bypassed,  
			//  however, we still want to throw errors for no Rating set and no Review Title set (when review title is required). 
			if ($errors)
			{
				return $this->error($errors);
			}
				
			/** @var \XenAddons\Showcase\XF\Service\Post\ConvertToReview $converter */
			$converter = $this->service('XenAddons\Showcase\XF:Post\ConvertToReview', $post); 
			
			if ($this->filter('author_alert', 'bool'))
			{
				$converter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
				
			$review = $converter->convertToReview($item, $rating, $reviewTitle);
	
			if ($review)
			{
				return $this->redirect($this->buildLink('showcase/review', $review));
			}
	
			return $this->redirect($this->buildLink('threads', $thread));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
							
				'post' => $post,
				'thread' => $thread,
			];
				
			return $this->view('XF:Post\ConvertPostToScReview', 'xa_sc_convert_post_to_review', $viewParams);
		}
	}	
	
	
	// Convert Post to Showcase Item Update is currently considered Beta/Unsupported.
	// Contact Bob at Xenaddons.com or XenForo.com for questions concerning this beta feature.
	
	public function actionConvertPostToScUpdate(ParameterBag $params)
	{
		$post = $this->assertViewablePost($params->post_id);
		if (!$post->canConvertPostToScUpdate($error))
		{
			return $this->noPermission($error);
		}
	
		$thread = $post->Thread;
	
		if ($thread)
		{
			/** @var \XenAddons\Showcase\Entity\Item $item */
			$item = \XF::repository('XenAddons\Showcase:Item')->findItemForThread($thread)->fetchOne();
			if ($item && $item->canView())
			{
				// do nothing, continue
			}
			else
			{
				return $this->noPermission($error);
			}
		}
		else
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$errors = [];
				
			$updateTitle = $this->filter('title', 'str');
			if ($updateTitle == '')
			{
				$errors['title'] = \XF::phraseDeferred('xa_sc_please_set_update_title');
			}
				
			// Since this is considered an automated process, normal validation in the add update service is bypassed,
			//  however, we still want to throw errors for no Update Title set as an update title is required.
			if ($errors)
			{
				return $this->error($errors);
			}
			
			/** @var \XenAddons\Showcase\XF\Service\Post\ConvertToUpdate $converter */
			$converter = $this->service('XenAddons\Showcase\XF:Post\ConvertToUpdate', $post);
				
			if ($this->filter('author_alert', 'bool'))
			{
				$converter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
	
			$update = $converter->convertToUpdate($item, $updateTitle);
	
			if ($update)
			{
				return $this->redirect($this->buildLink('showcase/update', $update));
			}
	
			return $this->redirect($this->buildLink('threads', $thread));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
					
				'post' => $post,
				'thread' => $thread,
			];
	
			return $this->view('XF:Post\ConvertPostToScUpdate', 'xa_sc_convert_post_to_update', $viewParams);
		}
	}
	
}