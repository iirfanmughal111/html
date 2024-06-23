<?php

namespace XenAddons\Showcase\Service\ItemUpdateReply;

use XenAddons\Showcase\Entity\ItemUpdateReply;

class Editor extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var ItemUpdateReply
	 */
	protected $reply;

	/**
	 * @var \XenAddons\Showcase\Service\ItemUpdateReply\Preparer
	 */
	protected $preparer;
	
	protected $oldMessage;
	
	protected $performValidations = true;
	
	protected $logDelay;
	protected $logEdit = true;
	protected $logHistory = true;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemUpdateReply $reply)
	{
		parent::__construct($app);
		$this->setReply($reply);
	}

	protected function setReply(ItemUpdateReply $reply)
	{
		$this->reply = $reply;
		$this->preparer = $this->service('XenAddons\Showcase:ItemUpdateReply\Preparer', $this->reply);
	}

	public function getReply()
	{
		return $this->reply;
	}

	public function getPreparer()
	{
		return $this->preparer;
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
		$reply = $this->reply;
	
		// TODO implement edit logging for Item Update Replies
		/* $reply->edit_count++;
	
		$options = $this->app->options();
		if ($options->editLogDisplay['enabled'] && $this->logEdit)
		{
			$delay = is_null($this->logDelay) ? $options->editLogDisplay['delay'] * 60 : $this->logDelay;
			if ($reply->reply_date + $delay <= \XF::$time)
			{
				$reply->last_edit_date = \XF::$time;
				$reply->last_edit_user_id = \XF::visitor()->user_id;
			}
		}
	
		if ($options->editHistory['enabled'] && $this->logHistory)
		{
			$this->oldMessage = $reply->message;
		} */
	}
	
	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool)$perform;
	}
	
	public function getPerformValidations()
	{
		return $this->performValidations;
	}

	public function setMessage($message, $format = true)
	{
		if (!$this->reply->isChanged('message'))
		{
			$this->setupEditHistory();
		}
		
		return $this->preparer->setMessage($message, $format);
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
		if ($this->reply->reply_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->preparer->checkForSpam();
		}
	}

	protected function finalSetup() {}

	protected function _validate()
	{
		$this->finalSetup();
		
		$reply = $this->reply;
		
		$reply->preSave();
		$errors = $reply->getErrors();
		
		if ($this->performValidations)
		{
		
		}
		
		return $errors;
	}

	protected function _save()
	{
		$db = $this->db();
		$db->beginTransaction();

		$reply = $this->reply;
		$visitor = \XF::visitor();

		$reply->save(true, false);

		$this->preparer->afterUpdate();
		
		if ($this->oldMessage)
		{
			// TODO implement edit history handler for Review Replies
			
			/** @var \XF\Repository\EditHistory $repo */
			//$repo = $this->repository('XF:EditHistory');
			//$repo->insertEditHistory('sc_update_reply', $reply, $visitor, $this->oldMessage, $this->app->request()->getIp());
		}

		if ($reply->reply_state == 'visible' && $this->alert && $reply->user_id != $visitor->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
			$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
			$updateRepo->sendReplyModeratorActionAlert($reply, 'edit', $this->alertReason);
		}

		$db->commit();

		return $reply;
	}
}