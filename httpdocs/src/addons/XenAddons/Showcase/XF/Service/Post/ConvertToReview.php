<?php

namespace XenAddons\Showcase\XF\Service\Post;

use XF\Entity\Post;

class ConvertToReview extends \XF\Service\AbstractService
{
	/**
	 * @var \XF\Entity\Post
	 */
	protected $post;
	
	/**
	 * @var \XenAddons\Showcase\Service\Item\Rate|null
	 */
	protected $reviewCreator;
	
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
	
	public function convertToReview(\XenAddons\Showcase\Entity\Item $item, $rating = 5, $reviewTitle = '')
	{
		$post = $this->post;
		
		$creator = $this->setupNewReviewCreation($item, $rating, $reviewTitle);
		if ($creator && $creator->validate())
		{
			$review = $creator->save();
		
			$this->reviewCreator = $creator;
		
			$this->afterNewReviewCreated($review);
		
			$this->sendNotifications($review);
		
			return $review;
		}
		
		return false;
	}
	
	protected function setupNewReviewCreation(\XenAddons\Showcase\Entity\Item $item, $rating, $reviewTitle)
	{
		$post = $this->post;
	
		/** @var \XenAddons\Showcase\Service\Item\Rate $creator */
		$creator = $this->service('XenAddons\Showcase:Item\Rate', $item);
		
		$creator->setIsAutomated();
		$creator->setIsConvertFromPost();
		$creator->setUser($post->User);
		$creator->setRating($rating);	
		$creator->setTitle($reviewTitle);
		$creator->setMessage($post->message, false); 
	
		$review = $creator->getRating();
	
		$review->rating_state = 'visible'; 
	
		return $creator;
	}
	
	protected function afterNewReviewCreated(\XenAddons\Showcase\Entity\ItemRating $review)
	{
		$post = $this->post;
		$thread = $post->Thread;

		// if there are any post attachments, reassign them to the new review 
		if ($post->attach_count)
		{
			$attachCount = $this->db()->update(
				'xf_attachment',
				[
					'content_id' => $review->rating_id,
					'content_type' => 'sc_rating'
				],
				"content_id = $post->post_id AND content_type = 'post'"
			);
		
			$review->attach_count += $attachCount;
			
			$post->attach_count = 0;
		}
		
		// convert post reactions to update reactions
		$reactionCount = $this->db()->update(
			'xf_reaction_content',
			[
				'content_id' => $review->rating_id,
				'content_type' => 'sc_rating'
			],
			"content_id = $post->post_id AND content_type = 'post'"
		);
				
		$review->reaction_score = $post->reaction_score;
		$review->reactions = $post->reactions;
		$review->reaction_users = $post->reaction_users;
		
		$post->reaction_score = 0;
		$post->reactions = [];
		$post->reaction_users = [];

		$post->message = $this->geNewtPostMessage($review);	
		$post->save();

		$review->rating_date = $post->post_date;
		$review->save();  
		
		// Mark the item as read for both the moderator that is performing the convert action as well as the Post author
		$this->repository('XenAddons\Showcase:Item')->markItemReadByVisitor($review->Item);
		$this->repository('XenAddons\Showcase:Item')->markItemReadByUser($review->Item, $post->User);
		
		// set the watch state of the item for the thread author
		$this->repository('XenAddons\Showcase:ItemWatch')->autoWatchScItem($review->Item, $post->User, true);
	}
	
	protected function geNewtPostMessage(\XenAddons\Showcase\Entity\ItemRating $review)
	{
		$item = $review->Item;
		$category = $item->Category;
		
		$snippet = $this->app->bbCode()->render(
			$this->app->stringFormatter()->wholeWordTrim($review->message, 500),
			'bbCodeClean',
			'post',
			null
		);
		
		$phrase = \XF::phrase('xa_sc_item_thread_new_review', [
			'item_title' => $item->title_,
			'item_link' => $this->app->router('public')->buildLink('canonical:showcase', $item),
			'review_link' => $this->app->router('public')->buildLink('canonical:showcase/review', $review),
			'term' => $category->content_term ?: \XF::phrase('xa_sc_item'),
			'term_lower' => $category->content_term ? strtolower($category->content_term) : strtolower(\XF::phrase('xa_sc_item')),
			'snippet' => $snippet,
			'username' => $review->User ? $review->User->username : $review->username
		]);
	
		return $phrase->render('raw');
	}
		
	public function sendNotifications(\XenAddons\Showcase\Entity\ItemRating $review)
	{
		if ($this->reviewCreator)
		{
			$this->reviewCreator->sendNotifications();
		}
		
		if ($this->alert && $review->user_id != \XF::visitor()->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemRating $reviewRepo */
			$reviewRepo = $this->repository('XenAddons\Showcase:ItemRating');
			$reviewRepo->sendModeratorActionAlert($review, 'converted_post', $this->alertReason);
		}
	}	
	
}	