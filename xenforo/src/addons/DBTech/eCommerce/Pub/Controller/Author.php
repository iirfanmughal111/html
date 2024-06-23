<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Author
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Author extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();

		if (!$visitor->canViewDbtechEcommerceProducts($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\Reroute
	 */
	public function actionIndex(ParameterBag $params)
	{
		if ($params->user_id)
		{
			return $this->rerouteController('DBTech\eCommerce:Author', 'Author', $params);
		}

		/** @var \XF\Entity\MemberStat $memberStat */
		$memberStat = $this->em()->findOne('XF:MemberStat', ['member_stat_key' => 'dbtech_ecommerce_most_products']);

		if ($memberStat && $memberStat->canView())
		{
			return $this->redirectPermanently(
				$this->buildLink('members', null, ['key' => $memberStat->member_stat_key])
			);
		}
		
		return $this->redirect($this->buildLink('dbtech-ecommerce'));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAuthor(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id, ['DBTechEcommerceCommission']);

		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = $this->repository('DBTech\eCommerce:Category');
		$viewableCategoryIds = $categoryRepo->getViewableCategoryIds();

		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = $this->repository('DBTech\eCommerce:Product');
		$finder = $productRepo->findProductsByUser($user->user_id, $viewableCategoryIds)
			->where('parent_product_id', 0)
			->where('is_listed', true)
		;

		$total = $finder->total();

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceProductsPerPage;

		$this->assertValidPage($page, $perPage, $total, 'dbtech-ecommerce/authors', $user);
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/authors', $user, ['page' => $page]));

		$products = $finder->limitByPage($page, $perPage)->fetch();
		$products = $products->filterViewable();

		$canInlineMod = false;
		foreach ($products AS $product)
		{
			/** @var \DBTech\eCommerce\Entity\Product $product */
			if ($product->canUseInlineModeration())
			{
				$canInlineMod = true;
				break;
			}
		}

		$viewParams = [
			'user' => $user,
			'products' => $products,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			'canInlineMod' => $canInlineMod
		];
		return $this->view('DBTech\eCommerce:Author\View', 'dbtech_ecommerce_author_view', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIncomeStats(ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id, ['DBTechEcommerceCommission']);
		
		$this->assertCanViewIncomeStatsForUser($user);
		
		/** @var \DBTech\eCommerce\Entity\Commission $commission */
		$commission = $user->DBTechEcommerceCommission;
		
		if (!$commission)
		{
			if ($user->user_id == \XF::visitor()->user_id)
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_you_have_no_commissions'));
			}
			else
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_this_user_has_no_commissions'));
			}
		}
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\CommissionPayment $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:CommissionPayment', [
			'commission_id' => $commission->commission_id
		]);
		
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);
		
		$finder->with(['Commission', 'Ip', 'User']);
		
		$total = $finder->total();
		$entries = $finder->fetch();
		
		
		// Find outstanding payments
		
		$productCommissions = $this->finder('DBTech\eCommerce:ProductCommissionValue')
			->with('Commission')
			->where('commission_id', $commission->commission_id)
			->keyedBy('product_id')
			->fetch()
		;
		
		$applicablePurchases = $this->finder('DBTech\eCommerce:PurchaseLog')
			->where('product_id', $productCommissions->keys())
			->where('log_date', '>', $commission->last_paid_date)
			->fetch()
		;
		
		$amountOwed = 0;
		
		/** @var \DBTech\eCommerce\Entity\PurchaseLog $purchase */
		foreach ($applicablePurchases as $purchase)
		{
			/** @var \DBTech\eCommerce\Entity\ProductCommissionValue $productCommission */
			$productCommission = $productCommissions[$purchase->product_id];
			
			$amountOwed += $productCommission->getCommission($purchase);
		}
		
		$viewParams = [
			'entries' => $entries,
			
			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,
			
			'criteria' => $searcher->getFilteredCriteria(),
			'sortOptions' => $searcher->getOrderOptions(),
			
			'user' => $user,
			
			'commission' => $commission,
			'commissions' => $productCommissions,
			'purchases' => $applicablePurchases,
			'amountOwed' => $amountOwed
		];
		return $this->view('DBTech\eCommerce:IncomeStats\Stats', 'dbtech_ecommerce_income_stats', $viewParams);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCanViewIncomeStatsForUser(\XF\Entity\User $user)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (
			(
				$user->user_id != $visitor->user_id
				&& !$visitor->canViewAnyDbtechEcommerceIncomeStats()
			)
			|| (
				$user->user_id == $visitor->user_id
				&& !$visitor->canViewDbtechEcommerceIncomeStats()
			)
		) {
			throw $this->exception($this->noPermission());
		}
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\IncomeStats
	 */
	protected function getIncomeStatsRepo(): \DBTech\eCommerce\Repository\IncomeStats
	{
		return $this->repository('DBTech\eCommerce:IncomeStats');
	}

	/**
	 * @param array $activities
	 *
	 * @return array|bool|\XF\Phrase
	 */
	public static function getActivityDetails(array $activities)
	{
		$userIds = [];
		$userData = [];
		
		$router = \XF::app()->router('public');
		$defaultPhrase = \XF::phrase('dbtech_ecommerce_viewing_author_profile');
		
		if (!\XF::visitor()->hasPermission('general', 'viewProfile'))
		{
			return $defaultPhrase;
		}
		
		foreach ($activities AS $activity)
		{
			$userId = $activity->pluckParam('user_id');
			if ($userId)
			{
				$userIds[$userId] = $userId;
			}
		}
		
		if ($userIds)
		{
			$users = \XF::em()->findByIds('XF:User', $userIds, 'Privacy');
			foreach ($users AS $user)
			{
				$userData[$user->user_id] = [
					'username' => $user->username,
					'url' => $router->buildLink('members', $user),
				];
			}
		}
		
		$output = [];
		
		foreach ($activities AS $key => $activity)
		{
			$userId = $activity->pluckParam('user_id');
			$user = $userId && isset($userData[$userId]) ? $userData[$userId] : null;
			if ($user)
			{
				$output[$key] = [
					'description' => \XF::phrase('dbtech_ecommerce_viewing_author_profile'),
					'title' => $user['username'],
					'url' => $user['url']
				];
			}
			else
			{
				$output[$key] = $defaultPhrase;
			}
		}
		
		return $output;
	}
}