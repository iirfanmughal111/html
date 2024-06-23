<?php

namespace DBTech\eCommerce\Job;

use XF\Job\UserEmail;

/**
 * Class OrderEmail
 *
 * @package DBTech\eCommerce\Job
 */
class OrderEmail extends UserEmail
{
	/** @var null|\DBTech\eCommerce\Entity\Order */
	protected $order;
	
	
	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \LogicException
	 */
	protected function setupData(array $data): array
	{
		if (empty($data['order_id']))
		{
			throw new \LogicException('Cannot trigger this job without a order id.');
		}
		
		/** @var \DBTech\eCommerce\Entity\Order $order */
		$order = $this->app->em()->find('DBTech\eCommerce:Order', $data['order_id']);
		if (!$order)
		{
			throw new \LogicException('Cannot trigger this job without a valid order id.');
		}
		
		$this->order = $order;
		
		$this->defaultData = array_merge($this->extraDefaultData, $this->defaultData);
		
		$data['email']['email_format'] = 'html';
		$data['email']['email_title'] = '{phrase:dbtech_ecommerce_reminder_your_order_x_at_y_title}';
		$data['email']['email_body'] = '{phrase:dbtech_ecommerce_reminder_your_order_x_at_y_' .
			((!empty($data['has_coupon']) && $this->order->Coupon) ? 'with_coupon_' : '') . 'body}';
		
		$options = \XF::options();
		
		$data['email']['from_name'] = $options->emailSenderName ? $options->emailSenderName : $options->boardTitle;
		$data['email']['from_email'] = $options->defaultEmailAddress;
		
		return parent::setupData($data);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 */
	protected function executeAction(\XF\Entity\User $user)
	{
		if (!$user->Option->dbtech_ecommerce_order_email_reminder)
		{
			return;
		}
		
		parent::executeAction($user);
		
		$this->order->fastUpdate([
			'sent_reminder' => 1,
			'order_date' => \XF::$time
		]);
	}
	
	
	/**
	 * @param \XF\Entity\User $user
	 * @param $escape
	 *
	 * @return array
	 */
	protected function prepareTokens(\XF\Entity\User $user, $escape): array
	{
		$unsubLink = $this->app->router('public')->buildLink('canonical:email-stop/content', $user, ['t' => 'dbtech_ecommerce_order']);
		$checkoutLink = $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/checkout');
		
		$tokens = [
			'{orderId}' => $this->order->order_id,
			'{boardTitle}' => \XF::options()->boardTitle,
			'{name}' => $user->username,
			'{email}' => $user->email,
			'{id}' => $user->user_id,
			'{unsub}' => $unsubLink,
			'{checkout}' => $checkoutLink
		];
		
		if (!empty($this->data['has_coupon']) && $this->order->Coupon)
		{
			/** @var \XF\Language $language */
			$language = $this->app->language($user->language_id);
			
			[$endDate, $endTime] = $language->getDateTimeParts($this->order->Coupon->expiry_date);
			
			$tokens['{coupon_discount}'] = $this->order->Coupon->getBaseDiscount(true, $language);
			$tokens['{coupon_expiry}'] = $language->getDateTimeOutput($endDate, $endTime);
		}
		
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
		
		return $tokens;
	}
}