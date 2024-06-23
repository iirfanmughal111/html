<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

/**
 * Class Terms
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class Terms extends AbstractPlugin
{
	/**
	 * @return null|\XF\Entity\Page
	 */
	public function getTerms(): ?\XF\Entity\Page
	{
		if (!$this->options()->dbtechEcommerceTermsPageId)
		{
			return null;
		}
		
		/** @var \XF\Entity\Page $page */
		$page = $this->em->find('XF:Page', $this->options()->dbtechEcommerceTermsPageId);
		
		return $page;
	}
	
	/**
	 *
	 */
	public function setTermsAccepted()
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$terms = $this->getTerms();
		if (!$terms)
		{
			return;
		}
		
		if ($visitor->user_id)
		{
			// Update the ToS acceptance date
			$visitor->dbtech_ecommerce_tos_accept = $terms->modified_date;
			$visitor->saveIfChanged();
		}
		else
		{
			$this->app->response()->setCookie('dbtechEcommerceTosAccept', $terms->modified_date, 86400 * 365);
		}
	}
}