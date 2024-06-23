<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Download
 * @package DBTech\eCommerce\Admin\Controller
 */
class Download extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomDownload');
		
		$productRepo = $this->getProductRepo();
		if (!$productRepo->findProductsForList()->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_product_before_continuing')));
		}
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\Download $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:Download', $criteria);
		
		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}
		
		$searcher->setOrder($order, $direction);
		
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);

		$finder->with('Product');

		$filter = $this->filter('_xfFilter', [
			'text' => 'str',
			'prefix' => 'bool'
		]);
		if ($filter['text'] !== '')
		{
			$finder->searchText($filter['text'], false, $filter['prefix']);
		}
		
		$total = $finder->total();
		$entries = $finder->fetch();

		$viewParams = [
			'downloads' => $entries,

			'criteria' => $searcher->getFilteredCriteria(),
			'filter' => $filter['text'],

			'page' => $page,
			'perPage' => $perPage,
			'total' => $total
		];
		return $this->view('DBTech\eCommerce:Download\Listing', 'dbtech_ecommerce_download_list', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Download $download
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \Exception
	 */
	protected function downloadAddEdit(\DBTech\eCommerce\Entity\Download $download): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'download' => $download,
			'renderedOptions' => $download->getHandler()->renderEdit($download)
		];
		return $this->view('DBTech\eCommerce:Download\Edit', 'dbtech_ecommerce_download_edit', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertDownloadExists($params->download_id);
		return $this->downloadAddEdit($download);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\Download\Create
	 * @throws \Exception
	 */
	protected function setupDownloadCreate(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\Download\Create
	{
		$handler = $this->getDownloadRepo()->getDownloadHandler($this->filter('download_type', 'str'), true);
		
		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$changeLog = $editorPlugin->fromInput('change_log');
		$releaseNotes = $editorPlugin->fromInput('release_notes');
		
		/** @var \DBTech\eCommerce\Service\Download\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Download\Create', $product, $handler);
		$creator->setPerformValidations(false);
		
		$bulkInput = $this->filter([
			'download_type' => 'str',
			'version_string' => 'str',
			'has_new_features' => 'bool',
			'has_changed_features' => 'bool',
			'has_bug_fixes' => 'bool',
			'is_unstable' => 'bool',
		]);
		$creator->getDownload()->bulkSet($bulkInput);
		
		$creator->setChangeLog($changeLog);
		$creator->setReleaseNotes($releaseNotes);
		
		$handler->setEditData($creator, $this->filter('handler_data', 'array'));
		
		$dateInput = $this->filter([
			'date' => 'datetime',
			'time' => 'str',
		]);
		$creator->setDateTime($dateInput['date'], $dateInput['time']);
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Download\Create $creator
	 */
	protected function finalizeDownloadCreate(\DBTech\eCommerce\Service\Download\Create $creator)
	{
		$creator->sendNotifications();
		
		/** @var \DBTech\eCommerce\Entity\Download $download */
		$download = $creator->getDownload();
		
		if (\XF::visitor()->user_id)
		{
			if ($download->download_state == 'moderated')
			{
				$this->session()->setHasContentPendingApproval();
			}
		}
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionAdd()
	{
		$productName = $this->filter('product', 'str');
		$copyDownloadId = $this->filter('source_download_id', 'uint');
		$productId = $this->filter('product_id', 'uint');
		
		$product = null;
		if ($productName)
		{
			/** @var \DBTech\eCommerce\Finder\Product $finder */
			$finder = $this->finder('DBTech\eCommerce:Product');
			$finder->searchText($productName, false, false, true);
			
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $finder->fetchOne();
			
			$downloadType = $this->filter('download_type', 'str');
			if ($product && $product->hasDownloadFunctionality() && $this->getDownloadRepo()->getDownloadHandler($downloadType))
			{
				return $this->redirect($this->buildLink('dbtech-ecommerce/downloads/add', null, [
					'product_id' => $product->product_id,
					'download_type' => $downloadType
				]));
			}
		}
		elseif ($productId)
		{
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $this->em()->find('DBTech\eCommerce:Product', $productId);
			
			$downloadType = $this->filter('download_type', 'str');
			if ($product && $product->hasDownloadFunctionality() && $this->getDownloadRepo()->getDownloadHandler($downloadType))
			{
				/** @var \DBTech\eCommerce\Entity\Download $download */
				$download = $this->em()->create('DBTech\eCommerce:Download');
				$download->product_id = $product->product_id;
				$download->download_type = $downloadType;
				
				$download->hydrateRelation('Product', $product);
				
				return $this->downloadAddEdit($download);
			}
		}
		elseif ($copyDownloadId)
		{
			$copyDownload = $this->assertDownloadExists($copyDownloadId);
			
			$copyDownloadArray = $copyDownload->toArray(false);
			foreach ([
				'download_id',
				'release_date',
				'download_state',
				'reactions'
			] as $key)
			{
				unset($copyDownloadArray[$key]);
			}
			
			/** @var \DBTech\eCommerce\Entity\Download $download */
			$download = $this->em()->create('DBTech\eCommerce:Download');
			$download->bulkSet($copyDownloadArray);
			
			if ($copyDownload->Versions)
			{
				$download->hydrateRelation('Versions', $copyDownload->Versions);
			}
			
			return $this->downloadAddEdit($download);
		}
		
		$viewParams = [
			'handlers' => [],
			'product' => $product
		];
		
		$handlers = $this->getDownloadRepo()->getDownloadHandlers();
		
		/** @var \DBTech\eCommerce\Download\AbstractHandler $handler */
		foreach ($handlers as $type => $handler)
		{
			if ($handler->isEnabled())
			{
				$viewParams['handlers'][$type] = $this->app()->getContentTypePhrase($type);
			}
		}

		return $this->view('DBTech\eCommerce:Download\AddChooser', 'dbtech_ecommerce_download_add_chooser', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Download $download
	 *
	 * @return \DBTech\eCommerce\Service\Download\Edit
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	protected function setupDownloadEdit(\DBTech\eCommerce\Entity\Download $download): \DBTech\eCommerce\Service\Download\Edit
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $download->Product;
		
		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$changeLog = $editorPlugin->fromInput('change_log');
		$releaseNotes = $editorPlugin->fromInput('release_notes');
		
		/** @var \DBTech\eCommerce\Service\Download\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Download\Edit', $download);
		
		$bulkInput = $this->filter([
			'version_string' => 'str',
			'has_new_features' => 'bool',
			'has_changed_features' => 'bool',
			'has_bug_fixes' => 'bool',
			'is_unstable' => 'bool',
		]);
		$editor->getDownload()->bulkSet($bulkInput);
		
		$editor->setChangeLog($changeLog);
		$editor->setReleaseNotes($releaseNotes);
		
		$editor->getHandler()->setEditData($editor, $this->filter('handler_data', 'array'));
		
		if ($this->filter('author_alert', 'bool') && $product->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Download\Edit $editor
	 */
	protected function finalizeDownloadEdit(\DBTech\eCommerce\Service\Download\Edit $editor)
	{
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();
		
		if ($params->download_id)
		{
			$download = $this->assertDownloadExists($params->download_id);
			
			$editor = $this->setupDownloadEdit($download);
//			$editor->checkForSpam();
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$user = $download->Product->User ?: \XF::visitor();
			\XF::asVisitor($user, function () use ($editor)
			{
				$editor->save();
			});
			
			$this->finalizeDownloadEdit($editor);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/downloads') . $this->buildLinkHash($download->download_id));
		}
		
		$productId = $this->filter('product_id', 'uint');
		$product = $this->assertProductExists($productId);
		
		$creator = $this->setupDownloadCreate($product);
		//			$creator->checkForSpam();
		
		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}
		
		$user = $product->User ?: \XF::visitor();
		$download = \XF::asVisitor($user, function () use ($creator)
		{
			/** @var \DBTech\eCommerce\Entity\Product $download */
			return $creator->save();
		});
		
		$this->finalizeDownloadCreate($creator);
		
		return $this->redirect($this->buildLink('dbtech-ecommerce/downloads') . $this->buildLinkHash($download->download_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$download = $this->assertDownloadExists($params->download_id);
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Delete');
		return $plugin->actionDeleteWithState(
			$download,
			'download_state',
			'DBTech\eCommerce:Download\Delete',
			'dbtech_ecommerce_download',
			$this->buildLink('dbtech-ecommerce/downloads/delete', $download),
			$this->buildLink('dbtech-ecommerce/downloads/edit', $download),
			$this->buildLink('dbtech-ecommerce/downloads'),
			$download->title,
			true,
			true
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionResetCache(ParameterBag $params)
	{
		$download = $this->assertDownloadExists($params->download_id);
		
		if ($this->isPost())
		{
			\XF::fs()->deleteDir($download->getReleaseAbstractPath());
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/downloads') . $this->buildLinkHash($download->download_id));
		}
		
		$viewParams = [
			'download' => $download,
		];
		return $this->view('DBTech\eCommerce:Download\ResetCache', 'dbtech_ecommerce_download_reset_cache', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getDownloadSearcherParams();
		
		return $this->view('DBTech\eCommerce:Download\Search', 'dbtech_ecommerce_download_search', $viewParams);
	}
	
	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getDownloadSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\Download $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:Download');
		
		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Download
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertDownloadExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Download
	{
		return $this->assertRecordExists('DBTech\eCommerce:Download', $id, $with, $phraseKey);
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Product
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertProductExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Product
	{
		return $this->assertRecordExists('DBTech\eCommerce:Product', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Download|\XF\Mvc\Entity\Repository
	 */
	protected function getDownloadRepo()
	{
		return $this->repository('DBTech\eCommerce:Download');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('DBTech\eCommerce:Category');
	}
}