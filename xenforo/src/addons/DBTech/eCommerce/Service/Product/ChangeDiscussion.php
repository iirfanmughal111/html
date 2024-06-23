<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class ChangeDiscussion
 *
 * @package DBTech\eCommerce\Service\Product
 */
class ChangeDiscussion extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;


	/**
	 * ChangeDiscussion constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Product $product
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);
		$this->product = $product;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @return bool
	 * @throws \XF\PrintableException
	 */
	public function disconnectDiscussion(): bool
	{
		$this->product->discussion_thread_id = 0;
		$this->product->save();

		return true;
	}

	/**
	 * @param string $threadUrl
	 * @param bool $checkPermissions
	 * @param null $error
	 *
	 * @return bool
	 * @throws \XF\PrintableException
	 */
	public function changeThreadByUrl(string $threadUrl, bool $checkPermissions = true, &$error = null): bool
	{
		$threadRepo = $this->repository('XF:Thread');
		$thread = $threadRepo->getThreadFromUrl($threadUrl, null, $threadFetchError);
		if (!$thread)
		{
			$error = $threadFetchError;
			return false;
		}

		return $this->changeThreadTo($thread, $checkPermissions, $error);
	}

	/**
	 * @param \XF\Entity\Thread $thread
	 * @param bool $checkPermissions
	 * @param null $error
	 *
	 * @return bool
	 * @throws \XF\PrintableException
	 */
	public function changeThreadTo(\XF\Entity\Thread $thread, bool $checkPermissions = true, &$error = null): bool
	{
		if ($checkPermissions && !$thread->canView($viewError))
		{
			$error = $viewError ?: \XF::phrase('do_not_have_permission');
			return false;
		}

		if ($thread->thread_id === $this->product->discussion_thread_id)
		{
			return true;
		}

		if ($thread->discussion_type != \XF\ThreadType\AbstractHandler::BASIC_THREAD_TYPE)
		{
			$error = \XF::phrase('dbtech_ecommerce_new_product_discussion_thread_must_be_standard_thread');
			return false;
		}

		$this->product->discussion_thread_id = $thread->thread_id;
		$this->product->save();

		return true;
	}
}