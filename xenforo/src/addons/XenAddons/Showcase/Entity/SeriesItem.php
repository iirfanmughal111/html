<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\BookmarkTrait;
use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\Arr;

use function gmdate;

/**
 * COLUMNS
 * @property int|null $series_id
 * @property int $user_id
 * @property string $username
 * @property string $title
 * @property string $og_title
 * @property string $meta_title
 * @property string $description
 * @property string $meta_description
 * @property string $series_state
 * @property string $message
 * @property int $create_date
 * @property int $edit_date
 * @property int $last_feature_date
 * @property int $item_count
 * @property int $last_part_date
 * @property int $last_part_id
 * @property int $last_part_item_id
 * @property int $community_series
 * @property int $icon_date
 * @property array $tags
 * @property int $view_count
 * @property int $watch_count
 * @property int $attach_count
 * @property int $has_poll
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 * @property int $warning_id
 * @property string $warning_message
 * @property int $last_edit_date
 * @property int $last_edit_user_id
 * @property int $edit_count 
 * @property int $ip_id
 * @property array|null $embed_metadata
 * 
 * GETTERS
 * @property string $series_title
 * @property int $featured
 * @property mixed $reactions
 * @property mixed $reaction_users
 * 
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \XF\Entity\Poll $Poll
 * @property \XenAddons\Showcase\Entity\Item $LastItem
 * @property \XenAddons\Showcase\Entity\SeriesFeature $Featured
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\SeriesWatch[] $Watch
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\BookmarkItem[] $Bookmarks
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\TagContent[] $Tags
 */
class SeriesItem extends Entity implements \XF\BbCode\RenderableContentInterface, \XF\Entity\LinkableInterface
{
	use ReactionTrait, BookmarkTrait;
	
	public function canView(&$error = null)
	{
		$visitor = \XF::visitor();

		if (!$this->hasPermission('view')) // This checks if the viewing user can view AMS
		{
			return false;
		}
		
		if (!$this->hasPermission('viewSeries')) // This checks if the viewing user can view a series
		{
			return false;
		}

		if ($this->series_state == 'moderated')
		{
			if (
				!$this->hasPermission('viewModeratedSeries') 
				&& (!$visitor->user_id || $visitor->user_id != $this->user_id)
			)
			{
				return false;
			}
		}
		else if ($this->series_state == 'deleted')
		{
			if (!$this->hasPermission('viewDeletedSeries')) 
			{
				return false;
			}
		}
		
		return true;
	}
	
	public function canViewSeriesAttachments()
	{
		return $this->hasPermission('viewSeriesAttach'); 
	}
	
	public function canUploadAndManageSeriesAttachments(&$error = null)
	{
		return $this->hasPermission('uploadSeriesAttach');
	}
	
	public function canUploadSeriesVideos()
	{
		return $this->hasPermission('uploadSeriesVideo');
	}
	
	public function canAddItemToSeries(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		if ($this->canAddItemToAnySeries())
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id)
		{
			return true;
		}
		
