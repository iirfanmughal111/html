<?php

namespace XenAddons\Showcase\Service\Review;

use XenAddons\Showcase\Entity\ItemRating;

class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\ItemRating
	 */
	protected $rating;
	
	/**
	 * @var \XenAddons\Showcase\Service\Review\Preparer
	 */
	protected $reviewPreparer;
	
	protected $oldMessage;

	protected $reviewRequired = false;
	protected $reviewMinLength = 0;
	
	protected $performValidations = true;

	protected $logDelay;
	protected $logEdit = true;
	protected $logHistory = true;
	
	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemRating $rating)
	{
		parent::__construct($app);

		$this->rating = $this->setUpRating($rating);

		$this->reviewRequired = $rating->Item->Category->require_review;
		$this->reviewMinLength = $this->app->options()->xaScMinimumReviewLength;
	}

	protected function setUpRating(ItemRating $rating)
	{
		$this->rating = $rating;
		
		$this->reviewPreparer = $this->service('XenAddons\Showcase:Review\Preparer', $this->rating);		
		
		return $rating;
	}

	public function getRating()
	{
		return $this->rating;
	}
	
	public function setIsAutomated()
	{
		$this->setPerformValidations(false);
	}
	
	public function logDelay($logDelay)
	{
		$this->logDelay = $logDelay;
	}
	
	public function logEdit($logEdit)
	{
		$this->logEdit = $logEdit;
	}
	
	public function logHistory($logHistory)
	{
		$this->logHistory = $logHistory;
	}
	
	protected function setupEditHistory()
	{
		$rating = $this->rating;
	
		$rating->edit_count++;
	
		$options = $this->app->options();
		if ($options->editLogDisplay['enabled'] && $this->logEdit)
		{
			$delay = is_null($this->logDelay) ? $options->editLogDisplay['delay'] * 60 : $this->logDelay;
			if ($rating->rating_date + $delay <= \XF::$time)
			{
				$rating->last_edit_date = \XF::$time;
				$rating->last_edit_user_id = \XF::visitor()->user_id;
			}
		}
	
		if ($options->editHistory['enabled'] && $this->logHistory)
		{
			$this->oldMessage = $rating->message;
		}
	}
	
	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool)$perform;
	}
	
	public function getPerformValidations()
	{
		return $this->performValidations;
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
		if (!$this->rating->isChanged('message'))
		{
			$this->setupEditHistory();
		}
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
	
		$editMode = $rating->getFieldEditMode();
	
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $rating->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, $editMode)
			->filterOnly($rating->Item->Category->review_field_cache);
	
		$customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
	
		if ($customFieldsShown)
		{
			$fieldSet->bulkSet($customFields, $customFieldsShown, $editMode);
		}
	}	
	
	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
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
		$user = $rating->User;

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
		$rating = $this->rating;
		
		$options = $this->app->options();

		$rating->preSave();
		$errors = $rating->getErrors();

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
		$rating = $this->rating;
		$visitor = \XF::visitor();
		
		$db = $this->db();
		$db->beginTransaction();
		
		$rating->save(true, false);
		
		$this->reviewPreparer->afterUpdate();

		if ($this->oldMessage)
		{
			/** @var \XF\Repository\EditHistory $repo */
			$repo = $this->repository('XF:EditHistory');
			$repo->insertEditHistory('sc_rating', $rating, $visitor, $this->oldMessage, $this->app->request()->getIp());
		}
		
		if ($rating->rating_state == 'visible' && $this->alert && $rating->user_id != $visitor->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
			$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
			$ratingRepo->sendModeratorActionAlert($rating, 'edit', $this->alertReason);
		}

		$db->commit();
		
		return $rating;
	}
}