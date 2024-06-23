<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class AddUpdate extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	/**
	 * @var \XenAddons\Showcase\Entity\ItemUpdate
	 */
	protected $update;
	
	/**
	 * @var \XenAddons\Showcase\Service\ItemUpdate\Preparer
	 */
	protected $updatePreparer;

	protected $sendAlert = true;
	
	protected $postThreadUpdate = false;
	
	protected $performValidations = true;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);

		$this->item = $item;
		$this->update = $this->setupUpdate();
	}

	protected function setupUpdate()
	{
		$item = $this->item;
		
		$update = $this->em()->create('XenAddons\Showcase:ItemUpdate');
		$update->item_id = $item->item_id;
		$update->user_id = \XF::visitor()->user_id;
		$update->username = \XF::visitor()->username;
		
		$update->update_state = $item->getNewUpdateState();
		
		$this->update = $update;
		
		$this->updatePreparer = $this->service('XenAddons\Showcase:ItemUpdate\Preparer', $this->update);		
		
		return $update;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function getUpdate()
	{
		return $this->update;
	}

	public function logIp($logIp)
	{
		$this->updatePreparer->logIp($logIp);
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
	
	// Used only for the Convert Post to Update feature
	public function setUser($user)
	{
		$this->update->user_id = $user->user_id;
		$this->update->username = $user->username;
	}
	
	public function setTitle($title)
	{
		$this->update->title = $title;
	}
	
	public function setMessage($message, $format = true)
	{
		return $this->updatePreparer->setMessage($message, $format);
	}
	
	public function setAttachmentHash($hash)
	{
		$this->updatePreparer->setAttachmentHash($hash);
	}

	public function setCustomFields(array $customFields)
	{
		$update = $this->update;
	
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $update->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, 'user')
			->filterOnly($update->Item->Category->update_field_cache);
	
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
		$update = $this->update;

		if (
			!\XF::visitor()->isSpamCheckRequired()
			|| $update->update_state != 'visible'
			|| !strlen($this->update->message)
			|| $this->update->getErrors()
		)
		{
			return;
		}

		/** @var \XF\Entity\User $user */
		$user = $update->User ?: $this->repository('XF:User')->getGuestUser($update->username);
		
		$message = $update->message;

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:showcase', $update->Item),
			'content_type' => 'sc_update'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
				$update->update_state = 'moderated';
				break;
				
			case 'denied':
				$checker->logSpamTrigger('sc_update', $update->item_update_id);
				$update->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}

	protected function _validate()
	{
		$item = $this->item;
		$update = $this->update;
		
		if (!$update->user_id)
		{
			/** @var \XF\Validator\Username $validator */
			$validator = $this->app->validator('Username');
			$update->username = $validator->coerceValue($update->username);
			if (!$validator->isValid($update->username, $error))
			{
				return [$validator->getPrintableErrorValue($error)];
			}
		}

		$update->preSave();
		$errors = $update->getErrors();

		if ($this->performValidations)
		{
		}
			
		return $errors;
	}

	protected function _save()
	{
		$item = $this->item;
		$update = $this->update;

		$update->save(true, false);
		
		$this->updatePreparer->afterInsert();
		
		if ($this->postThreadUpdate)
		{
			$this->updateItemThread();
		}
		
		return $update;
	}
	
	protected function updateItemThread()
	{
		$update = $this->update;
		$item = $this->item;
		$thread = $item->Discussion;
		if (!$thread)
		{
			return;
		}
	
		$asUser = $update->User ?: $this->repository('XF:User')->getGuestUser($update->username);
	
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
		$update = $this->update;
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
			'update_link' => $this->app->router('public')->buildLink('canonical:showcase/update', $this->update),
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
		if ($this->update->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\ItemUpdate\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:ItemUpdate\Notifier', $this->update);
			$notifier->setMentionedUserIds($this->updatePreparer->getMentionedUserIds());
			$notifier->notifyAndEnqueue(3);
		}
	}
}