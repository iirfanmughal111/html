<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var Item
	 */
	protected $item;

	/**
	 * @var \XenAddons\Showcase\Service\Item\Preparer
	 */
	protected $itemPreparer;
	
	protected $oldMessage;

	protected $performValidations = true;
	
	protected $minLengthMessageS1 = 0;
	protected $minLengthMessageS2 = 0;
	protected $minLengthMessageS3 = 0;
	protected $minLengthMessageS4 = 0;
	protected $minLengthMessageS5 = 0;
	protected $minLengthMessageS6 = 0;

	protected $logDelay;
	protected $logEdit = true;
	protected $logHistory = true;
	
	protected $alert = false;
	protected $alertReason = '';
	
	protected $postThreadUpdate = false;
	protected $postThreadUpdateMessage = '';

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		
		$this->setItem($item);
		
		$this->minLengthMessageS1 =  $item->Category->min_message_length_s1;
		$this->minLengthMessageS2 =  $item->Category->min_message_length_s2;
		$this->minLengthMessageS3 =  $item->Category->min_message_length_s3;
		$this->minLengthMessageS4 =  $item->Category->min_message_length_s4;
		$this->minLengthMessageS5 =  $item->Category->min_message_length_s5;
		$this->minLengthMessageS6 =  $item->Category->min_message_length_s6;
	}
	
	public function setItem(Item $item)
	{
		$this->item = $item;
		$this->itemPreparer = $this->service('XenAddons\Showcase:Item\Preparer', $this->item);
	}

	public function getItem()
	{
		return $this->item;
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
		$item = $this->item;
	
		$item->edit_count++;
	
		$options = $this->app->options();
		if ($options->editLogDisplay['enabled'] && $this->logEdit)
		{
			$delay = is_null($this->logDelay) ? $options->editLogDisplay['delay'] * 60 : $this->logDelay;
			if ($item->create_date + $delay <= \XF::$time)
			{
				$item->last_edit_date = \XF::$time;
				$item->last_edit_user_id = \XF::visitor()->user_id;
			}
		}
	
		if ($options->editHistory['enabled'] && $this->logHistory)
		{
			$this->oldMessage = $item->message;
		}
	}
	
	public function getItemPreparer()
	{
		return $this->itemPreparer;
	}

	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool)$perform;
	}

	public function getPerformValidations()
	{
		return $this->performValidations;
	}

	public function setTitle($title)
	{
		$this->item->title = $title;
	}

	public function setMessage($message, $format = true)
	{
		if (!$this->item->isChanged('message'))
		{
			$this->setupEditHistory();
		}
		return $this->itemPreparer->setMessage($message, $format);
	}
	
	public function setMessageS2($message, $format = true)
	{
		return $this->itemPreparer->setMessageS2($message, $format);
	}
	
	public function setMessageS3($message, $format = true)
	{
		return $this->itemPreparer->setMessageS3($message, $format);
	}
	
	public function setMessageS4($message, $format = true)
	{
		return $this->itemPreparer->setMessageS4($message, $format);
	}
	
	public function setMessageS5($message, $format = true)
	{
		return $this->itemPreparer->setMessageS5($message, $format);
	}
	
	public function setMessageS6($message, $format = true)
	{
		return $this->itemPreparer->setMessageS6($message, $format);
	}
	
	public function setAttachmentHash($hash)
	{
		$this->itemPreparer->setAttachmentHash($hash);
	}

	public function setPrefix($prefixId)
	{
		$this->item->prefix_id = $prefixId;
	}
	
	public function setSticky($sticky)
	{
		$this->item->sticky = $sticky;
	}

	public function setCustomFields(array $customFields)
	{
		$item = $this->item;

		$editMode = $item->getFieldEditMode();

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $item->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, $editMode)
			->filterOnly($item->Category->field_cache);

		$customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

		if ($customFieldsShown)
		{
			$fieldSet->bulkSet($customFields, $customFieldsShown, $editMode);
		}
	}
	
	public function setAuthorRating($authorRating)
	{
		$this->item->author_rating = $authorRating;
	}
	
	public function setLocation($location)
	{
		$this->item->location = $location;
	
		if ($location)
		{
			$this->setLocationData($location);
		}
		else
		{
			$this->item->location_data = [];
		}
	}
	
	public function setLocationData($location)
	{
		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->repository('XenAddons\Showcase:Item');
	
		$this->item->location_data = $itemRepo->getLocationDataFromGoogleMapsGeocodingApi($location, 'edit');
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}
	
	public function setPostThreadUpdate($postThreadUpdate, $postThreadUpdateMessage = null)
	{
		$this->postThreadUpdate = (bool)$postThreadUpdate;
		if ($postThreadUpdateMessage !== null)
		{
			$this->postThreadUpdateMessage = $postThreadUpdateMessage;
		}
	
		$item = $this->item;
		$item->last_update = time();
	}

	public function checkForSpam()
	{
		if ($this->item->item_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->itemPreparer->checkForSpam();
		}
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();

		$item = $this->item;

		$item->preSave();
		$errors = $item->getErrors();

		if ($this->performValidations)
		{
			if ($this->minLengthMessageS1 && utf8_strlen($item->message) < $this->minLengthMessageS1)
			{
				$errors['message'] = \XF::phrase(
					'xa_sc_section_x_must_be_at_least_x_characters',
					[
						'num' => 1,
						'title' => $item->Category->title_s1,
						'min_length' => $this->minLengthMessageS1
					]
				);
			}
				
			if ($this->minLengthMessageS2 && utf8_strlen($item->message_s2) < $this->minLengthMessageS2)
			{
				$errors['message_s2'] = \XF::phrase(
					'xa_sc_section_x_must_be_at_least_x_characters',
					[
						'num' => 2,
						'title' => $item->Category->title_s2,
						'min_length' => $this->minLengthMessageS2
					]
				);
			}
				
			if ($this->minLengthMessageS3 && utf8_strlen($item->message_s3) < $this->minLengthMessageS3)
			{
				$errors['message_s3'] = \XF::phrase(
					'xa_sc_section_x_must_be_at_least_x_characters',
					[
						'num' => 3,
						'title' => $item->Category->title_s3,
						'min_length' => $this->minLengthMessageS3
					]
				);
			}
				
			if ($this->minLengthMessageS4 && utf8_strlen($item->message_s4) < $this->minLengthMessageS4)
			{
				$errors['message_s4'] = \XF::phrase(
					'xa_sc_section_x_must_be_at_least_x_characters',
					[
						'num' => 4,
						'title' => $item->Category->title_s4,
						'min_length' => $this->minLengthMessageS4
					]
				);
			}
				
			if ($this->minLengthMessageS5 && utf8_strlen($item->message_s5) < $this->minLengthMessageS5)
			{
				$errors['message_s5'] = \XF::phrase(
					'xa_sc_section_x_must_be_at_least_x_characters',
					[
						'num' => 5,
						'title' => $item->Category->title_s5,
						'min_length' => $this->minLengthMessageS5
					]
				);
			}
				
			if ($this->minLengthMessageS6 && utf8_strlen($item->message_s6) < $this->minLengthMessageS6)
			{
				$errors['message_s6'] = \XF::phrase(
					'xa_sc_section_x_must_be_at_least_x_characters',
					[
						'num' => 6,
						'title' => $item->Category->title_s6,
						'min_length' => $this->minLengthMessageS6
					]
				);
			}
			
			if (!$item->prefix_id
				&& $item->Category->require_prefix
				&& $item->Category->getUsablePrefixes()
				&& !$item->canMove()
			)
			{
				$errors[] = \XF::phraseDeferred('please_select_a_prefix');
			}
			
			if (
				!$item->location
				&& $item->Category->require_location
			)
			{
				$errors[] = \XF::phraseDeferred('xa_sc_please_set_location');
			}
			
			if (!$this->itemPreparer->validateFiles($coverImageError))
			{
				$errors[] = $coverImageError;
			}
		}

		return $errors;
	}

	protected function _save()
	{
		$item = $this->item;
		$visitor = \XF::visitor();
		
		$db = $this->db();
		$db->beginTransaction();
		
		$item->save(true, false);
		
		$this->itemPreparer->afterUpdate();

		if ($this->oldMessage)
		{
			/** @var \XF\Repository\EditHistory $repo */
			$repo = $this->repository('XF:EditHistory');
			$repo->insertEditHistory('sc_item', $item, $visitor, $this->oldMessage, $this->app->request()->getIp());
		}
		
		if ($this->postThreadUpdate && $this->postThreadUpdateMessage)
		{
			$this->updateItemThread();
		}
		
		if ($item->isVisible() && $this->alert && $item->user_id != \XF::visitor()->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
			$itemRepo = $this->repository('XenAddons\Showcase:Item');
			$itemRepo->sendModeratorActionAlert($this->item, 'edit', $this->alertReason);
		}

		$db->commit();
		
		return $item;
	}
	
	public function sendNotifications()
	{
		if ($this->item->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\Item\Notify $notifier */
			$notifier = $this->service('XenAddons\Showcase:Item\Notify', $this->item, 'update');
			$notifier->notifyAndEnqueue(3);
		}
	}	
	
	protected function updateItemThread()
	{
		$item = $this->item;
		$thread = $item->Discussion;
		if (!$thread)
		{
			return;
		}
	
		$asUser = $item->User ?: $this->repository('XF:User')->getGuestUser($item->username);
	
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
	
		$phrase = \XF::phrase('xa_sc_item_thread_update', [
			'title' => $item->title_,
			'item_link' => $this->app->router('public')->buildLink('canonical:showcase', $this->item),
			'description' => $item->description_,
			'username' => $item->User ? $item->User->username : $item->username,
			'message' => $this->postThreadUpdateMessage
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
		}
	}
}