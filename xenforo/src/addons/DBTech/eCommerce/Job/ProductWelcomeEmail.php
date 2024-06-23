<?php

namespace DBTech\eCommerce\Job;

use XF\Job\UserEmail;

/**
 * Class ProductWelcomeEmail
 *
 * @package DBTech\eCommerce\Job
 */
class ProductWelcomeEmail extends UserEmail
{
	/** @var null|\DBTech\eCommerce\Entity\Product */
	protected ?\DBTech\eCommerce\Entity\Product $product;


	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \LogicException
	 */
	protected function setupData(array $data): array
	{
		if (empty($data['product_id']))
		{
			throw new \LogicException('Cannot trigger this job without a product id.');
		}

		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->app->em()->find('DBTech\eCommerce:Product', $data['product_id']);
		if (!$product)
		{
			throw new \LogicException('Cannot trigger this job without a valid product id.');
		}

		$this->product = $product;

		$data['email']['email_format'] = $product->WelcomeEmail->email_format;
		$data['email']['email_title'] = $product->WelcomeEmail->email_title;
		$data['email']['email_body'] = $product->WelcomeEmail->email_body;

		if ($product->WelcomeEmail->from_name)
		{
			$data['email']['from_name'] = $product->WelcomeEmail->from_name;
		}

		if ($product->WelcomeEmail->from_email)
		{
			$data['email']['from_email'] = $product->WelcomeEmail->from_email;
		}

		return parent::setupData($data);
	}


	/**
	 * @param \XF\Entity\User $user
	 * @param $escape
	 *
	 * @return array
	 */
	protected function prepareTokens(\XF\Entity\User $user, $escape): array
	{
		$tokens = [
			'{product}' => $this->product->title,
			'{boardTitle}' => \XF::options()->boardTitle,
			'{name}' => $user->username,
			'{email}' => $user->email,
			'{id}' => $user->user_id,
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

		return $tokens;
	}
}