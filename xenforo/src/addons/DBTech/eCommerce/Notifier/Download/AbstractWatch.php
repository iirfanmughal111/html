<?php

namespace DBTech\eCommerce\Notifier\Download;

use XF\Notifier\AbstractNotifier;

/**
 * Class AbstractWatch
 *
 * @package DBTech\eCommerce\Notifier\Download
 */
abstract class AbstractWatch extends AbstractNotifier
{
	/** @var \DBTech\eCommerce\Entity\Download */
	protected $download;
	
	/** @var string */
	protected $actionType;

	/** @var bool */
	protected $isApplicable;


	/** @return mixed */
	abstract protected function getDefaultWatchNotifyData();
	
	/** @return mixed */
	abstract protected function getApplicableActionTypes();
	
	/**
	 * @return mixed
	 */
	abstract protected function getWatchEmailTemplateName();
	
	/**
	 * AbstractWatch constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Download $download
	 * @param string $actionType
	 */
	public function __construct(\XF\App $app, \DBTech\eCommerce\Entity\Download $download, string $actionType)
	{
		parent::__construct($app);

		$this->download = $download;
		$this->actionType = $actionType;
		$this->isApplicable = $this->isApplicable();
	}
	
	/**
	 * @return bool
	 */
	protected function isApplicable(): bool
	{
		if (!in_array($this->actionType, $this->getApplicableActionTypes()))
		{
			return false;
		}

		if (!$this->download->isVisible())
		{
			return false;
		}

		return true;
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function canNotify(\XF\Entity\User $user): bool
	{
		if (!$this->isApplicable)
		{
			return false;
		}

		$download = $this->download;
		$product = $download->Product;
		
		return !($user->user_id == $product->user_id || $user->isIgnoring($product->user_id));
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return mixed
	 */
	public function sendAlert(\XF\Entity\User $user)
	{
		$download = $this->download;
		$downloadUser = $download->User ?: $download->Product->User;

		return $this->basicAlert(
			$user,
			$downloadUser->user_id,
			$downloadUser->username,
			'dbtech_ecommerce_download',
			$download->download_id,
			'insert',
			['depends_on_addon_id' => 'DBTech/eCommerce']
		);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function sendEmail(\XF\Entity\User $user): bool
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$download = $this->download;

		$params = [
			'download' => $download,
			'product' => $download->Product,
			'category' => $download->Product->Category,
			'receiver' => $user
		];

		$template = $this->getWatchEmailTemplateName();

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate($template, $params)
			->queue();

		return true;
	}
	
	/**
	 * @return array
	 */
	public function getDefaultNotifyData(): array
	{
		if (!$this->isApplicable)
		{
			return [];
		}

		return $this->getDefaultWatchNotifyData();
	}
}