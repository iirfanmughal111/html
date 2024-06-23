<?php

namespace XenAddons\Showcase\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Feed extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('showcase');
	}

	public function actionIndex(ParameterBag $params)
	{
		$feedRepo = $this->getFeedRepo();

		$viewParams = [
			'feeds' => $feedRepo->findFeedsForList()->fetch()
		];
		return $this->view('XenAddons\Showcase:Feed\Listing', 'xa_sc_feed_list', $viewParams);
	}

	public function feedAddEdit(\XenAddons\Showcase\Entity\Feed $feed)
	{
		$categoryRepo = $this->repository('XenAddons\Showcase:Category');

		$prefixes = [];
		if ($feed->category_id)
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			$category = $this->em()->find('XenAddons\Showcase:Category', $feed->category_id);
			if ($category)
			{
				$prefixes = $category->getPrefixesGrouped();
			}
		}
		
		$viewParams = [
			'feed' => $feed,
			'categories' => $categoryRepo->getCategoryOptionsData(false),
			'prefixes' => $prefixes
		];
		return $this->view('XenAddons\Showcase:Feed\Edit', 'xa_sc_feed_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$feed = $this->assertFeedExists($params->feed_id);
		return $this->feedAddEdit($feed);
	}

	public function actionAdd()
	{
		$feed = $this->em()->create('XenAddons\Showcase:Feed');

		return $this->feedAddEdit($feed);
	}

	protected function getFeedInput()
	{
		return $this->filter([
			'title' => 'str',
			'url' => 'str',
			'frequency' => 'uint',
			'active' => 'bool',

			'user_id' => 'int',

			'category_id' => 'uint',
			'prefix_id' => 'uint',
			'title_template' => 'str',
			'message_template' => 'str',

			'item_visible' => 'bool'
		]);
	}

	protected function feedSaveProcess(\XenAddons\Showcase\Entity\Feed $feed)
	{
		$form = $this->formAction();

		$input = $this->getFeedInput();
		if ($input['user_id'] == -1)
		{
			$username = $this->filter('username', 'str');
			$user = $this->finder('XF:User')->where('username', $username)->fetchOne();
			if ($user)
			{
				$input['user_id'] = $user['user_id'];
			}
		}
		$input['user_id'] = intval(max($input['user_id'], 0));

		$reader = $this->getFeedReader($input['url']);
		$feedData = $reader->getFeedData(false);

		$this->assertValidFeedData($feedData, $reader, false);

		if (!$input['title'] && !empty($feedData['title']))
		{
			$input['title'] = $feedData['title'];
		}

		$form->basicEntitySave($feed, $input);

		return $form;
	}

	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($this->request->exists('preview'))
		{
			return $this->rerouteController(__CLASS__, 'preview', $params);
		}

		if ($params->feed_id)
		{
			$feed = $this->assertFeedExists($params->feed_id);
		}
		else
		{
			$feed = $this->em()->create('XenAddons\Showcase:Feed');
		}

		$this->feedSaveProcess($feed)->run();

		return $this->redirect($this->buildLink('xa-sc/feeds'));
	}

	public function actionPreview()
	{
		$input = $this->getFeedInput();

		/** @var \XenAddons\Showcase\Entity\Feed $feed */
		$feed = $this->em()->create('XenAddons\Showcase:Feed');
		$feed->bulkSet($input);

		$reader = $this->getFeedReader($feed['url']);
		$feedData = $reader->getFeedData();

		$this->assertValidFeedData($feedData, $reader);

		if (!$feed->title && $feedData['title'])
		{
			$feed->title = $feedData['title'];
		}

		$entry = $feedData['entries'][mt_rand(0, count($feedData['entries']) - 1)];

		$title = $feed->getEntryTitle($entry);
		$message = $feed->getEntryMessage($entry);
		
		$entry['title'] = $title;
		$entry['message'] = $message;

		if ($input['user_id'] == 0)
		{
			$entry['author'] = $feed->title;
		}
		else if ($input['user_id'] == -1)
		{
			$entry['author'] = $this->filter('username', 'str');
		}

		$viewParams = [
			'feed' => $feed,
			'feedData' => $feedData,
			'entry' => $entry
		];
		return $this->view('XenAddons\Showcase:\Feed\Preview', 'xa_sc_feed_preview', $viewParams);
	}

	public function actionDelete(ParameterBag $params)
	{
		$feed = $this->assertFeedExists($params->feed_id);
		if (!$feed->preDelete())
		{
			return $this->error($feed->getErrors());
		}

		if ($this->isPost())
		{
			$feed->delete();

			return $this->redirect($this->buildLink('xa-sc/feeds'));
		}
		else
		{
			$viewParams = [
				'feed' => $feed
			];
			return $this->view('XenAddons\Showcase:Feed\Delete', 'xa_sc_feed_delete', $viewParams);
		}
	}

	public function actionToggle()
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('XenAddons\Showcase:Feed');
	}

	public function actionImport(ParameterBag $params)
	{
		$feed = $this->assertFeedExists($params->feed_id, ['Category']);
		if (!$feed->Category)
		{
			throw $this->exception($this->error(\XF::phrase('cannot_find_associated_category')));
		}

		$feeder = $this->getFeedFeeder($feed->url);
		if ($feeder->setupImport($feed, true) && $feeder->countPendingEntries())
		{
			$feeder->importEntries();
		}
		return $this->redirect($this->buildLink('xa-sc/feeds'));
	}

	protected function assertValidFeedData($feedData, \XenAddons\Showcase\Service\Feed\Reader $reader, $checkEntries = true)
	{
		if (!$feedData || ($checkEntries && empty($feedData['entries'])))
		{
			throw $this->exception($this->error(
				\XF::phrase('there_was_problem_requesting_feed', [
					'message' => $reader->getException()
						? $reader->getException()->getMessage()
						: \XF::phrase('n_a')
				])
			));
		}
	}

	/**
	 * @param $url
	 *
	 * @return \XenAddons\Showcase\Service\Feed\Reader
	 */
	protected function getFeedReader($url)
	{
		return $this->service('XenAddons\Showcase:Feed\Reader', $url);
	}

	/**
	 * @param $url
	 *
	 * @return \XenAddons\Showcase\Service\Feed\Feeder
	 */
	protected function getFeedFeeder($url)
	{
		return $this->service('XenAddons\Showcase:Feed\Feeder', $url);
	}

	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \XenAddons\Showcase\Entity\Feed
	 */
	protected function assertFeedExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('XenAddons\Showcase:Feed', $id, $with, $phraseKey);
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Feed
	 */
	protected function getFeedRepo()
	{
		return $this->repository('XenAddons\Showcase:Feed');
	}
}