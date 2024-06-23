<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class AddPage extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;
	
	/**
	 * @var \XenAddons\Showcase\Entity\ItemPage
	 */
	protected $page;
	
	/**
	 * @var \XenAddons\Showcase\Service\Page\Preparer
	 */
	protected $pagePreparer;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);

		$this->item = $item;
		
		$this->page = $this->setUpPage();
	}

	protected function setUpPage()
	{
		$item = $this->item;
		
		$page = $this->em()->create('XenAddons\Showcase:ItemPage');
		$page->item_id = $item->item_id;
		$page->user_id = \XF::visitor()->user_id;
		$page->username = \XF::visitor()->username;
		
		$this->page = $page;
		
		$this->pagePreparer = $this->service('XenAddons\Showcase:Page\Preparer', $this->page);		
		
		return $page;
	}

	public function getItem()
	{
		return $this->item;
	}
	
	public function getPage()
	{
		return $this->page;
	}
	
	public function logIp($logIp)
	{
		$this->pagePreparer->logIp($logIp);
	}
	
	public function setTitle($title)
	{
		$this->page->title = $title;
	}
	
	public function setMessage($message, $format = true)
	{
		return $this->pagePreparer->setMessage($message, $format);
	}
	
	public function setAttachmentHash($hash)
	{
		$this->pagePreparer->setAttachmentHash($hash);
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
		if ($this->page->page_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->pagePreparer->checkForSpam();
		}
	}
	
	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$page = $this->page;

		$page->preSave();
		$errors = $page->getErrors();

		return $errors;
	}

	protected function _save()
	{
		$page = $this->page;
		$visitor = \XF::visitor();
		
		$page->save(true, false);
		
		$this->pagePreparer->afterInsert();

		return $page;
	}
	
	public function sendNotifications()
	{
		if ($this->page->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\Page\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:Page\Notifier', $this->page);
			$notifier->notifyAndEnqueue(3);
		}
	}	
}