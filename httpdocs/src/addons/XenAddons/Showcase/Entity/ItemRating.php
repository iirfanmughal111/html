<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\ReactionTrait;
use XF\Entity\ContentVoteTrait;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $rating_id
 * @property int $item_id
 * @property int $user_id
 * @property string $username
 * @property int $rating
 * @property string $title
 * @property int $rating_date
 * @property string $rating_state
 * @property bool $is_review
 * @property string $pros
 * @property string $cons
 * @property string $message
 * @property array $custom_fields_
 * @property int $warning_id
 * @property string $warning_message
 * @property int $ip_id
 * @property bool $is_anonymous
 * @property int $attach_count
 * @property int $reply_count
 * @property int $first_reply_date
 * @property int $last_reply_date
 * @property array $latest_reply_ids
 * @property array|null $embed_metadata
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 * @property int $vote_score
 * @property int $vote_count
 * 
 * GETTERS
 * @property Item $Content
 * @property string $item_title
 * @property array $reply_ids
 * @property ArrayCollection|null $LatestReplies
 * @property \XF\CustomField\Set $custom_fields
 * @property mixed $reactions
 * @property mixed $reaction_users
 * @property mixed $vote_score_short
 * 
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Entity\User $User
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ContentVote[] $ContentVotes
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemRatingReply[] $Replies
 */
class ItemRating extends Entity implements \XF\BbCode\RenderableContentInterface, \XF\Entity\LinkableInterface
{
	use ReactionTrait, ContentVoteTrait;
	
	public function canView(&$error = null)
	{
		$visitor = \XF::visitor();
		
		$item = $this->Item;

		if ($this->rating_state == 'moderated')
		{
			if (
				!$item->hasPermission('viewModeratedReviews')
				&& (!$visitor->user_id || $visitor->user_id != $this->user_id)
			)
			{
				return false;
			}
		}
		else if ($this->rating_state == 'deleted')
		{
			if (!$item->hasPermission('viewDeleted'))
			{
				return false;
			}
		}

		return ($item ? $item->canView($error) && $item->canViewReviews($error) : false);
	}
	
	public function canViewReviewImages()
	{
		$item = $this->Item;
		
		return $item->hasPermission('viewReviewAttach');
	}
	
	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		$item = $this->Item;

		if ($item->hasPermission('editAnyReview'))
		{
			return true;
		}

		if ($this->user_id == $visitor->user_id && $item->hasPermission('editReview'))
		{
			$editLimit = $item->hasPermission('editOwnReviewTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->rating_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_edit_this_review_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}

			return true;
		}

		return false;
	}	
	
	public function canEditSilently(&$error = null)
	{
		$item = $this->Item;
		$visitor = \XF::visitor();
		if (!$visitor->user_id || !$item)
		{
			return false;
		}
	
		return $item->hasPermission('editAnyReview');
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
	
		return $this->Item->hasPermission('editAnyReview');
	}	

	public function canReassign(&$error = null)
	{
		$visitor = \XF::visitor();
	
		return (
			$visitor->user_id
			&& $this->Item->hasPermission('reassignReview')
		);
	}
	
	public function canChangeDate(&$error = null)
	{
		$visitor = \XF::visitor();
	
		return (
			$visitor->user_id
			&& $this->Item->hasPermission('editAnyReview')
		);
	}
	
	public function canMove(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if (!$visitor->user_id)
		{
			return false;
		}
	
		$item = $this->Item;

		return $item->hasPermission('editAnyReview');
	}
	
