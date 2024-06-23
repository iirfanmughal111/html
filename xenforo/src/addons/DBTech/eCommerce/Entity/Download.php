<?php

namespace DBTech\eCommerce\Entity;

use XF\Entity\LinkableInterface;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Entity\BookmarkTrait;
use XF\Entity\ReactionTrait;

/**
 * COLUMNS
 * @property int|null $download_id
 * @property int $product_id
 * @property int $user_id
 * @property string $download_state
 * @property string $version_string
 * @property int $release_date
 * @property string $change_log
 * @property bool $has_new_features
 * @property bool $has_changed_features
 * @property bool $has_bug_fixes
 * @property bool $is_unstable
 * @property string $release_notes
 * @property int $discussion_thread_id
 * @property string $download_type
 * @property int $download_count
 * @property int $full_download_count
 * @property int $attach_count
 * @property int $warning_id
 * @property string $warning_message
 * @property array|null $embed_metadata
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 *
 * GETTERS
 * @property string $title
 * @property string $product_title
 * @property null $EditData
 * @property null $DownloadData
 * @property \DBTech\eCommerce\Download\AbstractHandler|null $Handler
 * @property mixed $reactions
 * @property mixed $reaction_users
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \XF\Entity\User $User
 * @property \XF\Entity\Thread $Discussion
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\DownloadVersion[] $Versions
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\DownloadVersion[] $FullVersions
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductDownload[] $Downloads
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\DownloadLog[] $DownloadLog
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\BookmarkItem[] $Bookmarks
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 */
class Download extends Entity implements LinkableInterface
{
	use BookmarkTrait;
	use ReactionTrait;

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->Product->full_title . ' ' . $this->version_string;
	}

	/**
	 * @return bool
	 */
	public function isIgnored(): bool
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}
	
	/**
	 * @param \XF\Entity\Attachment|int $attachmentId
	 *
	 * @return bool
	 */
	public function isAttachmentEmbedded($attachmentId): bool
	{
		if (!$this->embed_metadata)
		{
			return false;
		}
		
		if ($attachmentId instanceof \XF\Entity\Attachment)
		{
			$attachmentId = $attachmentId->attachment_id;
		}
		
		return isset($this->embed_metadata['attachments'][$attachmentId]);
	}
	
	/**
	 * @return string
	 */
	public function getProductTitle(): string
	{
		return $this->Product->title;
	}
	
	/**
	 * @param License|null $license
	 *
	 * @return \XF\Mvc\Entity\ArrayCollection
	 */
	public function getDownloadOptions(?License $license = null): ArrayCollection
	{
		return $this->getRelationFinder('Versions')->keyedBy(function (DownloadVersion $e): string
		{
			return ($e->product_version . '_' . $e->product_version_type);
		})->fetch()->filter(function (DownloadVersion $version) use ($license): ?DownloadVersion
		{
			if ($version->product_version_type != ($license ? 'full' : 'demo'))
			{
				return null;
			}
			
			if (!$version->canDownload($license))
			{
				return null;
			}
			
			return $version;
		});
	}
	
	/**
	 * @param DownloadVersion $version
	 *
	 * @return bool
	 */
	public function forceDownloadChooser(DownloadVersion $version): bool
	{
		return (
			$this->download_type == 'dbtech_ecommerce_attach'
			&& $version->Attachments->count() > 1
		);
	}
	
	/**
	 * @param bool $includeSelf
	 *
	 * @return array
	 */
	public function getBreadcrumbs(bool $includeSelf = true): array
	{
		$breadcrumbs = $this->Product ? $this->Product->getBreadcrumbs() : [];
		if ($includeSelf && $this->exists())
		{
			$breadcrumbs[] = [
				'href' => $this->app()->router()->buildLink('dbtech-ecommerce/release', $this),
				'value' => $this->title
			];
		}
		
		return $breadcrumbs;
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canView(&$error = null): bool
	{
		$product = $this->Product;
		
		if (!$product || !$product->canView($error))
		{
			return false;
		}
		
		$visitor = \XF::visitor();
		
		if ($this->download_state == 'moderated')
		{
			if (
				(!$visitor->user_id || $visitor->user_id != $product->user_id)
				&& !$product->hasPermission('viewModerated')
			) {
				return false;
			}
		}
		elseif ($this->download_state == 'deleted')
		{
			if (!$product->hasPermission('viewDeleted'))
			{
				return false;
			}
		}
		elseif ($this->download_state == 'scheduled')
		{
			if (!$product->hasPermission('viewScheduled'))
			{
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @param License|null $license
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canDownload(?License $license = null, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$product = $this->Product;
		
		if (!$product || !$product->canDownload($error))
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_cannot_download_product');
			return false;
		}
		
		if (!$product->has_demo && (!$license || $license->product_id != $this->product_id))
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_product_has_no_demo');
			return false;
		}
		
		if ($license)
		{
			if ($this->release_date > $license->expiry_date && !$license->isLifetime())
			{
				return false;
			}
			
			if (!$license->isVisible())
			{
				return false;
			}
			
			if (
				$visitor->user_id != $license->user_id &&
				!$visitor->canDownloadAnyDbtechEcommerceLicense($error)
			) {
				return false;
			}
			
			if (!$license->hasValidLicenseFields())
			{
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canEdit(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;
		
		if (!$visitor->user_id || !$product)
		{
			return false;
		}
		
		if ($product->hasPermission('updateAny'))
		{
			return true;
		}
		
		return $product->canEdit($error);
	}
	
	/**
	 * @param null $error
	 *
	 * @return mixed|null
	 */
	public function canBookmarkContent(&$error = null): ?bool
	{
		return $this->isVisible();
	}
	
	/**
	 * @param string $type
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canDelete(string $type = 'soft', &$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;
		
		if (!$visitor->user_id || !$product)
		{
			return false;
		}
		
		if ($type != 'soft')
		{
			return $product->hasPermission('hardDeleteAny');
		}
		
		if ($product->hasPermission('deleteAny'))
		{
			return true;
		}
		
		return (
			$product->user_id == $visitor->user_id
			&& $product->hasPermission('updateOwn')
		);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canUndelete(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;
		
		if (!$visitor->user_id || !$product)
		{
			return false;
		}
		
		return $product->hasPermission('undelete');
	}
	
	/**
	 * @return bool
	 */
	public function canSendModeratorActionAlert(): bool
	{
		$product = $this->Product;
		
		return (
			$product
			&& $product->canSendModeratorActionAlert()
			&& $this->download_state == 'visible'
		);
	}
	
	/**
	 * @param null $error
	 * @param \XF\Entity\User|null $asUser
	 *
	 * @return bool
	 */
	public function canReport(&$error = null, ?\XF\Entity\User $asUser = null): bool
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canWarn(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;
		
		if ($this->warning_id
			|| !$product
			|| !$product->user_id
			|| !$visitor->user_id
			|| $product->user_id == $visitor->user_id
			|| !$product->hasPermission('warn')
		) {
			return false;
		}
		
		$user = $this->Product->User;
		return ($user && $user->isWarnable());
	}
	
	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canApproveUnapprove(&$error = null): bool
	{
		return $this->Product && $this->Product->canApproveUnapprove();
	}
	
	/**
	 * @param null $error
	 * @return bool
	 */
	public function canReact(&$error = null): bool
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id || !$this->Product)
		{
			return false;
		}
		
		if ($this->download_state != 'visible')
		{
			return false;
		}
		
		if ($this->Product->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}
		
		return $this->Product->hasPermission('react');
	}

	/**
	 * @return bool
	 */
	public function isVisible(): bool
	{
		return (
			$this->download_state == 'visible'
			&& $this->Product
			&& $this->Product->isVisible()
		);
	}
	
	/**
	 * @return bool
	 */
	public function isScheduled(): bool
	{
		return ($this->download_state == 'scheduled');
	}
	
	/**
	 * @return bool
	 */
	public function isLastUpdate(): bool
	{
		$product = $this->Product;
		if (!$product)
		{
			return false;
		}
		
		return ($this->download_id == $product->latest_version_id);
	}
	
	/**
	 * @return bool
	 */
	public function hasViewableDiscussion(): bool
	{
		if (!$this->discussion_thread_id)
		{
			return false;
		}
		
		$thread = $this->Discussion;
		if (!$thread)
		{
			return false;
		}
		
		return $thread->canView();
	}
	
	/**
	 * @return null
	 */
	public function getEditData()
	{
		$handler = $this->Handler;
		return $handler ? $handler->getEditData($this) : null;
	}
	
	/**
	 * @return null
	 */
	public function getDownloadData()
	{
		$handler = $this->Handler;
		return $handler ? $handler->getDownloadData($this) : null;
	}
	
	/**
	 * @return null|string
	 */
	public function getHandlerIdentifier(): ?string
	{
		return $this->app()->getContentTypeFieldValue($this->download_type, 'dbtech_ecommerce_download_handler_class');
	}
	
	/**
	 * @return \DBTech\eCommerce\Download\AbstractHandler|null
	 * @throws \Exception
	 */
	public function getHandler(): ?\DBTech\eCommerce\Download\AbstractHandler
	{
		return $this->getDownloadRepo()->getDownloadHandler($this->download_type);
	}

	/**
	 * @return mixed|null|string|string[]
	 * @throws \Exception
	 */
	public function getExpectedThreadTitle()
	{
		$template = '';
		$options = $this->app()->options();
		
		if ($this->download_state != 'visible' && $options->dbtechEcommerceContentDeleteThreadAction['update_title'])
		{
			$template = $options->dbtechEcommerceContentDeleteThreadAction['title_template'];
		}
		
		if (!$template)
		{
			$template = $options->dbtechEcommerceReleaseThreadTitle;
		}
		
		$threadTitle = strtr($template, [
			'{title}' => $this->getTitle(),
			'{category}' => $this->Product->Category->title,
			'{starting_price}' => $this->Product->getStartingPrice(null, false),
		]);
		return $this->app()->stringFormatter()->wholeWordTrim($threadTitle, 100);
	}
	
	/**
	 * @return string
	 */
	public function getReleaseAbstractPath(): string
	{
		return sprintf(
			'internal-data://dbtechEcommerce/releases/%d/',
			$this->download_id
		);
	}
	
	/**
	 * @return bool
	 */
	public function rebuildCounters(): bool
	{
		$this->rebuildDownloadCount();
		$this->rebuildFullDownloadCount();
		
		return true;
	}

	/**
	 * @return int
	 */
	public function rebuildDownloadCount(): int
	{
		$this->download_count = (int)$this->db()->fetchOne('
			SELECT COUNT(DISTINCT user_id)
			FROM xf_dbtech_ecommerce_product_download
			WHERE download_id = ?
		', $this->download_id);
		
		return $this->download_count;
	}
	
	/**
	 * @return int
	 */
	public function rebuildFullDownloadCount(): int
	{
		$this->full_download_count = (int)$this->db()->fetchOne('
			SELECT COUNT(download_log_id)
			FROM xf_dbtech_ecommerce_download_log
			WHERE download_id = ?
		', $this->download_id);
		
		return $this->full_download_count;
	}

	/**
	 * @param bool $canonical
	 * @param array $extraParams
	 * @param null $hash
	 *
	 * @return mixed|string
	 */
	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null): string
	{
		$route = $canonical ? 'canonical:dbtech-ecommerce/release' : 'dbtech-ecommerce/release';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}

	/**
	 * @return string|null
	 */
	public function getContentPublicRoute(): ?string
	{
		return 'dbtech-ecommerce/release';
	}

	/**
	 * @param string $context
	 *
	 * @return string|\XF\Phrase
	 */
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('dbtech_ecommerce_release_x', ['title' => $this->title]);
	}

	/**
	 * @param int $productId
	 * @return bool
	 */
	protected function verifyProduct(int &$productId): bool
	{
		if (!$productId)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_product_id'), 'product_id');
			return false;
		}

		$product = $this->_em->find('DBTech\eCommerce:Product', $productId);
		if (!$product)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_product_id'), 'product_id');
			return false;
		}

		return true;
	}
	
	/**
	 *
	 */
	protected function _preSave()
	{
		if ($this->release_date > \XF::$time && $this->isInsert())
		{
			$this->download_state = 'scheduled';
		}
		
		if ($this->isInsert() && !$this->user_id)
		{
			$this->user_id = \XF::visitor()->user_id;
		}
	}
	
	/**
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('download_state', 'visible');
		$approvalChange = $this->isStateChanged('download_state', 'moderated');
		$deletionChange = $this->isStateChanged('download_state', 'deleted');
		
		if ($this->isUpdate())
		{
			if ($deletionChange == 'leave' && $this->DeletionLog)
			{
				$this->DeletionLog->delete();
			}
			
			if ($approvalChange == 'leave' && $this->ApprovalQueue)
			{
				$this->ApprovalQueue->delete();
			}

			if ($this->isChanged('discussion_thread_id'))
			{
				if ($this->getExistingValue('discussion_thread_id'))
				{
					/** @var \XF\Entity\Thread $oldDiscussion */
					$oldDiscussion = $this->getExistingRelation('Discussion');
					if ($oldDiscussion && $oldDiscussion->discussion_type == 'dbtech_ecommerce_download')
					{
						// this will set it back to the forum default type
						$oldDiscussion->discussion_type = '';
						$oldDiscussion->save(false, false);
					}
				}

				if (
					$this->discussion_thread_id
					&& $this->Discussion
					&& $this->Discussion->discussion_type === \XF\ThreadType\AbstractHandler::BASIC_THREAD_TYPE
				) {
					$this->Discussion->discussion_type = 'dbtech_ecommerce_download';
					$this->Discussion->save(false, false);
				}
			}
		}
		else
		{
			// insert
			if ($this->download_state == 'visible')
			{
				$this->downloadInsertedVisible();
			}
		}
		
		if ($this->discussion_thread_id)
		{
			$newThreadTitle = $this->getExpectedThreadTitle();
			if (
				$this->Discussion
				&& $this->Discussion->discussion_type == 'dbtech_ecommerce_download'
				&& $newThreadTitle != $this->Discussion->title
			) {
				$this->Discussion->title = $newThreadTitle;
				$this->Discussion->saveIfChanged($saved, false, false);
			}
		}
		
		if ($approvalChange == 'enter')
		{
			/** @var \XF\Entity\ApprovalQueue $approvalQueue */
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->release_date;
			$approvalQueue->save();
		}
		elseif ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}
		
		$this->updateProductRecord();
		
		if ($this->isInsert() && $this->isScheduled())
		{
			$this->app()->jobManager()->enqueueLater(
				'dbtechEcommerceDownloadSchedule' . $this->download_id,
				$this->release_date,
				'DBTech\eCommerce:DownloadSchedule',
				[
					'download_id' => $this->download_id
				]
			);
		}
		
		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('dbtech_ecommerce_download', $this);
		}
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function updateProductRecord()
	{
		if (!$this->Product || !$this->Product->exists())
		{
			// inserting a product, don't try to write to it
			return;
		}
		
		$visibilityChange = $this->isStateChanged('download_state', 'visible');
		if ($visibilityChange == 'enter' && $this->Product)
		{
			$this->Product->releaseAdded($this);
			$this->Product->save();
		}
		elseif ($visibilityChange == 'leave' && $this->Product)
		{
			$this->Product->releaseRemoved($this);
			$this->Product->save();
		}
	}

	/**
	 * @throws \Exception
	 */
	protected function downloadMadeVisible()
	{
		if ($this->discussion_thread_id && $this->Discussion && $this->Discussion->discussion_type == 'dbtech_ecommerce_download')
		{
			$thread = $this->Discussion;
			
			switch ($this->app()->options()->dbtechEcommerceContentDeleteThreadAction['action'])
			{
				case 'delete':
					$thread->discussion_state = 'visible';
					break;
				
				case 'close':
					$thread->discussion_open = true;
					break;
			}
			
			$thread->title = $this->getExpectedThreadTitle();
			$thread->saveIfChanged($saved, false, false);
		}
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->recalculateReactionIsCounted('dbtech_ecommerce_download', $this->download_id);
	}

	/**
	 *
	 */
	protected function downloadInsertedVisible()
	{
	}

	/**
	 * @param bool $hardDelete
	 *
	 * @throws \Exception
	 */
	protected function downloadHidden(bool $hardDelete = false)
	{
		if ($this->discussion_thread_id && $this->Discussion && $this->Discussion->discussion_type == 'dbtech_ecommerce_download')
		{
			$thread = $this->Discussion;
			
			switch ($this->app()->options()->dbtechEcommerceContentDeleteThreadAction['action'])
			{
				case 'delete':
					$thread->discussion_state = 'deleted';
					break;
				
				case 'close':
					$thread->discussion_open = false;
					break;
			}
			
			$thread->title = $this->getExpectedThreadTitle();
			$thread->saveIfChanged($saved, false, false);
		}
		
		if (!$hardDelete)
		{
			// on hard delete the reactions will be removed which will do this
			/** @var \XF\Repository\Reaction $reactionRepo */
			$reactionRepo = $this->repository('XF:Reaction');
			$reactionRepo->fastUpdateReactionIsCounted('dbtech_ecommerce_download', $this->download_id, false);
		}
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('dbtech_ecommerce_download', $this->download_id);
	}
	
	/**
	 *
	 */
	protected function submitHamData()
	{
		/** @var \XF\Spam\ContentChecker $submitter */
		$submitter = $this->app()->container('spam.contentHamSubmitter');
		$submitter->submitHam('dbtech_ecommerce_download', $this->download_id);
	}
	
	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _postDelete()
	{
		if ($this->download_state == 'visible')
		{
			$this->downloadHidden(true);
		}
		
		if ($this->Product && $this->download_state == 'visible')
		{
			$this->Product->releaseRemoved($this);
			$this->Product->save();
		}
		
		if ($this->download_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->download_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('dbtech_ecommerce_download', $this, 'delete_hard');
		}
		
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('dbtech_ecommerce_download', $this->download_id);
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('dbtech_ecommerce_download', $this->download_id);
	}
	
	/**
	 * @param string $reason
	 * @param \XF\Entity\User|null $byUser
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function softDelete(string $reason = '', \XF\Entity\User $byUser = null): bool
	{
		$byUser = $byUser ?: \XF::visitor();
		
		if ($this->download_state == 'deleted')
		{
			return false;
		}
		
		$this->download_state = 'deleted';
		
		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;
		
		$this->save();
		
		return true;
	}
	
	/**
	 * @param \XF\Entity\User|null $byUser
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function unDelete(?\XF\Entity\User $byUser = null): bool
	{
		$byUser = $byUser ?: \XF::visitor();
		
		if ($this->download_state == 'visible')
		{
			return false;
		}
		
		$this->download_state = 'visible';
		$this->save();
		
		return true;
	}
	
	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-see self::addReactionStateToApiResult()
	 *
	 * @api-type Download
	 *
	 * @api-out Product $product <cond> If the "with_product" option is passed to the API Result generation.
	 * @api-out User $user <cond> If the "with_user" option is passed to the API Result generation.
	 * @api-out bool $can_edit
	 * @api-out bool $can_soft_delete
	 * @api-out bool $can_hard_delete
	 * @api-out bool $can_react
	 * @api-out bool $can_download
	 */
	protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result,
		$verbosity = self::VERBOSITY_NORMAL,
		array $options = []
	) {
		if (empty($options['licenses']))
		{
			if ($verbosity > self::VERBOSITY_NORMAL && \XF::visitor()->user_id)
			{
				/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
				$licenseRepo = $this->repository('DBTech\eCommerce:License');
				
				$licenseFinder = $licenseRepo->findLicensesByUser(
					\XF::visitor()->user_id,
					null,
					['allowOwnPending' => false]
				);
				$licenseFinder->where('product_id', $this->product_id);
				
				/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
				$licenses = $licenseFinder->fetch();
				$licenses = $licenseRepo->filterLicensesForApiResponse($licenses);
			}
			else
			{
				$licenses = new ArrayCollection([]);
			}
		}
		else
		{
			$licenses = $options['licenses'];
		}
		
		if (!empty($options['with_product']))
		{
			$result->includeRelation('Product', $verbosity, [
				'licenses' => $licenses
			]);
		}
		
		if (!empty($options['with_user']))
		{
			$result->includeRelation('User', $verbosity);
		}
		
		if (!empty($options['with_versions']))
		{
			$result->includeRelation('Versions', $verbosity, [
				'licenses' => $licenses
			]);
		}
		
		$this->addReactionStateToApiResult($result);
		
		$result->can_edit = $this->canEdit();
		$result->can_soft_delete = $this->canDelete();
		$result->can_hard_delete = $this->canDelete('hard');
		$result->can_react = $this->canReact();
		
		$result->can_download = false;
		
		if ($licenses->count())
		{
			/** @var \DBTech\eCommerce\Entity\License $license */
			foreach ($licenses as $license)
			{
				if ($this->canDownload($license))
				{
					$result->can_download = true;
					break;
				}
			}
		}
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_download';
		$structure->shortName = 'DBTech\eCommerce:Download';
		$structure->contentType = 'dbtech_ecommerce_download';
		$structure->primaryKey = 'download_id';
		$structure->columns = [
			'download_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'product_id' => ['type' => self::UINT, 'required' => true, 'writeOnce' => true, 'default' => 0, 'api' => true,
				'verify' => 'verifyProduct'
			],
			'user_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
			'download_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'scheduled', 'moderated', 'deleted'],
				'api' => true
			],
			'version_string' => ['type' => self::STR, 'maxLength' => 25, 'required' => true, 'api' => true],
			'release_date' => ['type' => self::UINT, 'default' => \XF::$time, 'api' => true],
			'change_log' => ['type' => self::STR, 'required' => true, 'api' => true],
			'has_new_features' => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'has_changed_features' => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'has_bug_fixes' => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'is_unstable' => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'release_notes' => ['type' => self::STR, 'api' => true],
			'discussion_thread_id' => ['type' => self::UINT, 'default' => 0],
			'download_type' => ['type' => self::STR, 'maxLength' => 25, 'default' => 'dbtech_ecommerce_attach', 'api' => true],
			'download_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'full_download_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
			'attach_count' => ['type' => self::UINT, 'max' => 65535, 'forced' => true, 'default' => 0, 'api' => true],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'api' => true],
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
		];
		$structure->behaviors = [
			'XF:Reactable' => ['stateField' => 'download_state'],
			'XF:Indexable' => [
				'checkForUpdates' => ['change_log', 'product_id', 'release_date', 'download_state']
			],
			'XF:NewsFeedPublishable' => [
				'userIdField' => function (Download $download): ?int
				{
					return $download->User->user_id;
				},
				'usernameField' => function (Download $download): string
				{
					return $download->User->username;
				},
				'dateField' => 'release_date'
			]
		];
		$structure->getters = [
			'title' => true,
			'product_title' => true,
			'Content' => true,
			'EditData' => true,
			'DownloadData' => true,
			'Handler' => true,
		];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Discussion' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => [
					['thread_id', '=', '$discussion_thread_id']
				],
				'primary' => true
			],
			'Attachments' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_download'],
					['content_id', '=', '$download_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],
			'Versions' => [
				'entity' => 'DBTech\eCommerce:DownloadVersion',
				'type' => self::TO_MANY,
				'conditions' => 'download_id',
				//'key' => ['product_version', 'product_version_type']
				//'key' => 'product_version',
				'cascadeDelete' => true
			],
			'FullVersions' => [
				'entity' => 'DBTech\eCommerce:DownloadVersion',
				'type' => self::TO_MANY,
				'conditions' => [
					['product_version_type', '=', 'full'],
					['download_id', '=', '$download_id']
				],
				'key' => 'product_version',
			],
			'Downloads' => [
				'entity' => 'DBTech\eCommerce:ProductDownload',
				'type' => self::TO_MANY,
				'conditions' => 'download_id',
				'key' => 'user_id'
			],
			'DownloadLog' => [
				'entity' => 'DBTech\eCommerce:DownloadLog',
				'type' => self::TO_MANY,
				'conditions' => 'download_id',
				'cascadeDelete' => true
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_download'],
					['content_id', '=', '$download_id']
				],
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_download'],
					['content_id', '=', '$download_id']
				],
				'primary' => true
			]
		];
		
		$structure->withAliases = [
			'full' => [
				'Product.full|category',
				function (): ?string
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return 'Reactions|' . $userId;
					}
					
					return null;
				}
			],
			'api' => [
				'full'
			]
		];
		
		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = ['Product'];
		
		static::addBookmarkableStructureElements($structure);
		static::addReactableStructureElements($structure);

		return $structure;
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Download
	 */
	protected function getDownloadRepo(): \DBTech\eCommerce\Repository\Download
	{
		return $this->repository('DBTech\eCommerce:Download');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Product
	 */
	protected function getProductRepo(): \DBTech\eCommerce\Repository\Product
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
}