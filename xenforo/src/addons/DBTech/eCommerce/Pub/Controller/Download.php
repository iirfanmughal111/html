<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;
use DBTech\eCommerce\Entity\DownloadVersion;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Download extends AbstractController
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
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIndex(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/release', $download));
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_product', $download->product_id);
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_download', $download->download_id);
		
		$license = $this->assertValidLicenseParameter($params);
		
		$viewParams = [
			'product' => $download->Product,
			'download' => $download,
			
			'license' => $license
		];
		return $this->view('DBTech\eCommerce:Download\View', 'dbtech_ecommerce_download_view', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionDownload(ParameterBag $params)
	{
		if (!$params->download_id && $downloadId = $this->filter('download_id', 'uint'))
		{
			// This is a bit of a hack to ensure that downloads from the drop-down works
			$params->download_id = $downloadId;
		}
		
		$download = $this->assertViewableDownload($params->download_id);
		
//		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/release/download', $download));
		
		$license = $this->assertValidLicenseParameter($params);

		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('dbtech_ecommerce_download', $download->download_id);
		
		if (!$download->canDownload($license, $error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Terms $terms */
		$terms = $this->plugin('DBTech\eCommerce:Terms');
		
		if ($this->isPost() || $visitor->hasAcceptedDbtechEcommerceTerms())
		{
			$terms->setTermsAccepted();
			
			/** @var DownloadVersion|null $version */
			$version = null;
			
			$versions = $download->getDownloadOptions($license);
			
			$versionIndex = $params->version_id . ($license ? '_full' : '_demo');
			
			if ($versions->count() == 0)
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_cannot_download'));
			}
			elseif ($versions->count() == 1)
			{
				$version = $versions->first();
			}
			elseif ($versionIndex && isset($versions[$versionIndex]))
			{
				$version = $versions[$versionIndex];
			}

			$file = $this->filter('file', 'uint');
			if (!$file && (!$version || $download->forceDownloadChooser($version)))
			{
				$renderedDownloads = $download->getHandler()->renderDownload($download, $license);
				if (!$renderedDownloads)
				{
					return $this->error(\XF::phrase('dbtech_ecommerce_no_available_downloads_found'));
				}
				
				$viewParams = [
					'download' => $download,
					'downloadOptions' => $renderedDownloads,
					'license' => $license
				];
				return $this->view('DBTech\eCommerce:Download\VersionChooser', 'dbtech_ecommerce_download_version_chooser', $viewParams);
			}

			/** @var \DBTech\eCommerce\ControllerPlugin\AbstractDownload $downloadPlugin */
			$downloadPlugin = $this->plugin($download->getHandlerIdentifier());
			return $downloadPlugin->download($version, $license);
		}

		$viewParams = [
			'product'  => $download->Product,
			'download' => $download,
			
			'license' => $license
		];
		return $this->view('DBTech\eCommerce:Download\Terms', 'dbtech_ecommerce_download_terms', $viewParams);
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
		$download = $this->assertViewableDownload($params->download_id);
		
		if (!$download->canEdit($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
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
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->assertViewableProduct($params->product_id);
		
		if (!$product->canReleaseUpdate())
		{
			throw $this->exception($this->noPermission());
		}
		
		$downloadType = $this->filter('download_type', 'str');
		if ($downloadType && $this->getDownloadRepo()->getDownloadHandler($downloadType))
		{
			/** @var \DBTech\eCommerce\Entity\Download $download */
			$download = $this->em()->create('DBTech\eCommerce:Download');
			$download->product_id = $product->product_id;
			$download->download_type = $downloadType;
			
			$download->hydrateRelation('Product', $product);
			
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
			$download = $this->assertViewableDownload($params->download_id);
			
			if (!$download->canEdit($error))
			{
				throw $this->exception($this->noPermission($error));
			}
			
			$editor = $this->setupDownloadEdit($download);
			$editor->checkForSpam();
			
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
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/releases', $download->Product));
		}
		
		$productId = $this->filter('product_id', 'uint');
		$product = $this->assertViewableProduct($productId);
		
		if (!$product->canReleaseUpdate())
		{
			throw $this->exception($this->noPermission());
		}
		
		$creator = $this->setupDownloadCreate($product);
		$creator->checkForSpam();
		
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
		
		return $this->redirect($this->buildLink('dbtech-ecommerce', $product));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionBookmark(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		
		/** @var \XF\ControllerPlugin\Bookmark $bookmarkPlugin */
		$bookmarkPlugin = $this->plugin('XF:Bookmark');
		
		return $bookmarkPlugin->actionBookmark(
			$download,
			$this->buildLink('dbtech-ecommerce/release/bookmark', $download)
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function actionDelete(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		if (!$download->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}
		
		if ($this->isPost())
		{
			if ($download->download_state == 'deleted')
			{
				$type = $this->filter('hard_delete', 'uint');
				switch ($type)
				{
					case 0:
						return $this->redirect($this->buildLink('dbtech-ecommerce/releases', $download->Product));
					
					case 1:
						$reason = $this->filter('reason', 'str');
						if (!$download->canDelete('hard', $error))
						{
							return $this->noPermission($error);
						}
						
						// Do this because we want to be able to redirect back to the product
						// 	and relying on $download->Product is not good
						$product = $this->assertViewableProduct($download->product_id);
						
						/** @var \DBTech\eCommerce\Service\Download\Delete $deleter */
						$deleter = $this->service('DBTech\eCommerce:Download\Delete', $download);
						
						if ($this->filter('author_alert', 'bool'))
						{
							$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
						}
						
						$deleter->delete('hard', $reason);
						
						/** @var \XF\ControllerPlugin\InlineMod $inlineModPlugin */
						$inlineModPlugin = $this->plugin('XF:InlineMod');
						$inlineModPlugin->clearIdFromCookie('dbtech_ecommerce_download', $download->download_id);
						
						return $this->redirect($this->buildLink('dbtech-ecommerce/releases', $product));
					
					case 2:
						/** @var \DBTech\eCommerce\Service\Download\Delete $deleter */
						$deleter = $this->service('DBTech\eCommerce:Download\Delete', $download);
						
						if ($this->filter('author_alert', 'bool'))
						{
							$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
						}
						
						$deleter->unDelete();
						
						return $this->redirect($this->buildLink('dbtech-ecommerce/releases', $download->Product));
				}
			}
			else
			{
				$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
				$reason = $this->filter('reason', 'str');
				if (!$download->canDelete($type, $error))
				{
					return $this->noPermission($error);
				}
				
				/** @var \DBTech\eCommerce\Service\Download\Delete $deleter */
				$deleter = $this->service('DBTech\eCommerce:Download\Delete', $download);
				
				if ($this->filter('author_alert', 'bool'))
				{
					$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
				}
				
				$deleter->delete($type, $reason);
				
				/** @var \XF\ControllerPlugin\InlineMod $inlineModPlugin */
				$inlineModPlugin = $this->plugin('XF:InlineMod');
				$inlineModPlugin->clearIdFromCookie('dbtech_ecommerce_download', $download->download_id);
				
				return $this->redirect($this->buildLink('dbtech-ecommerce/releases', $download->Product));
			}
		}

		$viewParams = [
			'download' => $download,
		];
		return $this->view('DBTech\eCommerce:Download\Delete', 'dbtech_ecommerce_download_delete', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionResetCache(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		if (!$download->canDelete())
		{
			return $this->noPermission();
		}
		
		if ($this->isPost())
		{
			\XF::fs()->deleteDir($download->getReleaseAbstractPath());
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/releases', $download->Product));
		}
		
		$viewParams = [
			'download' => $download,
		];
		return $this->view('DBTech\eCommerce:Download\ResetCache', 'dbtech_ecommerce_download_reset_cache', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReport(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		if (!$download->canReport($error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'dbtech_ecommerce_download',
			$download,
			$this->buildLink('dbtech-ecommerce/release/report', $download),
			$this->buildLink('dbtech-ecommerce/release', $download)
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReact(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		if (!$download->canReact($error))
		{
			return $this->noPermission($error);
		}
		
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($download, 'dbtech-ecommerce/release');
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Message|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReactions(ParameterBag $params)
	{
		$download = $this->assertViewableDownload($params->download_id);
		
		$breadcrumbs = $download->Product->Category->getBreadcrumbs();
		
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$download,
			'dbtech-ecommerce/release/reactions',
			null,
			$breadcrumbs
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionWarn(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$download = $this->assertViewableDownload($params->download_id);
		
		if (!$download->canWarn($error))
		{
			return $this->noPermission($error);
		}
		
		$breadcrumbs = $download->getBreadcrumbs();
		
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'dbtech_ecommerce_download',
			$download,
			$this->buildLink('dbtech-ecommerce/release/warn', $download),
			$breadcrumbs
		);
	}

	/**
	 * @param int|null $downloadId
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\Download
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableDownload(?int $downloadId, array $extraWith = []): \DBTech\eCommerce\Entity\Download
	{
		if (!$downloadId)
		{
			throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_download_not_found')));
		}

		$visitor = \XF::visitor();

		$extraWith[] = 'Product';
		$extraWith[] = 'Product.User';
		$extraWith[] = 'Product.Category';
		$extraWith[] = 'Product.Category.Permissions|' . $visitor->permission_combination_id;

		/** @var \DBTech\eCommerce\Entity\Download $download */
		$download = $this->em()->find('DBTech\eCommerce:Download', $downloadId, $extraWith);
		if (!$download)
		{
			throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_download_not_found')));
		}

		if (!$download->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		return $download;
	}
	
	/**
	 * @param int|null $productId
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\Product
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableProduct(?int $productId, array $extraWith = []): \DBTech\eCommerce\Entity\Product
	{
		$visitor = \XF::visitor();
		
		$extraWith[] = 'User';
		$extraWith[] = 'Category';
		$extraWith[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		$extraWith[] = 'LatestVersion';
		$extraWith[] = 'Discussion';
		$extraWith[] = 'Discussion.Forum';
		$extraWith[] = 'Discussion.Forum.Node';
		$extraWith[] = 'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id;
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->em()->find('DBTech\eCommerce:Product', $productId, $extraWith);
		if (!$product)
		{
			throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_product_not_found')));
		}
		
		if (!$product->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		return $product;
	}
	
	/**
	 * @param ParameterBag $params
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\License|null
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertValidLicenseParameter(ParameterBag $params, array $extraWith = []): ?\DBTech\eCommerce\Entity\License
	{
		if (!$params->license_key && $licenseKey = $this->filter('license_key', 'str'))
		{
			// This is a bit of a hack
			$params->license_key = $licenseKey;
		}

		$license = null;
		if ($licenseKey = $params->license_key)
		{
			/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
			$licenseRepo = $this->repository('DBTech\eCommerce:License');
			
			/** @var \DBTech\eCommerce\Entity\License $license */
			$license = $licenseRepo->findLicenseByKey($licenseKey)->with($extraWith)->fetchOne();
			
			if (!$license)
			{
				throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_license_not_found')));
			}
			
			if (!$license->isValid($error))
			{
				throw $this->exception($this->noPermission($error));
			}
		}
		
		return $license;
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
	 * @param array $activities
	 *
	 * @return \XF\Phrase
	 */
	public static function getActivityDetails(array $activities): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_viewing_products');
	}
}