		return $this->canAddItemToCommunitySeries();
	}
	
	public function canAddItemToCommunitySeries(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		if ($this->canAddItemToAnySeries())
		{
			return true;
		}
		
		return (
			$this->isCommunitySeries()
			&& $this->hasPermission('addToCommunitySeries')
		);		
	}
	
	public function canAddItemToAnySeries(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		 return $this->hasPermission('editAnySeries');
	}	
	
	public function canCreatePoll(&$error = null)
	{
		if ($this->has_poll)
		{
			return false;
		}
	
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->hasPermission('editAnySeries')) 
		{
			return true;
		}
	
		if ($this->user_id == $visitor->user_id && $this->hasPermission('editOwnSeries')) 
		{
			$editLimit = $visitor->hasShowcaseSeriesPermission('editOwnSeriesTimeLimit'); // TODO create separate permission
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phraseDeferred('message_edit_time_limit_expired', ['minutes' => $editLimit]);
				return false;
			}
	
			return true;
		}
	
		return false;
	}	
		
	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->hasPermission('editAnySeries'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id && $this->hasPermission('editOwnSeries'))
		{
			$editLimit = $this->hasPermission('editOwnSeriesTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_edit_this_series_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return false;
	}
	
	public function canViewHistory(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if (!$this->app()->options()->editHistory['enabled'])
		{
			return false;
		}
	
		if ($this->hasPermission('editAnySeries')) 
		{
			return true;
		}
	
		return false;
	}

	public function canEditIcon(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->hasPermission('editAnySeries'))
		{
			return true;
		}
	
		if ($this->user_id == $visitor->user_id)
		{
			if ($this->hasPermission('editOwnSeries'))
			{
				return true;
			}
	
			if (!$this->icon_date && $this->create_date > \XF::$time - 3 * 3600)
			{
				// allow an icon to be set shortly after series creation, even if not editable since you can't
				// specify an icon during creation
				return true;
			}
		}
	
		return false;
	}
	
	public function canBookmarkContent(&$error = null)
	{
		return $this->canView();
	}

	public function canDelete($type = 'soft', &$error = null)
	{
		$visitor = \XF::visitor();

		if ($type != 'soft' && !$this->hasPermission('hardDeleteAnySeries'))
		{
			return false;
		}
		
		if ($this->hasPermission('deleteAnySeries'))
		{
			return true;
		}

		if ($this->user_id == $visitor->user_id && $this->hasPermission('deleteOwnSeries'))
		{
			$editLimit = $this->hasPermission('editOwnSeriesTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_delete_this_series_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return false;	
	}
	
	public function canUndelete(&$error = null)
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return false;
		}
	
		return $this->hasPermission('undeleteSeries');
	}
	
	public function canApproveUnapprove(&$error = null)
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return false;
		}
	
		return $this->hasPermission('approveUnapproveSeries');
	}
	
	public function canReassign(&$error = null)
	{
		$visitor = \XF::visitor();
	
		return (
			$visitor->user_id
			&& $this->hasPermission('editAnySeries')  
		);
	}
	
	public function canWarn(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if ($this->warning_id
			|| !$this->user_id
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$this->hasPermission('warnSeries') 
		)
		{
			return false;
		}
	
		return ($this->User && $this->User->isWarnable());
	}
	
	public function canReact(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->series_state != 'visible')
		{
			return false;
		}
	
		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}
	
		return $this->hasPermission('reactSeries');  
	}
	
	public function canReport(&$error = null, \XF\Entity\User $asUser = null)
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}
	
	public function canWatch(&$error = null)
	{
		return (\XF::visitor()->user_id ? true : false);
	}
	
	public function canEditTags(&$error = null)
	{
		if (!$this->app()->options()->enableTagging)
		{
			return false;
		}
	
		$visitor = \XF::visitor();
	
		if ($this->user_id == $visitor->user_id)
		{
			if ($this->hasPermission('tagOwnSeries'))
			{
				return true;
			}
		}
	
		if ($this->hasPermission('tagAnySeries')
			|| $this->hasPermission('manageAnySeriesTag')
		)
		{
			return true;
		}
	
		return false;
	}
	
	public function canUseInlineModeration(&$error = null)
	{
		$visitor = \XF::visitor();
		return ($visitor->user_id && $this->hasPermission('inlineModSeries'));
	}
	
	public function canFeatureUnfeature(&$error = null)
	{
		$visitor = \XF::visitor();
	
		return (
			$visitor->user_id
			&& $this->isVisible()
			&& $this->hasPermission('featureUnfeatureSeries') 
		);
	}
	
	public function canViewModeratorLogs(&$error = null)
	{
		$visitor = \XF::visitor();
		return $visitor->user_id && ($this->hasPermission('editAnySeries') || $this->hasPermission('deleteAnySeries'));
	}
	
	public function canSendModeratorActionAlert()
	{
		$visitor = \XF::visitor();
	
		return (
			$visitor->user_id
			&& $visitor->user_id != $this->user_id
		);
	}
	
	public function hasPermission($permission)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor->hasShowcaseSeriesPermission($permission);
	}
	
	public function getMaxAllowedAttachmentsPerSeries()
	{
		return $this->hasPermission('maxAttachPerSeries');
	}
	
	public function isVisible()
	{
		return ($this->series_state == 'visible');
	}
	
	public function isSearchEngineIndexable()
	{
		// TODO add more configurable criteria (via AMS Options)
		
		if ($this->item_count < 1) // TODO make this an option
		{
			return false;
		}
		
		return true;
	}
	
	public function isIgnored()
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}
	
	public function isFeatured()
	{
		return $this->featured ? true : false;
	}
	
	public function isCommunitySeries()
	{
		return $this->community_series ? true : false;
	}
	
	public function isAttachmentEmbedded($attachmentId)
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
	
	public function hasImageAttachments($series = false)
	{
		if (!$this->attach_count)
		{
			return false;
		}
	
		if ($series && $series['Attachments'])
		{
			$attachments = $series['Attachments'];
		}
		else
		{
			$attachments = $this->Attachments;
		}
	
		foreach ($attachments AS $attachment)
		{
			if ($attachment['thumbnail_url'])
			{
				return true;
			}
		}
	
		return false;
	}

	public function getBreadcrumbs($includeSelf = true)
	{
		$breadcrumbs[] = [
			'href' => $this->app()->router()->buildLink('showcase/series'),
			'value' => \XF::phrase('xa_sc_series')
		];
	
		if ($includeSelf)
		{
			$breadcrumbs[] = [
				'href' => $this->app()->router()->buildLink('showcase/series', $this),
				'value' => $this->title
			];
		}
	
		return $breadcrumbs;
	}
	
	protected function getLdSnippet(string $message, int $length = null): string
	{
		if ($length === null)
		{
			$length = 250;
		}
	
		return \XF::app()->stringFormatter()->snippetString($message, $length, ['stripBbCode' => true]);
	}
	
	public function getLdStructuredData(int $page = 1, array $extraData = []): array
	{
		$router = $this->app()->router('public');
		$templater = \XF::app()->templater();
	
		$output = [
			'@context'            => 'https://schema.org',
			'@type'               => 'CreativeWorkSeries',
			'@id'                 => $router->buildLink('canonical:showcase/series', $this),
			'name'                => $this->title,
			'headline'            => $this->meta_title ?: $this->title,
			'alternativeHeadline' => $this->og_title ?: $this->title,
			'description'         => $this->getLdSnippet($this->meta_description ?: $this->description ?: $this->message),
			"keywords"            => implode(', ', array_column($this->tags, 'tag')),
			'dateCreated'         => gmdate('c', $this->create_date),
			'dateModified'        => gmdate('c', $this->last_part_date),
			'author'              => [
				'@type' => 'Person',
				'name'  => $this->User->username ?: $this->username,
			]
		];
	
		if ($this->icon_date && $thumbnailUrl = $this->getIconUrl(null, true))
		{
			$output['thumbnailUrl'] = $thumbnailUrl;
		}
	
		$output['interactionStatistic'] = [
			[
				'@type' => 'InteractionCounter',
				'interactionType' => 'https://schema.org/LikeAction',
				'userInteractionCount' => strval($this->reaction_score)
			]
		];
	
		return Arr::filterNull($output, true);
	}	
	
	public function getAbstractedIconPath($sizeCode = null)
	{
		$seriesId = $this->series_id;
	
		return sprintf('data://sc_series_icons/%d/%d.jpg',
			floor($seriesId / 1000),
			$seriesId
		);
	}
	
	public function getIconUrl($sizeCode = null, $canonical = false)
	{
		$app = $this->app();
	
		if ($this->icon_date)
		{
			$group = floor($this->series_id / 1000);
			return $app->applyExternalDataUrl(
				"sc_series_icons/{$group}/{$this->series_id}.jpg?{$this->icon_date}",
				$canonical
			);
		}
		else
		{
			return null;
		}
	}	
	
	public function getNewSeriesState(SeriesItem $series = null)
	{
		$visitor = \XF::visitor();
	
		if ($visitor->user_id && $this->hasPermission('approveUnapproveSeries'))
		{
			return 'visible';
		}
	
		if (!$this->hasPermission('addSeriesWithoutApproval'))
		{
			return 'moderated';
		}
	
		return 'visible';
	}
	
	public function getNewSeriesPart()
	{
		$seriesPart = $this->_em->create('XenAddons\Showcase:SeriesPart');
		$seriesPart->series_id = $this->series_id;
	
		return $seriesPart;
	}
	
	public function partAdded(SeriesPart $seriesPart)
	{
		$this->item_count++;
		if ($seriesPart)
		{
			$this->last_part_date = $seriesPart->create_date;
			$this->last_part_id = $seriesPart->series_part_id;
			$this->last_part_item_id = $seriesPart->item_id;
		}
		else 
		{
			$this->rebuildLastPart();
		}	
	}

	public function partUpdated(SeriesPart $seriesPart)
	{
		$this->rebuildLastPart();
	}
	
	public function partRemoved(SeriesPart $seriesPart)
	{
		$this->item_count--;
		
		$this->rebuildLastPart();
	}
	
	/**
	 * @return array
	 */
	public function getSeriesPartIds()
	{
		return $this->db()->fetchAllColumn("
			SELECT series_part_id
			FROM xf_xa_sc_series_part
			WHERE series_id = ?
			ORDER BY create_date
		", $this->series_id);
	}
	
	public function rebuildCounters()
	{
		$this->rebuildLastPart();
		$this->rebuildPartCount();
	
		return true;
	}
	
	public function rebuildLastPart()
	{
		$part = $this->db()->fetchRow("
			SELECT *
			FROM xf_xa_sc_series_part
			WHERE series_id = ?
			ORDER BY create_date DESC
			LIMIT 1
		", $this->series_id);
		if ($part)
		{
			$this->last_part_date = $part['create_date'];
			$this->last_part_id = $part['series_part_id'];
			$this->last_part_item_id = $part['item_id'];
		}
		else
		{
			$this->last_part_date = 0;
			$this->last_part_id = 0;
			$this->last_part_item_id = 0;
		}
	}
	
	public function rebuildPartCount()
	{
		$this->item_count = $this->db()->fetchOne("
			SELECT COUNT(*)
				FROM xf_xa_sc_series_part
				WHERE series_id = ?
		", $this->series_id);
	
		return $this->item_count;
	}
	
	public function getBbCodeRenderOptions($context, $type)
	{
		return [
			'entity' => $this,
			'user' => $this->User,
			'attachments' => $this->attach_count ? $this->Attachments : [],
			'viewAttachments' => $this->canViewSeriesAttachments()
		];
	}

	protected function _preSave()
	{

	}

	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('series_state', 'visible');
		$approvalChange = $this->isStateChanged('series_state', 'moderated');
		$deletionChange = $this->isStateChanged('series_state', 'deleted');
		
		if ($this->isUpdate())
		{		
			if ($visibilityChange == 'enter')
			{
				$this->seriesMadeVisible();
			
				if ($approvalChange)
				{
					$this->submitHamData();
				}
			}
			else if ($visibilityChange == 'leave')
			{
				$this->seriesHidden();
			}
			
			if ($this->isChanged('user_id'))
			{
				$this->seriesReassigned();
			}
			
			if ($deletionChange == 'leave' && $this->DeletionLog)
			{
				$this->DeletionLog->delete();
			}
			
			if ($approvalChange == 'leave' && $this->ApprovalQueue)
			{
				$this->ApprovalQueue->delete();
			}
		}
		else 
		{
			// insert
			if ($this->series_state == 'visible')
			{
				$this->seriesInsertedVisible();
			}
		}
		
		if ($approvalChange == 'enter')
		{
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->create_date;
			$approvalQueue->save();
		}
		else if ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}
		
		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('sc_series', $this);
		}
		
		$this->_postSaveBookmarks();
	}
	
	protected function seriesMadeVisible()
	{
		$this->adjustUserSeriesCountIfNeeded(1);
	
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->recalculateReactionIsCounted('sc_series', $this->series_id);
	
		// TODO alert author that their series was approved?
	}
	
	protected function seriesHidden($hardDelete = false)
	{
		$this->adjustUserSeriesCountIfNeeded(-1);
	
		if (!$hardDelete)
		{
			// on hard delete the reactions will be removed which will do this
			/** @var \XF\Repository\Reaction $reactionRepo */
			$reactionRepo = $this->repository('XF:Reaction');
			$reactionRepo->recalculateReactionIsCounted('sc_series', $this->series_id, false);
		}
	
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('sc_series', $this->series_id);
		$alertRepo->fastDeleteAlertsForContent('sc_series_part', $this->series_part_ids); 
	}	
	
	protected function seriesInsertedVisible()
	{
		$this->adjustUserSeriesCountIfNeeded(1);
	}
	
	protected function submitHamData()
	{
		/** @var \XF\Spam\ContentChecker $submitter */
		$submitter = $this->app()->container('spam.contentHamSubmitter');
		$submitter->submitHam('sc_series', $this->series_id);
	}
	
	protected function seriesReassigned()
	{
		$this->adjustUserSeriesCountIfNeeded(-1, $this->getExistingValue('user_id'));
		$this->adjustUserSeriesCountIfNeeded(1);
	}
	
	protected function adjustUserSeriesCountIfNeeded($amount, $userId = null)
	{
		if ($userId === null)
		{
			$userId = $this->user_id;
		}
	
		if ($userId)
		{
			$this->db()->query("
				UPDATE xf_user
				SET xa_sc_series_count = GREATEST(0, xa_sc_series_count + ?)
				WHERE user_id = ?
			", [$amount, $userId]);
		}
	}

	protected function _postDelete()
	{
		$db = $this->db();
		
		if ($this->series_state == 'visible')
		{
			$this->seriesHidden(true);
		}
		
		if ($this->series_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->series_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		if ($this->has_poll && $this->Poll)
		{
			$this->Poll->delete();
		}		
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_series', $this, 'delete_hard');
		}
		
		$db->delete('xf_approval_queue', 'content_id = ? AND content_type = ?', [$this->series_id, 'sc_series']);
		$db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->series_id, 'sc_series']);
		$db->delete('xf_edit_history', 'content_id = ? AND content_type = ?', [$this->series_id, 'sc_series']);

		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_series', $this->series_id);
		
		// remove all series part records (which should remove the item association!) 
		$seriesParts = $this->repository('XenAddons\Showcase:SeriesPart')->findPartsInSeriesDeleteSeries($this)->fetch();
		foreach ($seriesParts AS $seriesPart)
		{
			$seriesPart->delete();
		}
		
		/** @var \XenAddons\Showcase\Service\Series\Icon $iconService */
		$iconService = $this->app()->service('XenAddons\Showcase:Series\Icon', $this);
		$iconService->deleteIconForSeriesDelete();
		
		$this->_postDeleteBookmarks();
	}
	
	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();
	
		if ($this->series_state == 'deleted')
		{
			return false;
		}
	
		$this->series_state = 'deleted';
	
		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;
	
		$this->save();
	
		return true;
	}
	
	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
	{
		$route = ($canonical ? 'canonical:' : '') . 'showcase/series';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}
	
	public function getContentPublicRoute()
	{
		return 'showcase/series';
	}
	
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('xa_sc_series_x', [
			'title' => $this->title
		]);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_series';
		$structure->shortName = 'XenAddons\Showcase:SeriesItem';
		$structure->primaryKey = 'series_id';
		$structure->contentType = 'sc_series';
		$structure->columns = [
			'series_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'username' => ['type' => self::STR, 'maxLength' => 50,
				'required' => 'please_enter_valid_name'
			],
			'title' => ['type' => self::STR, 'maxLength' => 150,
				'required' => 'please_enter_valid_title',
				'censor' => true
			],
			'og_title' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'meta_title' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'series_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted']
			],
			'message' => ['type' => self::STR,
				'required' => 'please_enter_valid_message'
			],
			'description' => ['type' => self::STR, 'maxLength' => 256,
				'default' => '',
				'censor' => true
			],
			'meta_description' => ['type' => self::STR, 'maxLength' => 320,
				'default' => '',
				'censor' => true
			],
			'create_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'edit_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'last_feature_date' => ['type' => self::UINT, 'default' => 0],
			'item_count' => ['type' => self::UINT, 'default' => 0],
			'community_series' => ['type' => self::UINT, 'default' => 0],
			'last_part_date' => ['type' => self::UINT, 'default' => 0],
			'last_part_id' => ['type' => self::UINT, 'default' => 0],
			'last_part_item_id' => ['type' => self::UINT, 'default' => 0],
			'icon_date' => ['type' => self::UINT, 'default' => 0],
			'tags' => ['type' => self::JSON_ARRAY, 'default' => []],
			'view_count' => ['type' => self::UINT, 'default' => 0],
			'watch_count' => ['type' => self::UINT, 'default' => 0],
			'attach_count' => ['type' => self::UINT, 'default' => 0],
			'has_poll' => ['type' => self::BOOL, 'default' => false],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
			'edit_count' => ['type' => self::UINT, 'default' => 0],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null],
		];
		$structure->getters = [
			'series_part_ids' => true,
		];
		$structure->behaviors = [
			'XF:Reactable' => ['stateField' => 'series_state'],
			'XF:Taggable' => ['stateField' => 'series_state'],
			'XF:Indexable' => [
				'checkForUpdates' => ['title', 'message', 'description', 'user_id', 'tags']
			],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField' => 'create_date'
			]
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'LastItem' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => [
					['item_id', '=', '$last_part_item_id']
				],
				'primary' => true
			],
			'Attachments' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'sc_series'],
					['content_id', '=', '$series_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],
			'Featured' => [
				'entity' => 'XenAddons\Showcase:SeriesFeature',
				'type' => self::TO_ONE,
				'conditions' => 'series_id',
				'primary' => true
			],
			'Poll' => [
				'entity' => 'XF:Poll',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_series'],
					['content_id', '=', '$series_id']
				]
			],
			'Watch' => [
				'entity' => 'XenAddons\Showcase:SeriesWatch',
				'type' => self::TO_MANY,
				'conditions' => 'series_id',
				'key' => 'user_id'
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_series'],
					['content_id', '=', '$series_id']
				],
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_series'],
					['content_id', '=', '$series_id']
				],
				'primary' => true
			],
			'Tags' => [
				'entity' => 'XF:TagContent',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'sc_series'],
					['content_id', '=', '$series_id']
				],
				'key' => 'tag_id'
			]
		];
		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = ['User'];

		$structure->withAliases = [
			'full' => [
				'User',
				'LastItem', 
				'Featured',
				function()
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return ['Watch|' . $userId];
					}
				
					return null;
				}
			]
		];	
				
		static::addReactableStructureElements($structure);
		static::addBookmarkableStructureElements($structure);
		
		return $structure;
	}
}