	public function canDelete($type = 'soft', &$error = null)
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return false;
		}

		$item = $this->Item;
		
		if ($type != 'soft' && !$item->hasPermission('hardDeleteAnyReview'))
		{
			return false;
		}
		
		if ($item->hasPermission('deleteAnyReview'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id && $item->hasPermission('deleteReview'))
		{
			$editLimit = $item->hasPermission('editOwnReviewTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->rating_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_delete_this_review_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return false;
	}

	public function canUndelete(&$error = null)
	{
		$visitor = \XF::visitor();
		$item = $this->Item;

		if (!$visitor->user_id || !$item)
		{
			return false;
		}

		return $item->hasPermission('undelete');
	}
	
	public function canApproveUnapprove(&$error = null)
	{
		if (!$this->Item)
		{
			return false;
		}
	
		return $this->Item->hasPermission('approveUnapproveReview');
	}	

	public function canReport(&$error = null, \XF\Entity\User $asUser = null)
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}

	public function canWarn(&$error = null)
	{
		$visitor = \XF::visitor();
		$item = $this->Item;

		if ($this->warning_id
			|| !$item
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$item->hasPermission('warn')
		)
		{
			return false;
		}

		$user = $this->User;
		return ($user && $user->isWarnable());
	}
	
	public function canReply(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		if (!$this->is_review)
		{
			return false;
		}
		
		if ($this->rating_state != 'visible')
		{
			return false;
		}
		
		$item = $this->Item;
		
		if (!$item->isRatingsOpen())
		{
			return false;
		}
		
		$replyBans = $item->ReplyBans;
		if ($replyBans)
		{
			if (isset($replyBans[$visitor->user_id]))
			{
				$replyBan = $replyBans[$visitor->user_id];
				$isBanned = ($replyBan && (!$replyBan->expiry_date || $replyBan->expiry_date > time()));
				if ($isBanned)
				{
					return false;
				}
			}
		}

		// Checks to see if the viewing user is the Item owner and if the item owner has permission to reply to reivews on own item
		if ($item->isContributor())
		{
			if ($item->hasPermission('reviewReplyOwnItem'))
			{
				return true;
			}
		}
		
		// Checks to see if the viewing user is the Review owner and if the review owner has permission to reply to own reivews
		if ($item->isContributor() )
		{
			if ($item->hasPermission('reviewReplyOwnReview'))
			{
				return true;
			}
		}
		
		return $item->hasPermission('reviewReply');
	}
	
	public function canViewDeletedReplies()
	{
		$visitor = \XF::visitor();
		$item = $this->Item;
		
		return $item->hasPermission('viewDeletedReviews'); 
	}
	
	public function canViewModeratedReplies()
	{
		$visitor = \XF::visitor();
		$item = $this->Item;
		
		return $item->hasPermission('viewModeratedReviews'); 
	}
	
	public function canReact(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->rating_state != 'visible')
		{
			return false;
		}
	
		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}
	
		return $this->Item->hasPermission('reactReview');
	}
	
	public function canCleanSpam()
	{
		return (\XF::visitor()->canCleanSpam() && $this->User && $this->User->isPossibleSpammer());
	}	

	public function canViewAnonymousAuthor()
	{
		$visitor = \XF::visitor();

		return (
			$visitor->user_id
			&& (
				$visitor->user_id == $this->user_id
				|| $visitor->canBypassUserPrivacy()
			)
		);
	}
	
	public function isContentVotingSupported(): bool
	{
		$item = $this->Item;
	
		return $item->Category->review_voting !== '';
	}
	
	public function isContentDownvoteSupported(): bool
	{
		$item = $this->Item;
	
		return $item->Category->review_voting === 'yes';
	}
	
	protected function canVoteOnContentInternal(&$error = null): bool
	{
		if (!$this->isVisible())
		{
			return false;
		}
	
		return $this->Item->hasPermission('contentVote');
	}

	public function canSendModeratorActionAlert()
	{
		$visitor = \XF::visitor();
		
		if (!$visitor->user_id || $visitor->user_id == $this->user_id)
		{
			return false;
		}
		
		if ($this->rating_state != 'visible')
		{
			return false;
		}
		
		return true;
	}
	
	public function canUseInlineModeration(&$error = null)
	{
		$visitor = \XF::visitor();
		return ($visitor->user_id && $this->Item->hasPermission('inlineModReview'));
	}	
	
	public function hasMoreReplies()
	{
		if ($this->reply_count > 3)
		{
			return true;
		}
	
		$visitor = \XF::visitor();
	
		$canViewDeleted = $this->canViewDeletedReplies();    
		$canViewModerated = $this->canViewModeratedReplies(); 
	
		if (!$canViewDeleted && !$canViewModerated)
		{
			return false;
		}
	
		$viewableReplyCount = 0;
	
		foreach ($this->latest_reply_ids AS $replyId => $state)
		{
			switch ($state[0])
			{
				case 'visible':
					$viewableReplyCount++;
					break;
	
				case 'moderated':
					if ($canViewModerated)
					{
						$viewableReplyCount++;
					}
					break;
	
				case 'deleted':
					if ($canViewDeleted)
					{
						$viewableReplyCount++;
					}
					break;
			}
	
			if ($viewableReplyCount > 3)
			{
				return true;
			}
		}
	
		return false;
	}	

	public function isVisible()
	{
		return (
			$this->rating_state == 'visible'
			&& $this->Item
			&& $this->Item->isVisible()
		);
	}

	public function isIgnored()
	{
		if ($this->is_anonymous)
		{
			return false;
		}

		return \XF::visitor()->isIgnoring($this->user_id);
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
	
	public function getBbCodeRenderOptions($context, $type)
	{
		return [
			'entity' => $this,
			'user' => $this->User,
			'attachments' => $this->attach_count ? $this->Attachments : [],
			'viewAttachments' => $this->canViewReviewImages()
		];
	}

	/**
	 * @return string
	 */
	public function getItemTitle()
	{
		return $this->Item ? $this->Item->title : '';
	}
	
	/**
	 * @return Item
	 */
	public function getContent()
	{
		return $this->Item;
	}
	
	public function getFieldEditMode()
	{
		$visitor = \XF::visitor();
	
		$isSelf = ($visitor->user_id == $this->user_id || !$this->rating_id);
		$isMod = ($visitor->user_id && $this->Item->hasPermission('editAnyReview'));
	
		if ($isMod || !$isSelf)
		{
			return $isSelf ? 'moderator_user' : 'moderator';
		}
		else
		{
			return 'user';
		}
	}
	
	/**
	 * @return \XF\CustomField\Set
	 */
	public function getCustomFields()
	{
		/** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
		$fieldDefinitions = $this->app()->container('customFields.sc_reviews');
	
		return new \XF\CustomField\Set($fieldDefinitions, $this);
	}
	
	/**
	 * @return \XF\CustomField\Set
	 */
	public function getReviewFields()
	{
		/** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
		$fieldDefinitions = $this->app()->container('customFields.sc_reviews');
	
		return new \XF\CustomField\Set($fieldDefinitions, $this);
	}
	
	public function hasImageAttachments($review = false)
	{
		if ($review && $review['Attachments'])
		{
			$attachments = $review['Attachments'];
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
	
	/**
	 * @return array
	 */
	public function getReplyIds()
	{
		return $this->db()->fetchAllColumn("
			SELECT reply_id
			FROM xf_xa_sc_item_rating_reply
			WHERE rating_id = ?
			ORDER BY reply_date
		", $this->rating_id);
	}
	
	/**
	 * @return ArrayCollection|null
	 */
	public function getLatestReplies()
	{
		$this->repository('XenAddons\Showcase:ItemRating')->addRepliesToItemRatings([$this->rating_id => $this]);
	
		if (isset($this->_getterCache['LatestReplies']))
		{
			return $this->_getterCache['LatestReplies'];
		}
		else
		{
			return $this->_em->getBasicCollection([]);
		}
	}
	
	public function setLatestReplies(array $latest)
	{
		$this->_getterCache['LatestReplies'] = $this->_em->getBasicCollection($latest);
	}
	
	public function replyAdded(ItemRatingReply $reply)
	{
		$this->reply_count++;
	
		if (!$this->first_reply_date || $reply->reply_date < $this->first_reply_date)
		{
			$this->first_reply_date = $reply->reply_date;
		}
	
		if ($reply->reply_date > $this->last_reply_date)
		{
			$this->last_reply_date = $reply->reply_date;
		}
	
		$this->rebuildLatestReplyIds();
	
		unset($this->_getterCache['reply_ids']);
	}
	
	public function replyRemoved(ItemRatingReply $reply)
	{
		$this->reply_count--;
	
		if ($this->first_reply_date == $reply->reply_date)
		{
			if (!$this->reply_count)
			{
				$this->first_reply_date = 0;
			}
			else
			{
				$this->rebuildFirstReplyInfo();
			}
		}
	
		if ($this->last_reply_date == $reply->reply_date)
		{
			if (!$this->reply_count)
			{
				$this->last_reply_date = 0;
			}
			else
			{
				$this->rebuildLastReplyInfo();
			}
		}
	
		$this->rebuildLatestReplyIds();
	
		unset($this->_getterCache['reply_ids']);
	}	
	
	public function rebuildCounters()
	{
		if (!$this->rebuildFirstReplyInfo())
		{
			// no visible replies, we know we've set the last reply and count to 0
		}
		else
		{
			$this->rebuildLastReplyInfo();
			$this->rebuildReplyCount();
		}
	
		// since this contains non-visible replies, we always have to rebuild
		$this->rebuildLatestReplyIds();
	
		return true;
	}
	
	public function rebuildFirstReplyInfo()
	{
		$firstReply = $this->db()->fetchRow("
			SELECT reply_id, reply_date, user_id, username
			FROM xf_xa_sc_item_rating_reply
			WHERE rating_id = ?
				AND reply_state = 'visible'
			ORDER BY reply_date
			LIMIT 1
		", $this->rating_id);
	
		if (!$firstReply)
		{
			$this->reply_count = 0;
			$this->first_reply_date = 0;
			$this->last_reply_date = 0;
			return false;
		}
		else
		{
			$this->last_reply_date = $firstReply['reply_date'];
			return true;
		}
	}
	
	public function rebuildLastReplyInfo()
	{
		$lastReply = $this->db()->fetchRow("
			SELECT reply_id, reply_date, user_id, username
			FROM xf_xa_sc_item_rating_reply
			WHERE rating_id = ?
				AND reply_state = 'visible'
			ORDER BY reply_date DESC
			LIMIT 1
		", $this->rating_id);
	
		if (!$lastReply)
		{
			$this->reply_count = 0;
			$this->first_reply_date = 0;
			$this->last_reply_date = 0;
			return false;
		}
		else
		{
			$this->last_reply_date = $lastReply['reply_date'];
			return true;
		}
	}
	
	public function rebuildReplyCount()
	{
		$visibleReplies = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_item_rating_reply
			WHERE rating_id = ?
				AND reply_state = 'visible'
		", $this->rating_id);
	
		$this->reply_count = $visibleReplies;
	
		return $this->reply_count;
	}
	
	public function rebuildLatestReplyIds()
	{
		$this->latest_reply_ids = $this->repository('XenAddons\Showcase:ItemRating')->getLatestReplyCache($this);
	}	

	protected function _preSave()
	{
		if ($this->isChanged('message'))
		{
			$this->is_review = strlen($this->message) ? true : false;
		}
	}

	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('rating_state', 'visible');
		$approvalChange = $this->isStateChanged('rating_state', 'moderated');
		$deletionChange = $this->isStateChanged('rating_state', 'deleted');

		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->ratingMadeVisible();
			}
			else if ($visibilityChange == 'leave')
			{
				$this->ratingHidden();
			}
			else if ($this->isChanged('rating'))
			{
				$this->ratingChanged();
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
			if ($this->rating_state == 'visible')
			{
				$this->ratingMadeVisible();
			}
		}

		if ($approvalChange == 'enter')
		{
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->rating_date;
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
			$this->app()->logger()->logModeratorChanges('sc_rating', $this);
		}
	}

	protected function ratingChanged()
	{
		$item = $this->Item;
	
		if ($item)
		{
			$item->rebuildRating();
			$item->saveIfChanged();
		}
	}
	
	protected function ratingMadeVisible()
	{
		$item = $this->Item;

		if ($item)
		{
			if ($this->is_review)
			{
				$item->review_count++;
				$this->adjustUserReviewCountIfNeeded(1);
			}
			
			$item->rebuildRating();
			$item->saveIfChanged();
		}
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->recalculateReactionIsCounted('sc_rating_reply', $this->reply_ids);
	}

	protected function ratingHidden($hardDelete = false)
	{
		$item = $this->Item;

		if ($item)
		{
			if ($this->is_review)
			{
				$item->review_count--;
				$this->adjustUserReviewCountIfNeeded(-1);
			}			
			
			$item->rebuildRating();
			$item->saveIfChanged();
		}

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('sc_rating', $this->rating_id);
		$alertRepo->fastDeleteAlertsForContent('sc_rating_reply', $this->reply_ids);
		
		if (!$hardDelete)
		{
			// on hard delete the reactions will be removed which will do this
			/** @var \XF\Repository\Reaction $reactionRepo */
			$reactionRepo = $this->repository('XF:Reaction');
			$reactionRepo->recalculateReactionIsCounted('sc_rating_reply', $this->reply_ids, false);
		}
	}
	
	protected function reviewInsertedVisible()
	{
		$this->adjustUserReviewCountIfNeeded(1);
	}
	
	protected function adjustUserReviewCountIfNeeded($amount, $userId = null)
	{
		if ($userId === null)
		{
			$userId = $this->user_id;
		}
	
		if ($userId)
		{
			$this->db()->query("
				UPDATE xf_user
				SET xa_sc_review_count = GREATEST(0, xa_sc_review_count + ?)
				WHERE user_id = ?
			", [$amount, $userId]);
		}
	}

	protected function _postDelete()
	{
		if ($this->rating_state == 'visible')
		{
			$this->ratingHidden(true);
		}

		if ($this->rating_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->rating_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_rating', $this, 'delete_hard');
		}
		
		$db = $this->db();
		
		$db->delete('xf_xa_sc_review_field_value', 'rating_id = ?', $this->rating_id);
		
		$db->delete('xf_approval_queue', 'content_id = ? AND content_type = ?', [$this->rating_id, 'sc_rating']);
		$db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->rating_id, 'sc_rating']);
		$db->delete('xf_edit_history', 'content_id = ? AND content_type = ?', [$this->rating_id, 'sc_rating']);
		
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_rating', $this->rating_id);
		
		$replyIds = $this->reply_ids;
		if ($replyIds)
		{
			$quotedIds = $db->quote($replyIds);
		
			$db->delete('xf_xa_sc_item_rating_reply', "reply_id IN ({$quotedIds})");
			$db->delete('xf_approval_queue', "content_id IN ({$quotedIds}) AND content_type = 'sc_rating_reply'");
			$db->delete('xf_deletion_log', "content_id IN ({$quotedIds}) AND content_type = 'sc_rating_reply'");
		}
	}

	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();

		if ($this->rating_state == 'deleted')
		{
			return false;
		}

		$this->rating_state = 'deleted';

		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;

		$this->save();

		return true;
	}
	
	public function getNewReply()
	{
		$reply = $this->_em->create('XenAddons\Showcase:ItemRatingReply');
		$reply->rating_id = $this->rating_id;
	
		return $reply;
	}
	
	public function getNewReplyState()
	{
		$visitor = \XF::visitor();
		
		if ($this->canApproveUnapprove())
		{
			return 'visible';
		}
		
		if (!$visitor->hasPermission('general', 'submitWithoutApproval'))
		{
			return 'moderated';
		}
		
		return 'visible';
	}

	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
	{
		if ($this->is_review)
		{
			$route = ($canonical ? 'canonical:' : '') . 'showcase/review';
			return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
		}
		else
		{
			return '';
		}
	}
	
	public function getContentPublicRoute()
	{
		return 'showcase/review';
	}
	
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('xa_sc_review_in_x', [
			'title' => $this->Item->title
		]);
	}
	
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_rating';
		$structure->shortName = 'XenAddons\Showcase:ItemRating';
		$structure->primaryKey = 'rating_id';
		$structure->contentType = 'sc_rating';
		$structure->columns = [
			'rating_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'username' => ['type' => self::STR, 'maxLength' => 50,
				'required' => 'please_enter_valid_name'
			],
			'rating' => ['type' => self::UINT, 'required' => true, 'min' => 1, 'max' => 5],
			'title' => ['type' => self::STR, 'default' => '', 'maxLength' => 100],
			'rating_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'rating_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted']
			],
			'is_review' => ['type' => self::BOOL, 'default' => false],
			'pros' => ['type' => self::STR, 'default' => ''],
			'cons' => ['type' => self::STR, 'default' => ''],
			'message' => ['type' => self::STR, 'default' => ''],
			'custom_fields' => ['type' => self::JSON_ARRAY, 'default' => []],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'is_anonymous' => ['type' => self::BOOL, 'default' => false],
			'attach_count' => ['type' => self::UINT, 'default' => 0],
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
			'edit_count' => ['type' => self::UINT, 'default' => 0],
			'reply_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
			'first_reply_date' => ['type' => self::UINT, 'default' => 0],
			'last_reply_date' => ['type' => self::UINT, 'default' => 0],
			'latest_reply_ids' => ['type' => self::JSON_ARRAY, 'default' => []],			
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
		];
		$structure->getters = [
			'custom_fields' => true,
			'Content' => true,
			'item_title' => true,
			'reply_ids' => true,
			'LatestReplies' => true,
		];
		$structure->behaviors = [
			'XF:ContentVotable' => ['stateField' => 'rating_state'],
			'XF:Reactable' => ['stateField' => 'rating_state'],
			'XF:ReactableContainer' => [
				'childContentType' => 'sc_rating_reply',
				'childIds' => function($itemRating) { return $itemRating->reply_ids; },
				'stateField' => 'reply_state'
			],
			'XF:NewsFeedPublishable' => [
				'userIdField' => function($rating) { return $rating->is_anonymous ? 0 : $rating->user_id; },
				'usernameField' => function($rating) { return $rating->is_anonymous ? '' : $rating->User->username; },
				'dateField' => 'rating_date'
			],
			'XF:CustomFieldsHolder' => [
				'valueTable' => 'xf_xa_sc_review_field_value',
				'checkForUpdates' => ['category_id'],
				'getAllowedFields' => function($rating) { return $rating->Item->Category ? $rating->Item->Category->review_field_cache : []; }
			],
			'XF:Indexable' => [
				'checkForUpdates' => ['message', 'user_id', 'item_id', 'rating_date', 'rating_state']
			],
			'XF:IndexableContainer' => [
				'childContentType' => 'sc_rating_reply',
				'childIds' => function($itemRating) { return $itemRating->reply_ids; },
				'checkForUpdates' => ['rating_id', 'reply_state']
			],
		];
		$structure->relations = [
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Attachments' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'sc_rating'],
					['content_id', '=', '$rating_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_rating'],
					['content_id', '=', '$rating_id']
				],
				'primary' => true
			],			
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_rating'],
					['content_id', '=', '$rating_id']
				],
				'primary' => true
			],
			'Replies' => [
				'entity' => 'XenAddons\Showcase:ItemRatingReply',
				'type' => self::TO_MANY,
				'conditions' => 'rating_id',
				'primary' => true
			]
		];
		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = ['Item'];

		$structure->withAliases = [
			'full' => [
				'User',
				function()
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return [
							'Reactions|' . $userId,
							'ContentVotes|' . $userId
						];
					}
				}
			]
		];
		
		static::addReactableStructureElements($structure);
		static::addVotableStructureElements($structure);
		
		return $structure;
	}
}