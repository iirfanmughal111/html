<?php

namespace XenAddons\Showcase\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Item extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('showcase');
	}

	public function actionIndex()
	{
		return $this->view('XenAddons\Showcase:Item', 'xa_sc_item');
	}
	
	public function actionList()
	{
		$this->setSectionContext('xa_scBatchUpdateItems');
	
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');
	
		$page = $this->filterPage();
		$perPage = 50;
	
		$showingAll = $this->filter('all', 'bool');
		if ($showingAll)
		{
			$page = 1;
			$perPage = 5000;
		}
	
		$searcher = $this->searcher('XenAddons\Showcase:Item', $criteria);
		$searcher->setOrder($order, $direction);
	
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);
	
		$total = $finder->total();
		$items = $finder->fetch();
	
		$viewParams = [
			'items' => $items,
		
			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,
		
			'showingAll' => $showingAll,
			'showAll' => (!$showingAll && $total <= 5000),
		
			'criteria' => $searcher->getFilteredCriteria(),
			'order' => $order,
			'direction' => $direction
		];
		return $this->view('XenAddons\Showcase:Item\Listing', 'xa_sc_batch_update_item_list', $viewParams);
	}	
	
	public function actionReplyBans()
	{
		$replyBanRepo = $this->getReplyBanRepo();
		$replyBanFinder = $replyBanRepo->findReplyBansForList();
	
		$user = null;
		$linkParams = [];
		if ($username = $this->filter('username', 'str'))
		{
			$user = $this->finder('XF:User')->where('username', $username)->fetchOne();
			if ($user)
			{
				$replyBanFinder->where('user_id', $user->user_id);
				$linkParams['username'] = $user->username;
			}
		}
	
		$page = $this->filterPage();
		$perPage = 25;
	
		$replyBanFinder->limitByPage($page, $perPage);
		$total = $replyBanFinder->total();
	
		$this->assertValidPage($page, $perPage, $total, 'xa-sc/reply-bans');
	
		$viewParams = [
			'bans' => $replyBanFinder->fetch(),
			'user' => $user,
		
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
		
			'linkParams' => $linkParams
		];
		return $this->view('XenAddons\Showcase:Item\ReplyBan\Listing', 'xa_sc_item_reply_ban_list', $viewParams);
	}
	
	public function actionReplyBansDelete(ParameterBag $params)
	{
		$replyBan = $this->assertReplyBanExists($params->item_reply_ban_id);
	
		if ($this->isPost())
		{
			$replyBan->delete();
			return $this->redirect($this->buildLink('xa-sc/reply-bans'));
		}
		else
		{
			$viewParams = [
				'ban' => $replyBan
			];
			return $this->view('XenAddons\Showcase:Item\ReplyBan\Delete', 'xa_sc_item_reply_ban_delete', $viewParams);
		}
	}
	
	public function actionBatchUpdate()
	{
		$this->setSectionContext('xa_scBatchUpdateItems');
	
		$searcher = $this->searcher('XenAddons\Showcase:Item');
	
		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'success' => $this->filter('success', 'bool')
		] + $searcher->getFormData();
		return $this->view('XenAddons\Showcase:Item\BatchUpdate', 'xa_sc_item_batch_update', $viewParams);
	}
	
	public function actionBatchUpdateConfirm()
	{
		$this->setSectionContext('xa_scBatchUpdateItems');
	
		$this->assertPostOnly();
	
		$criteria = $this->filter('criteria', 'array');
		$searcher = $this->searcher('XenAddons\Showcase:Item', $criteria);
	
		$itemIds = $this->filter('item_ids', 'array-uint');
	
		$total = count($itemIds) ?: $searcher->getFinder()->total();
		if (!$total)
		{
			throw $this->exception($this->error(\XF::phraseDeferred('no_items_matched_your_filter')));
		}
	
		if ($itemIds)
		{
			$itemFinder = $this->finder('XenAddons\Showcase:Item');
			$itemFinder->where('item_id', $itemIds);
		}
		else
		{
			$itemFinder = clone $searcher->getFinder();
		}
		$hasPrefixes = (bool)$itemFinder
			->where('prefix_id', '>', 0)
			->total();
	
		/** @var \XenAddons\Showcase\Repository\ItemPrefix $prefixRepo */
		$prefixRepo = $this->repository('XenAddons\Showcase:ItemPrefix');
		$prefixes = $prefixRepo->getPrefixListData();
	
		/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
		$categoryRepo = $this->repository('XenAddons\Showcase:Category');
		$categories = $categoryRepo->getCategoryOptionsData(false);
	
		$viewParams = [
			'total' => $total,
			'itemIds' => $itemIds,
			'hasPrefixes' => $hasPrefixes,
			'criteria' => $searcher->getFilteredCriteria(),
		
			'prefixes' => $prefixes,
			'categories' => $categories
		];
		return $this->view('XenAddons\Showcase:Item\BatchUpdate\Confirm', 'xa_sc_item_batch_update_confirm', $viewParams);
	}
	
	public function actionBatchUpdateAction()
	{
		$this->setSectionContext('xa_scBatchUpdateItems');
	
		$this->assertPostOnly();
	
		if ($this->request->exists('item_ids'))
		{
			$itemIds = $this->filter('item_ids', 'json-array');
			$total = count($itemIds);
			$jobCriteria = null;
		}
		else
		{
			$criteria = $this->filter('criteria', 'json-array');
	
			$searcher = $this->searcher('XenAddons\Showcase:Item', $criteria);
			$total = $searcher->getFinder()->total();
			$jobCriteria = $searcher->getFilteredCriteria();
	
			$itemIds = null;
		}
	
		if (!$total)
		{
			throw $this->exception($this->error(\XF::phraseDeferred('no_items_matched_your_filter')));
		}
	
		$actions = $this->filter('actions', 'array');
	
		if ($this->request->exists('confirm_delete') && empty($actions['delete']))
		{
			return $this->error(\XF::phrase('you_must_confirm_deletion_to_proceed'));
		}
	
		$this->app->jobManager()->enqueueUnique('itemAction', 'XenAddons\Showcase:ItemAction', [
			'total' => $total,
			'actions' => $actions,
			'itemIds' => $itemIds,
			'criteria' => $jobCriteria
		]);
	
		return $this->redirect($this->buildLink('xa-sc/batch-update', null, ['success' => true]));
	}
	
	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \XenAddons\Showcase\Entity\ItemReplyBan
	 */
	protected function assertReplyBanExists($id, array $extraWith = [], $phraseKey = null)
	{
		$extraWith[] = 'Item';
		$extraWith[] = 'User';
		return $this->assertRecordExists('XenAddons\Showcase:ItemReplyBan', $id, $extraWith, $phraseKey);
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\ItemReplyBan
	 */
	protected function getReplyBanRepo()
	{
		return $this->repository('XenAddons\Showcase:ItemReplyBan');
	}
}