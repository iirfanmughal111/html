<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Rate extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	/**
	 * @var \XenAddons\Showcase\Entity\ItemRating
	 */
	protected $rating;
	
	/**
	 * @var \XenAddons\Showcase\Service\Review\Preparer
	 */
	protected $reviewPreparer;

	protected $reviewRequired = false;
	
	protected $reviewMinLength = 0;

	protected $sendAlert = true;
	
	protected $isPreRegAction = false;
	
	protected $performValidations = true;
	
	protected $postThreadUpdate = false;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);

		$this->item = $item;
		$this->rating = $this->setupRating();

		$this->reviewRequired = $item->Category->require_review;
		$this->reviewMinLength = $this->app->options()->xaScMinimumReviewLength;
	}

	protected function setupRating()
	{
		$item = $this->item;
		
		$rating = $this->em()->create('XenAddons\Showcase:ItemRating');
		$rating->item_id = $item->item_id;
		$rating->user_id = \XF::visitor()->user_id;
		$rating->username = \XF::visitor()->username;
		
		$rating->rating_state = $item->getNewRatingState();
		
		$this->rating = $rating;
		
		$this->reviewPreparer = $this->service('XenAddons\Showcase:Review\Preparer', $this->rating);		
		
		return $rating;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function logIp($logIp)
	{
		$this->reviewPreparer->logIp($logIp);
	}	
	
	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool)$perform;
	}
	
	public function getPerformValidations()
	{
		return $this->performValidations;
	}
	
	public function setIsAutomated()
	{
		$this->logIp(false);
		$this->setPerformValidations(false);
	}
	
	public function setIsConvertFromPost()
	{
		$this->postThreadUpdate = false;
	}
	
	// Used only for the Convert Post to Review feature
	public function setUser($user)
	{
		$this->rating->user_id = $user->user_id;
		$this->rating->username = $user->username;
	}
	
	public function setRating($rating)
	{
		$this->rating->rating = $rating;
	}
	
	public function setTitle($title)
	{
		$this->rating->title = $title;
	}
	
	public function setPros($pros = '')
	{
		$this->rating->pros = $pros;
	}
	
	public function setCons($cons = '')
	{
		$this->rating->cons = $cons;
	}
	
	public function setMessage($message, $format = true)
	{
		return $this->reviewPreparer->setMessage($message, $format);
	}
	
	public function setAttachmentHash($hash)
	{
		$this->reviewPreparer->setAttachmentHash($hash);
	}

	public function setIsAnonymous($value = true)
	{
		$this->rating->is_anonymous = (bool)$value;
	}
	
	public function setIsPreRegAction(bool $isPreRegAction)
	{
		$this->isPreRegAction = $isPreRegAction;
	}

	public function setReviewRequirements($reviewRequired = null, $minLength = null)
	{
		if ($reviewRequired !== null)
		{
			$this->reviewRequired = (bool)$reviewRequired;
		}
		if ($minLength !== null)
		{
			$minLength = max(0, intval($minLength));
			$this->reviewMinLength = $minLength;
		}
	}
	
	public function setCustomFields(array $customFields)
	{
		$rating = $this->rating;
	
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $rating->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, 'user')
			->filterOnly($rating->Item->Category->review_field_cache);
	
		$customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
	
		if ($customFieldsShown)
		{
			$fieldSet->bulkSet($customFields, $customFieldsShown);
		}
	}	
	
	public function setPostThreadUpdate($postThreadUpdate)
	{
		$this->postThreadUpdate = (bool)$postThreadUpdate;
	}

	public function checkForSpam()
	{
		$rating = $this->rating;

		if (
			!\XF::visitor()->isSpamCheckRequired()
			|| !strlen($this->rating->message)
			|| $this->rating->getErrors()
		)
		{
			return;
		}

		/** @var \XF\Entity\User $user */
		$user = $rating->User ?: $this->repository('XF:User')->getGuestUser($rating->username);

		$message = $rating->message;

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:showcase', $rating->Item),
			'content_type' => 'sc_rating'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
				$rating->rating_state = 'moderated';
				break;
				
			case 'denied':
				$checker->logSpamTrigger('sc_rating', $rating->rating_id);
				$rating->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}

	protected function _validate()
	{
		$item = $this->item;
		$rating = $this->rating;
		
		if (!$rating->user_id && !$this->isPreRegAction)
		{
			/** @var \XF\Validator\Username $validator */
			$validator = $this->app->validator('Username');
			$rating->username = $validator->coerceValue($rating->username);
			if (!$validator->isValid($rating->username, $error))
			{
				return [$validator->getPrintableErrorValue($error)];
			}
		}
		else if ($this->isPreRegAction && !$rating->username)
		{
			// need to force a value here to avoid a presave error
			$rating->username = 'preRegAction-' . \XF::$time;
		}
		
		$options = $this->app->options();

		$rating->preSave();
		$errors = $rating->getErrors();
		
		if ($this->isPreRegAction)
		{
			// ignore this as we'll resolve it later
			unset($errors['user_id']);
		}

		if ($this->performValidations)
		{		
			if ($this->reviewRequired && !$rating->is_review)
			{
				$errors['message'] = \XF::phrase('xa_sc_please_provide_review_with_your_rating');
			}
	
			if ($rating->is_review && utf8_strlen($rating->message) < $this->reviewMinLength)
			{
				$errors['message'] = \XF::phrase(
					'xa_sc_your_review_must_be_at_least_x_characters',
					['min' => $this->reviewMinLength]
				);
			}
	
			if (!$rating->rating)
			{
				$errors['rating'] = \XF::phrase('xa_sc_please_select_star_rating');
			}
			
			if ($rating->is_review && $options->xaScRequireReviewTitle && $rating->title == '')
			{
				$errors['title'] = \XF::phrase('xa_sc_please_set_review_title');
			}
		}
		
		return $errors;
	}

	protected function _save()
	{
		if ($this->isPreRegAction)
		{
			throw new \LogicException("Pre-reg action ratings cannot be saved");
		}
		
		$item = $this->item;
		$rating = $this->rating;

		// check for the existance of previous ratings  
		$existing = $this->item->Ratings[$rating->user_id];
		if ($existing)
		{
			// since there previous ratings, we need to fetch and delete all previous ratings only (ratings that are not reviews) posted by the viewing user on this item! 
				
			$ratings = $this->repository('XenAddons\Showcase:ItemRating')->getRatingsForItemByUser($rating->item_id, $rating->user_id); 
			foreach ($ratings AS $existingRating)
			{
				$existingRating->delete();
			}	
			
			// run the rebuildCounters on the item after performing this to make sure the rating/review caches are correct! 
			$item->rebuildCounters();
			$item->save();
		}

		$rating->save(true, false);
		
		$this->reviewPreparer->afterInsert();
		
		if ($this->postThreadUpdate && $rating->is_review)
		{
			$this->updateItemThread();
		}

		return $rating;
	}
	
	
	
	protected function updateItemThread()
	{
		$rating = $this->rating;
		$item = $this->item;
		$thread = $item->Discussion;
		if (!$thread)
		{
			return;
		}
	
		// handle anonymous reviews..
		if ($rating->is_anonymous)
		{
			$guestUsername = \XF::phrase('anonymous');
			$asUser = $this->repository('XF:User')->getGuestUser($guestUsername->render('raw'));
		}
		else
		{
			$asUser = $rating->User ?: $this->repository('XF:User')->getGuestUser($rating->username);
		}
	
		\XF::asVisitor($asUser, function() use ($thread)
		{
			$replier = $this->setupItemThreadReply($thread);
			if ($replier && $replier->validate())
			{
				$existingLastPostDate = $replier->getThread()->last_post_date;
	
				$post = $replier->save();
				$this->afterItemThreadReplied($post, $existingLastPostDate);
	
				\XF::runLater(function() use ($replier)
				{
					$replier->sendNotifications();
				});
			}
		});
	}
	
	protected function setupItemThreadReply(\XF\Entity\Thread $thread)
	{
		/** @var \XF\Service\Thread\Replier $replier */
		$replier = $this->service('XF:Thread\Replier', $thread);
		$replier->setIsAutomated();
		$replier->setMessage($this->getThreadReplyMessage(), false);
	
		return $replier;
	}
	
	protected function getThreadReplyMessage()
	{
		$item = $this->item;
		$rating = $this->rating;
		$category = $item->Category;
	
		if ($rating->is_anonymous)
		{
			$username = \XF::phrase('anonymous');
		}
		else
		{
			$username = $rating->User ? $rating->User->username : $rating->username;
		}
	
		$snippet = $this->app->bbCode()->render(
			$this->app->stringFormatter()->wholeWordTrim($rating->message, 500),
			'bbCodeClean',
			'post',
			null
		);
		
		$phrase = \XF::phrase('xa_sc_item_thread_new_review', [
			'username' => $username,
			'snippet' => $snippet,
			'review_link' => $this->app->router('public')->buildLink('canonical:showcase/review', $this->rating),
			'item_title' => $item->title_,
			'item_link' => $this->app->router('public')->buildLink('canonical:showcase', $this->item),
			'term' => $category->content_term ?: \XF::phrase('xa_sc_item'),
			'term_lower' => $category->content_term ? strtolower($category->content_term) : strtolower(\XF::phrase('xa_sc_item')),
		]);
	
		return $phrase->render('raw');
	}
	
	protected function afterItemThreadReplied(\XF\Entity\Post $post, $existingLastPostDate)
	{
		$thread = $post->Thread;
	
		if (\XF::visitor()->user_id)
		{
			if ($post->Thread->getVisitorReadDate() >= $existingLastPostDate)
			{
				$this->repository('XF:Thread')->markThreadReadByVisitor($thread);
			}
	
			$this->repository('XF:ThreadWatch')->autoWatchThread($thread, \XF::visitor(), false);
		};
	}
	
	public function sendNotifications()
	{
		if ($this->rating->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\Review\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:Review\Notifier', $this->rating);
			$notifier->setMentionedUserIds($this->reviewPreparer->getMentionedUserIds());
			$notifier->notifyAndEnqueue(3);
		}
	}
}