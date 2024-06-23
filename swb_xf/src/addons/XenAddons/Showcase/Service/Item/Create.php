<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Category;

class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\Category
	 */
	protected $category;

	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	/**
	 * @var \XenAddons\Showcase\Service\Item\Preparer
	 */
	protected $itemPreparer;

	/**
	 * @var \XF\Service\Tag\Changer
	 */
	protected $tagChanger;
	
	protected $associatedThreadTags;

	/**
	 * @var  \XF\Service\Poll\Creator|null
	 */
	protected $pollCreator;
	
	/**
	 * @var \XF\Service\Thread\Creator|null
	 */
	protected $threadCreator;
	
	protected $createAssociatedThread = true;

	protected $performValidations = true;
	
	protected $minLengthMessageS1 = 0;
	protected $minLengthMessageS2 = 0;
	protected $minLengthMessageS3 = 0;
	protected $minLengthMessageS4 = 0;
	protected $minLengthMessageS5 = 0;
	protected $minLengthMessageS6 = 0;

	public function __construct(\XF\App $app, Category $category)
	{
		parent::__construct($app);
		
		$this->category = $category;

		$this->minLengthMessageS1 =  $category->min_message_length_s1;
		$this->minLengthMessageS2 =  $category->min_message_length_s2;
		$this->minLengthMessageS3 =  $category->min_message_length_s3;
		$this->minLengthMessageS4 =  $category->min_message_length_s4;
		$this->minLengthMessageS5 =  $category->min_message_length_s5;
		$this->minLengthMessageS6 =  $category->min_message_length_s6;
		
		$this->setupDefaults();
	}

	protected function setupDefaults()
	{
		$item = $this->category->getNewItem();

		$this->item = $item;

		$this->itemPreparer = $this->service('XenAddons\Showcase:Item\Preparer', $this->item);

		$this->tagChanger = $this->service('XF:Tag\Changer', 'sc_item', $this->category);

		$visitor = \XF::visitor();
		$this->item->user_id = $visitor->user_id;
		$this->item->username = $visitor->username;
		
		$this->item->item_state = $this->category->getNewItemState();
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function getItem()
	{
		return $this->item;
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
	
	public function setCreateAssociatedThread($create)
	{
		$this->createAssociatedThread = (bool)$create;
	}
	
	public function getCreateAssociatedThread()
	{
		return $this->createAssociatedThread;
	}
	
	public function setIsConvertFromThread()
	{
		$this->setCreateAssociatedThread(false);
	}
	
	// Used only for the Convert Thread to Item feature
	public function setUser($user)
	{
		$this->item->user_id = $user->user_id;
		$this->item->username = $user->username;
	}

	public function setTitle($title)
	{
		$this->item->set('title', $title,
			['forceConstraint' => $this->performValidations ? false : true]
		);
	}

	public function setContent($title, $item, $format = true)
	{
		$this->setTitle($title);

		return $this->itemPreparer->setMessage($item, $format, $this->performValidations);
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
	
	// only used during the initial item create process to schedule publishing!
	public function setScheduledPublishDate($publishDate)
	{
		$this->item->create_date = $publishDate;
	}

	public function setPrefix($prefixId)
	{
		$this->item->prefix_id = $prefixId;
	}
	
	public function setItemState($itemState)
	{
		$this->item->item_state = $itemState;
	}
	
	public function setTags($tags)
	{
		if ($this->tagChanger->canEdit())
		{
			$this->tagChanger->setEditableTags($tags);
		}
	}
	
	public function setAssociatedThreadTags($tags)
	{
		$this->associatedThreadTags = $tags;
	}

	public function setItemAttachmentHash($hash)
	{
		$this->itemPreparer->setAttachmentHash($hash);
	}

	public function setCustomFields(array $customFields)
	{
		$item = $this->item;

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $item->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, 'user')
			->filterOnly($this->category->field_cache);

		$customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

		if ($customFieldsShown)
		{
			$fieldSet->bulkSet($customFields, $customFieldsShown);
		}
	}
	
	public function setSticky($sticky)
	{
		$this->item->sticky = $sticky;
	}
	
	public function setAuthorRating($authorRating)
	{
		$item = $this->item;
		if ($item->canSetAuthorRating())
		{
			$this->item->author_rating = $authorRating;
		}	
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
	
		$this->item->location_data = $itemRepo->getLocationDataFromGoogleMapsGeocodingApi($location, 'create');
	}

	public function setPollCreator(\XF\Service\Poll\Creator $creator = null)
	{
		$this->pollCreator = $creator;
	}
	
	public function getPollCreator()
	{
		return $this->pollCreator;
	}
	
	public function logIp($logIp)
	{
		$this->itemPreparer->logIp($logIp);
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

		if (!$item->user_id)
		{
			/** @var \XF\Validator\Username $validator */
			$validator = $this->app->validator('Username');
			$item->username = $validator->coerceValue($item->username);

			if ($this->performValidations && !$validator->isValid($item->username, $error))
			{
				return [
					$validator->getPrintableErrorValue($error)
				];
			}
		}

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
			
			if (!$this->itemPreparer->validateFiles($coverImageError))
			{
				$errors[] = $coverImageError;
			}
			
			if ($item->item_state == 'awaiting' && $item->create_date <= \XF::$time)
			{
				$errors[] = \XF::phraseDeferred('xa_sc_scheduled_publish_date_must_be_set_into_the_future');
			}
			
			if (!$item->prefix_id
				&& $this->category->require_prefix
				&& $this->category->getUsablePrefixes()
			)
			{
				$errors[] = \XF::phraseDeferred('please_select_a_prefix');
			}
			
			if (
				!$item->location
				&& $this->category->require_location
			)
			{
				$errors[] = \XF::phraseDeferred('xa_sc_please_set_location');
			}

			if ($this->tagChanger->canEdit())
			{
				$tagErrors = $this->tagChanger->getErrors();
				if ($tagErrors)
				{
					$errors = array_merge($errors, $tagErrors);
				}
			}
		}

		if ($this->pollCreator)
		{
			if (!$this->pollCreator->validate($pollErrors))
			{
				$errors = array_merge($errors, $pollErrors);
			}
		}
		
		return $errors;
	}

	protected function _save()
	{
		$category = $this->category;
		$item = $this->item;

		$db = $this->db();
		$db->beginTransaction();

		$item->save(true, false);

		$this->itemPreparer->afterInsert();

		if ($this->tagChanger->canEdit())
		{
			$this->tagChanger
				->setContentId($item->item_id, true)
				->save($this->performValidations);
		}

		if ($this->pollCreator)
		{
			$this->pollCreator->save();
		}
		
		if (
			$category->thread_node_id 
			&& $category->ThreadForum 
			&& $this->createAssociatedThread
		)
		{
			$creator = $this->setupItemThreadCreation($category->ThreadForum);
			if ($creator && $creator->validate())
			{
				$thread = $creator->save();
				$item->fastUpdate('discussion_thread_id', $thread->thread_id);
				$this->threadCreator = $creator;

				$this->afterItemThreadCreated($thread);
			}
		}

		$db->commit();

		return $item;
	}

	protected function setupItemThreadCreation(\XF\Entity\Forum $forum)
	{
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setIsAutomated();

		$creator->setContent($this->item->getExpectedThreadTitle(), $this->getThreadMessage(), false);
		$creator->setPrefix($this->category->thread_prefix_id);
		
		if ($this->category->thread_set_item_tags) // fail safe double check
		{
			$creator->setTags($this->associatedThreadTags);
		}
		
		$creator->setDiscussionTypeAndDataRaw('sc_item');

		// if the item has been saved as a draft or scheduled for publishing in the future, we want to set the discussion state to moderated!
		if ($this->item->item_state == 'draft' || $this->item->item_state == 'awaiting')
		{
			$discussionState = 'moderated';
		}
		else
		{
			$discussionState = $this->item->item_state;
		}
		
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

	protected function afterItemThreadCreated(\XF\Entity\Thread $thread)
	{
		$this->repository('XF:Thread')->markThreadReadByVisitor($thread);
		$this->repository('XF:ThreadWatch')->autoWatchThread($thread, \XF::visitor(), true);
	}

	public function sendNotifications()
	{
		if ($this->item->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\Item\Notify $notifier */
			$notifier = $this->service('XenAddons\Showcase:Item\Notify', $this->item, 'item');
			$notifier->setMentionedUserIds($this->itemPreparer->getMentionedUserIds());
			$notifier->notifyAndEnqueue(3);
		}

		if ($this->threadCreator)
		{
			$this->threadCreator->sendNotifications();
		}
	}
}