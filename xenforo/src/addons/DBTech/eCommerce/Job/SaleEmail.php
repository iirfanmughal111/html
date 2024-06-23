<?php

namespace DBTech\eCommerce\Job;

use XF\Job\UserEmail;

/**
 * Class SaleEmail
 *
 * @package DBTech\eCommerce\Job
 */
class SaleEmail extends UserEmail
{
	/** @var null|\DBTech\eCommerce\Entity\Sale */
	protected $sale;
	
	
	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \LogicException
	 */
	protected function setupData(array $data): array
	{
		$data['email']['email_format'] = 'html';
		
		if (empty($data['sale_type']) || $data['sale_type'] == 'now')
		{
			$data['email']['email_title'] = '{phrase:dbtech_ecommerce_ongoing_sale_x_title}';
			$data['email']['email_body'] = '{phrase:dbtech_ecommerce_ongoing_sale_x_body}';
		}
		elseif ($data['sale_type'] == 'future')
		{
			$data['email']['email_title'] = '{phrase:dbtech_ecommerce_new_upcoming_sale_x_title}';
			$data['email']['email_body'] = '{phrase:dbtech_ecommerce_new_upcoming_sale_x_body}';
		}
		
		if (empty($data['sale_id']))
		{
			throw new \LogicException('Cannot trigger this job without a sale id.');
		}
		
		/** @var \DBTech\eCommerce\Entity\Sale $sale */
		$sale = $this->app->em()->find('DBTech\eCommerce:Sale', $data['sale_id']);
		if (!$sale)
		{
			throw new \LogicException('Cannot trigger this job without a valid sale id.');
		}
		
		$this->sale = $sale;
		
		$this->defaultData = array_merge($this->extraDefaultData, $this->defaultData);
		
		$options = \XF::options();
		
		$data['email']['from_name'] = $options->emailSenderName ? $options->emailSenderName : $options->boardTitle;
		$data['email']['from_email'] = $options->defaultEmailAddress;
		
		return parent::setupData($data);
	}
	
	public function run($maxRunTime): \XF\Job\JobResult
	{
		if (!$this->app->options()->dbtechEcommerceSales['enabled'])
		{
			return $this->complete();
		}
		
		return parent::run($maxRunTime);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 */
	protected function executeAction(\XF\Entity\User $user)
	{
		if (!$user->Option->dbtech_ecommerce_email_on_sale || $this->sale->end_date < \XF::$time)
		{
			// The end date issue shouldn't be a factor but it doesn't hurt
			return;
		}
		
		parent::executeAction($user);
	}
	
	
	/**
	 * @param \XF\Entity\User $user
	 * @param $escape
	 *
	 * @return array
	 */
	protected function prepareTokens(\XF\Entity\User $user, $escape): array
	{
		$unsubLink = $this->app->router('public')->buildLink('canonical:email-stop/content', $user, ['t' => 'dbtech_ecommerce_sale']);
		
		/** @var \XF\Language $language */
		$language = $this->app->language($user->language_id);
		
		[$startDate, $startTime] = $language->getDateTimeParts($this->sale->start_date);
		[$endDate, $endTime] = $language->getDateTimeParts($this->sale->end_date);
		
		$products = $this->sale->getDiscountedProducts($language);
		$includeList = count($this->sale->product_discounts) > 0;
		
		$saleInfo = $includeList ? '<ul>' : '<p>';
		foreach ($products as $title => $discount)
		{
			$saleInfo .= ($includeList ? '<li>' : '') .
				$language->renderPhrase('dbtech_ecommerce_sale_list_item', [
					'title' => $title,
					'discount' => $discount
				]) .
				($includeList ? '</li>' : '</p>');
		}
		
		if ($includeList)
		{
			$saleInfo .= '</ul>';
		}
		
		$tokens = [
			'{sale_title}' => $this->sale->title,
			'{sale_description}' => $language->renderPhrase($this->sale->getDescriptionPhraseName()),
			'{sale_start}' => $language->getDateTimeOutput($startDate, $startTime),
			'{sale_end}' => $language->getDateTimeOutput($endDate, $endTime),
			'{name}' => $user->username,
			'{email}' => $user->email,
			'{id}' => $user->user_id,
			'{unsub}' => $unsubLink
		];
		
		if ($escape)
		{
			array_walk($tokens, function (&$value)
			{
				if (is_string($value))
				{
					$value = htmlspecialchars($value);
				}
			});
		}
		
		// This one shouldn't get escaped
		$tokens['{sale_products}'] = $saleInfo;
		
		return $tokens;
	}
}