<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Discount
 * @package DBTech\eCommerce\Admin\Controller
 */
class Discount extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomDiscount');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'discounts' => $this->getDiscountRepo()
				->findDiscountsForList()
				->fetch()
		];
		return $this->view('DBTech\eCommerce:Discount\Listing', 'dbtech_ecommerce_discount_list', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Discount $discount
	 * @return \XF\Mvc\Reply\View
	 */
	protected function discountAddEdit(\DBTech\eCommerce\Entity\Discount $discount): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'discount' => $discount
		];
		return $this->view('DBTech\eCommerce:Discount\Edit', 'dbtech_ecommerce_discount_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$discount = $this->assertDiscountExists($params->discount_id);
		return $this->discountAddEdit($discount);
	}
	
	/**
	 * @return \DBTech\eCommerce\Service\Discount\Create
	 */
	protected function setupDiscountCreate(): \DBTech\eCommerce\Service\Discount\Create
	{
		/** @var \DBTech\eCommerce\Service\Discount\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Discount\Create');
		
		$bulkInput = $this->filter([
			'discount_threshold' => 'float',
			'discount_percent' => 'float',
		]);
		$creator->getDiscount()->bulkSet($bulkInput);
		
		$creator->setTitle($this->filter('title', 'str'));
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Discount\Create $creator
	 */
	protected function finalizeDiscountCreate(\DBTech\eCommerce\Service\Discount\Create $creator)
	{
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		$copyDiscountId = $this->filter('source_discount_id', 'uint');
		if ($copyDiscountId)
		{
			$copyDiscount = $this->assertDiscountExists($copyDiscountId)->toArray(false);
			foreach ([
				'discount_id'
			] as $key)
			{
				unset($copyDiscount[$key]);
			}
			
			/** @var \DBTech\eCommerce\Entity\Discount $discount */
			$discount = $this->em()->create('DBTech\eCommerce:Discount');
			$discount->bulkSet($copyDiscount);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Discount $discount */
			$discount = $this->em()->create('DBTech\eCommerce:Discount');
		}
		
		return $this->discountAddEdit($discount);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Discount $discount
	 *
	 * @return \DBTech\eCommerce\Service\Discount\Edit
	 */
	protected function setupDiscountEdit(\DBTech\eCommerce\Entity\Discount $discount): \DBTech\eCommerce\Service\Discount\Edit
	{
		/** @var \DBTech\eCommerce\Service\Discount\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Discount\Edit', $discount);
		
		$bulkInput = $this->filter([
			'discount_threshold' => 'float',
			'discount_percent' => 'float',
		]);
		$editor->getDiscount()->bulkSet($bulkInput);
		
		$editor->setTitle($this->filter('title', 'str'));
		
		return $editor;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Discount\Edit $editor
	 */
	protected function finalizeDiscountEdit(\DBTech\eCommerce\Service\Discount\Edit $editor)
	{
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		if ($params->discount_id)
		{
			/** @var \DBTech\eCommerce\Entity\Discount $discount */
			$discount = $this->assertDiscountExists($params->discount_id);
		
			/** @var \DBTech\eCommerce\Service\Discount\Edit $editor */
			$editor = $this->setupDiscountEdit($discount);
			//			$editor->checkForSpam();
		
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
			$this->finalizeDiscountEdit($editor);
		}
		else
		{
			/** @var \DBTech\eCommerce\Service\Discount\Create $creator */
			$creator = $this->setupDiscountCreate();
			//			$creator->checkForSpam();
		
			if (!$creator->validate($errors))
			{
				throw $this->exception($this->error($errors));
			}
		
			/** @var \DBTech\eCommerce\Entity\Discount $discount */
			$discount = $creator->save();
			$this->finalizeDiscountCreate($creator);
		}
	
		return $this->redirect($this->buildLink('dbtech-ecommerce/discounts') . $this->buildLinkHash($discount->discount_id));
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$discount = $this->assertDiscountExists($params->discount_id);
	
		/** @var \DBTech\eCommerce\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Delete');
		return $plugin->actionDeleteWithState(
			$discount,
			'discount_state',
			'DBTech\eCommerce:Discount\Delete',
			'dbtech_ecommerce_discount',
			$this->buildLink('dbtech-ecommerce/discounts/delete', $discount),
			$this->buildLink('dbtech-ecommerce/discounts/edit', $discount),
			$this->buildLink('dbtech-ecommerce/discounts'),
			$discount->title,
			true,
			false
		);
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Discount
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertDiscountExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Discount
	{
		return $this->assertRecordExists('DBTech\eCommerce:Discount', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Discount|\XF\Mvc\Entity\Repository
	 */
	protected function getDiscountRepo()
	{
		return $this->repository('DBTech\eCommerce:Discount');
	}
}