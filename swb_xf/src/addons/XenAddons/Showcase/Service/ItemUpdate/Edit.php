<?php

namespace XenAddons\Showcase\Service\ItemUpdate;

use XenAddons\Showcase\Entity\ItemUpdate;

class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\ItemUpdate
	 */
	protected $update;
	
	/**
	 * @var \XenAddons\Showcase\Service\ItemUpdate\Preparer
	 */
	protected $updatePreparer;
	
	protected $oldMessage;
	
	protected $performValidations = true;

	protected $logDelay;
	protected $logEdit = true;
	protected $logHistory = true;
	
	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemUpdate $update)
	{
		parent::__construct($app);

		$this->update = $this->setUpUpdate($update);
	}

	protected function setUpUpdate(ItemUpdate $update)
	{
		$this->update = $update;
		
		$this->updatePreparer = $this->service('XenAddons\Showcase:ItemUpdate\Preparer', $this->update);		
		
		return $update;
	}

	public function getUpdate()
	{
		return $this->update;
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
		$update = $this->update;
	
		$update->edit_count++;
	
		$options = $this->app->options();
		if ($options->editLogDisplay['enabled'] && $this->logEdit)
		{
			$delay = is_null($this->logDelay) ? $options->editLogDisplay['delay'] * 60 : $this->logDelay;
			if ($update->update_date + $delay <= \XF::$time)
			{
				$update->last_edit_date = \XF::$time;
				$update->last_edit_user_id = \XF::visitor()->user_id;
			}
		}
	
		if ($options->editHistory['enabled'] && $this->logHistory)
		{
			$this->oldMessage = $update->message;
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
	
	public function setTitle($title)
	{
		$this->update->title = $title;
	}
	
	public function setMessage($message, $format = true)
	{
		if (!$this->update->isChanged('message'))
		{
			$this->setupEditHistory();
		}
		return $this->updatePreparer->setMessage($message, $format);
	}
	
	public function setAttachmentHash($hash)
	{
		$this->updatePreparer->setAttachmentHash($hash);
	}
	
	public function setCustomFields(array $customFields)
	{
		$update = $this->update;
	
		$editMode = $update->getFieldEditMode();
	
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $update->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, $editMode)
			->filterOnly($update->Item->Category->update_field_cache);
	
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
		$update = $this->update;

		if (
			!\XF::visitor()->isSpamCheckRequired()
			|| !strlen($this->update->message)
			|| $this->update->getErrors()
		)
		{
			return;
		}

		/** @var \XF\Entity\User $user */
		$user = $update->User;

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
		$update = $this->update;

		$update->preSave();
		$errors = $update->getErrors();

		if ($this->performValidations)
		{
				
		}
		
		return $errors;
	}

	protected function _save()
	{
		$update = $this->update;
		$visitor = \XF::visitor();
		
		$db = $this->db();
		$db->beginTransaction();
		
		$update->save(true, false);
		
		$this->updatePreparer->afterUpdate();

		if ($this->oldMessage)
		{
			/** @var \XF\Repository\EditHistory $repo */
			$repo = $this->repository('XF:EditHistory');
			$repo->insertEditHistory('sc_update', $update, $visitor, $this->oldMessage, $this->app->request()->getIp());
		}
		
		if ($update->update_state == 'visible' && $this->alert && $update->user_id != $visitor->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
			$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
			$updateRepo->sendModeratorActionAlert($update, 'edit', $this->alertReason);
		}
		
		$db->commit();

		return $update;
	}
}