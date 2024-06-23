<?php

namespace DBTech\eCommerce\Notifier\Download;

use XF\Notifier\AbstractNotifier;

/**
 * Class Mention
 *
 * @package DBTech\eCommerce\Notifier\Download
 */
class Mention extends AbstractNotifier
{
	/** @var \DBTech\eCommerce\Entity\Download */
	protected $download;
	
	/**
	 * Mention constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Download $download
	 */
	public function __construct(\XF\App $app, \DBTech\eCommerce\Entity\Download $download)
	{
		parent::__construct($app);

		$this->download = $download;
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function canNotify(\XF\Entity\User $user): bool
	{
		return ($this->download->isVisible() && $user->user_id != $this->download->Product->user_id);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function sendAlert(\XF\Entity\User $user): bool
	{
		$download = $this->download;
		$downloadUser = $download->User ?: $download->Product->User;
		
		return $this->basicAlert(
			$user,
			$downloadUser->user_id,
			$downloadUser->username,
			'dbtech_ecommerce_download',
			$download->download_id,
			'mention',
			['depends_on_addon_id' => 'DBTech/eCommerce']
		);
	}
}