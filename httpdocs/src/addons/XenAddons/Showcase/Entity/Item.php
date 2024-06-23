<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\BookmarkTrait;
use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Entity\User;
use XF\Util\Arr;

use function gmdate;

/**
 * COLUMNS
 * @property int|null $item_id
 * @property int $category_id
 * @property int $user_id
 * @property string $username
 * @property array $contributor_user_ids
 * @property string $title
 * @property string $og_title
 * @property string $meta_title
 * @property string $description
 * @property string $meta_description
 * @property string $item_state
 * @property bool $sticky
 * @property string $message
 * @property int $create_date
 * @property int $edit_date
 * @property int $last_update
 * @property int $category_id
 * @property int $discussion_thread_id
 * @property int $rating_count
 * @property int $rating_sum
 * @property string $rating_avg
 * @property string $rating_weighted
 * @property int $review_count
 * @property int $attach_count
 * @property int $ip_id
 * @property int $view_count
 * @property int $watch_count
 * @property int $update_count
 * @property int $page_count
 * @property array $custom_fields_
 * @property int $prefix_id
 * @property string $location
 * @property array $tags
 * @property bool $has_poll
 * @property int $series_part_id
 * @property array|null $embed_metadata
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 * 
 * GETTERS
 * @property \XF\CustomField\Set $custom_fields
 * @property int $real_update_count
 * @property int $real_review_count
 * @property int $real_comment_count
 * @property int $image_attach_count
 * @property array $item_page_ids
 * @property array $item_update_ids
 * @property array $item_rating_ids
 * @property \XF\Draft $draft_item
 * @property \XF\Draft $draft_comment
 * @property mixed $comment_ids
 * @property mixed $reactions
 * @property mixed $reaction_users
 * 
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Category $Category
 * @property \XenAddons\Showcase\Entity\ItemFeature $Featured
 * @property \XenAddons\Showcase\Entity\ItemPrefix $Prefix
 * @property \XenAddons\Showcase\Entity\Comment $LastComment
 * @property \XenAddons\Showcase\Entity\SeriesPart $SeriesPart
 * @property \XF\Entity\Attachment $CoverImage
 * @property \XF\Entity\User $User
 * @property \XF\Entity\User $LastCommenter
 * @property \XF\Entity\Thread $Discussion
 * @property \XF\Entity\Poll $Poll
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemContributor[] $Contributors
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemPage[] $Pages
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemRead[] $Read
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\CommentRead[] $CommentRead
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemFieldValue[] $CustomFields
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemReplyBan[] $ReplyBans
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemWatch[] $Watch
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Draft[] $DraftItems
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Draft[] $DraftComments
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\BookmarkItem[] $Bookmarks 
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\TagContent[] $Tags
 */
class Item extends Entity implements \XF\BbCode\RenderableContentInterface, \XF\Entity\LinkableInterface
{
	use CommentableTrait, ReactionTrait, BookmarkTrait;
	
	const RATING_WEIGHTED_THRESHOLD = 10;
	const RATING_WEIGHTED_AVERAGE = 3;

	public function canView(&$error = null)
	{
		if (!$this->Category || !$this->Category->canView())
		{
			return false;
		}

		$visitor = \XF::visitor();

		if (!$this->hasPermission('view'))
		{
			return false;
		}

		if ($this->item_state == 'moderated')
		{
			if (
				!$this->hasPermission('viewModerated')
				&& (!$visitor->user_id || !$this->isContributor())
			)
			{
				return false;
			}
		}
		else if ($this->item_state == 'deleted')
		{
			if (!$this->hasPermission('viewDeleted'))
			{
				return false;
			}
		}
		else if ($this->item_state == 'draft')
		{
			if (
				!$this->hasPermission('viewDraft')
				&& (!$visitor->user_id || !$this->isContributor())
			)
			{
				return false;
			}
		}
		else if ($this->item_state == 'awaiting')
		{
			if (
				!$this->hasPermission('viewDraft')
				&& (!$visitor->user_id || !$this->isContributor())
			)
			{
				return false;
			}
		}
		
		return true;
	}
	
	public function canViewFullItem()
	{
		$visitor = \XF::visitor();
	
		return (
			$this->hasPermission('viewFull')
			|| ($visitor->user_id && $this->isContributor())
		);
	}
	
	public function canViewSection($section = null)
	{
		$category = $this->Category;
	
		if (!$section || !$category)
		{
			return false;
		}
	
		$sectionTitleField = 'title_' . $section;
		if (!$category->$sectionTitleField)
		{
			return false;
		}
	
		$sectionMessageField = 'message_' . $section;
		if ($this->$sectionMessageField != '')
		{
			return true;
		}
	
		// check to see if there are any custom fields for the section and that the section field(s) contain data!
	
		if ($section == 's2')
		{
			$sectionHasFields = $this->getSectionFields(2);
			if ($sectionHasFields)
			{
				return true;
			}
		}
		else if ($section == 's3')
		{
			$sectionHasFields = $this->getSectionFields(3);
			if ($sectionHasFields)
			{
				return true;
			}
		}
		else if ($section == 's4')
		{
			$sectionHasFields = $this->getSectionFields(4);
			if ($sectionHasFields)
			{
				return true;
			}
		}
		else if ($section == 's5')
		{
			$sectionHasFields = $this->getSectionFields(5);
			if ($sectionHasFields)
			{
				return true;
			}
		}
		else if ($section == 's6')
		{
			$sectionHasFields = $this->getSectionFields(6);
			if ($sectionHasFields)
			{
				return true;
			}
		}
	
		return false;
	}	
	
	public function canViewItemMap()
	{
		return $this->hasPermission('viewItemMap');
	}
	
	public function canViewItemAttachments()
	{
		return $this->hasPermission('viewItemAttach');
	}
	
	public function canViewPageAttachments()
	{
		return $this->hasPermission('viewItemAttach');
	}
	
	// Updates view related permissions checks
	
	public function canViewUpdates(&$error = null)
	{
		return $this->hasPermission('viewUpdates'); 
	}
	
	public function canViewModeratedUpdates()
	{
		$visitor = \XF::visitor();
		if ($this->hasPermission('viewModerated')) // piggyback off of item permissions
		{
			return true;
		}
		else if ($visitor->user_id && $this->isContributor())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function canViewDeletedUpdates()
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		return $this->hasPermission('viewDeleted'); // piggyback off of item permissions 
	}
	
	public function canViewUpdateImages()
	{
		return $this->hasPermission('viewUpdateAttach');  
	}
	
	
	// Reviews view related permissions checks
	
	public function canViewReviews(&$error = null)
	{
		return $this->hasPermission('viewReviews');
	}

	public function canViewModeratedReviews()
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		return $this->hasPermission('viewModeratedReviews');
	}
	
	public function canViewDeletedReviews()
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		return $this->hasPermission('viewDeletedReviews');
	}	
	
	public function canViewReviewImages()
	{
		return $this->hasPermission('viewReviewAttach');
	}

	
	// Comments view related permissions checks
	
	public function canViewCommentImages()
	{
		return $this->hasPermission('viewCommentAttach');
	}
	

	// item specific permissions checks	

	public function canAddItemToSeries(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->isInSeries())
		{
			return false;
		}
	
		if (!$this->isVisible())
		{
			return false;
		}
	
		if ($this->canAddItemToAnySeries())
		{
			return true;
		}
	
		if ($this->user_id == $visitor->user_id)
		{
			return (
				$this->hasPermission('createSeries')
				|| $this->hasPermission('addToCommunitySeries')
			);
		}
	
		return false;
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
	
		if (!$this->Category->canCreatePoll())
		{
			return false;
		}
	
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		$categoryId = $this->category_id;
	
		if ($visitor->hasShowcaseItemCategoryPermission($categoryId, 'editAny'))
		{
			return true;
		}
	
		if ($this->isContributor() && $visitor->hasShowcaseItemCategoryPermission($categoryId, 'editOwn'))
		{
			$editLimit = $visitor->hasShowcaseItemCategoryPermission($categoryId, 'editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit) && $this->item_state != 'draft')
			{
				$error = \XF::phraseDeferred('message_edit_time_limit_expired', ['minutes' => $editLimit]);
				return false;
			}
	
			return true;
		}
	
		return false;
	}
	
	public function canAddPage(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		// pages can be added to 'visible', 'draft' or 'awaiting' states only!
		if ($this->isVisible() || $this->isDraft() || $this->isAwaitingPublishing())
		{
			return (
				$this->isContributor()
				&&	$this->hasPermission('addPageOwnItem')
			);
		}
		else
		{
			return false;
		}
	}
	
	public function canAddUpdate(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		if (!$this->isVisible())
		{
			return false;
		}
		
		if ($this->isContributor() && $this->hasPermission('addUpdate'))
		{
			return true;
		}
	
		return false;
	}
	
	public function canRate(&$error = null)
	{
		if (!$this->isVisible())
		{
			return false;
		}

		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		if (!$this->isAllowRatings())
		{
			return false;
		}
		
		if (!$this->isRatingsOpen())
		{
			return false;
		}

		if ($this->isContributor() && !$this->hasPermission('rateOwn'))
		{
			return false;
		}

		if(!$this->hasPermission('reviewMultiple') && $this->hasPermission('rate'))
		{
			$existingRating = $this->Ratings[$visitor->user_id];
			if ($existingRating)
			{
				return false;
			}
		}
		
		$replyBans = $this->ReplyBans;
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

		return $this->hasPermission('rate');
	}
	
	public function canRatePreReg()
	{
		if (\XF::visitor()->user_id || $this->canRate())
		{
			// quick bypass with the user ID check, then ensure that this can only return true if the visitor
			// can't take the "normal" action
			return false;
		}
	
		return \XF::canPerformPreRegAction(
			function() { return $this->canRate(); }
		);
	}
	
	public function canReviewAnon(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		return $this->hasPermission('reviewAnon');
	}
	
	public function canReviewAnonPreReg()
	{
		if (\XF::visitor()->user_id || $this->canReviewAnon())
		{
			// quick bypass with the user ID check, then ensure that this can only return true if the visitor
			// can't take the "normal" action
			return false;
		}
	
		return \XF::canPerformPreRegAction(
			function() { return $this->canReviewAnon(); }
		);
	}
	
	public function canPublishDraft(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if (!in_array($this->item_state, ['draft','awaiting']))
		{
			return false;
		}
			
		return $this->canEdit($error);
	}
	
	public function canPublishDraftScheduled(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if ($this->item_state != 'draft')
		{
			return false;
		}
			
		return $this->canEdit($error);
	}
	
	public function canChangeScheduledPublishDate(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if ($this->item_state != 'awaiting')
		{
			return false;
		}
			
		return $this->canEdit($error);
	}
	
	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->hasPermission('editAny'))
		{
			return true;
		}
		
		if ($this->isContributor() && $this->hasPermission('editOwn'))
		{
			$editLimit = $this->hasPermission('editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit) && $this->item_state != 'draft')
			{
				$error = \XF::phrase('xa_sc_time_limit_to_edit_this_item_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return false;		
	}
	
	public function canJoinContributorsTeam(&$error = null)///: bool
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if (!$this->Category->allow_contributors)
		{
			return false;
		}
	
		if (!$this->Category->allow_self_join_contributors)
		{
			return false;
		}
	
		if ($this->getMaxContributorCount() == 0)
		{
			return false;
		}
	
		if ($this->isOwner() || $this->isContributor())
		{
			return false;
		}
	
		return $this->hasPermission('selfJoinContributors');
	}
	
	public function canLeaveContributorsTeam(&$error = null) //: bool
	{
		if (!$this->Category->allow_contributors)
		{
			return false;
		}
	
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->isOwner())
		{
			return false;
		}
	
		return $this->isContributor();
	}
	
	// TODO redesign this for Showcase!
	public function canViewContributors() //: bool
	{
		if (!$this->Category->allow_contributors)
		{
			return false;
		}
	
		$visitor = \XF::visitor();
	
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
	
		if ($this->isContributor())
		{
			return true;
		}
	
		return true; // FOR NOW, EVERYONE CAN VIEW CONTRIBUTORS!
	}
	
	public function canManageContributors(&$error = null) //: bool
	{
		if (!$this->Category->allow_contributors)
		{
			return false;
		}
	
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->getMaxContributorCount() == 0)
		{
			return false;
		}
	
		return $this->canAddContributors() || $this->canRemoveContributors();
	}
	
	/**
	 * Note: check that canManageContributors is true before relying on this value.
	 *
	 * @return bool
	 */
	public function canAddCoOwners() //: bool
	{
		if ($this->hasPermission('manageAnyContributors'))
		{
			return true;
		}
	
		return (
			\XF::visitor()->user_id == $this->user_id
			&& $this->hasPermission('manageOwnContributors')
		);
	}
	
	/**
	 * Note: check that canManageContributors is true before relying on this value.
	 *
	 * @return bool
	 */
	public function canAddContributors() //: bool
	{
		if ($this->hasPermission('manageAnyContributors'))
		{
			return true;
		}
	
		return (
			\XF::visitor()->user_id == $this->user_id
			&& $this->hasPermission('manageOwnContributors')
		);
	}
	
	/**
	 * Note: check that canManageContributors is true before relying on this value.
	 *
	 * @return bool
	 */
	public function canRemoveContributors() //: bool
	{
		if (!$this->contributor_user_ids)
		{
			return false;
		}
	
		if ($this->hasPermission('manageAnyContributors'))
		{
			return true;
		}
	
		if ($this->hasPermission('editAny')) 
		{
			return true;
		}
	
		return (
			\XF::visitor()->user_id == $this->user_id
			&& $this->hasPermission('manageOwnContributors')
		);
	}
	
	public function canContributorBeAdded(User $user) //: bool
	{
		if (!$user->user_id)
		{
			return false;
		}
	
		if ($user->user_id == $this->user_id)
		{
			// can't add the author - normally skipped before this
			return false;
		}
	
		if ($user->isIgnoring($this->user_id))
		{
			// if the target is ignoring the item author, then don't let them be added
			return false;
		}
	
		/** @var \XenAddons\Showcase\XF\Entity\User $user */
		return $user->hasShowcaseItemCategoryPermission(
			$this->category_id,
			'add'
		);
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
	
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
	
		return false;
	}
	
	public function canSetAuthorRating(&$error = null)
	{
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
		
		return (
			$this->hasPermission('setAuthorRatingOwn')
			&& $this->canEdit($error)
		);
	}	
	
	public function canSetCoverImage(&$error = null)
	{
		if (!$this->hasImageAttachments())
		{
			return false;
		}
		
		return $this->canEdit($error);
	}
	
	public function canSetBusinessHours(&$error = null)
	{
		if (!$this->Category->allow_business_hours)
		{
			return false;
		}
	
		return $this->canEdit($error);
	}

	public function canMove(&$error = null)
	{
		$visitor = \XF::visitor();
		
		if (!$visitor->user_id)
		{
			return false;
		}

		return $this->hasPermission('editAny');
	}
	
	public function canMerge(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if (!$visitor->user_id)
		{
			return false;
		}
	
		return $this->hasPermission('editAny');
	}

	public function canReassign(&$error = null)
	{
		$visitor = \XF::visitor();

		return (
			$visitor->user_id
			&& $this->hasPermission('editAny')
			&& $this->hasPermission('reassign')
		);
	}

	public function canFeatureUnfeature(&$error = null)
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id
			&& $this->isVisible()
			&& $this->hasPermission('featureUnfeature')
		);
	}
	
	public function canStickUnstick(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if (!$visitor->user_id)
		{
			return false;
		}

		return (
			$visitor->user_id
			&& $this->hasPermission('editAny')
		);
	}
	
	public function canBookmarkContent(&$error = null)
	{
		return $this->isVisible();
	}
	
	public function canChangeDates(&$error = null)
	{
		$visitor = \XF::visitor();
		
		if (in_array($this->item_state, ['draft','awaiting']))
		{
			return false;
		}
		
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
		
		return (
			$visitor->user_id
			&& $this->isContributor()
			&& $this->canEdit($error)	
		);
	}
	
	public function canDelete($type = 'soft', &$error = null)
	{
		$visitor = \XF::visitor();

		if ($type != 'soft')
		{
			return $this->hasPermission('hardDeleteAny');
		}

		if ($this->hasPermission('deleteAny'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id && $this->hasPermission('deleteOwn'))
		{
			$editLimit = $this->hasPermission('editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit) && $this->item_state != 'draft')
			{
				$error = \XF::phrase('xa_sc_time_limit_to_delete_this_item_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return false;		
	}

	public function canUndelete(&$error = null)
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id 
			&& $this->hasPermission('undelete')
		);
	}
	
	public function canManageRatings(&$error = null)
	{
		$visitor = \XF::visitor();
	
		return (
			$visitor->user_id
			&& $this->hasPermission('editAny')
		);
	}
	
	public function canManagePages(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
	
		return (
			$visitor->user_id
			&& $this->isContributor()
			&& $this->hasPermission('addPageOwnItem')
			&& $this->canEdit($error)
		);
	}
	
	public function canReplyBan(&$error = null)
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id 
			&& $this->hasPermission('itemReplyBan')
		);
	}
	
	public function canChangeDiscussionThread(&$error = null)
	{
		$visitor = \XF::visitor();

		if (in_array($this->item_state, ['draft','awaiting']))
		{
			return false;
		}
		
		return (
			$visitor->user_id 
			&& $this->hasPermission('editAny')
		);
	}
	
	public function canConvertToThread(&$error = null)
	{
		$visitor = \XF::visitor();
		
		if (in_array($this->item_state, ['draft','awaiting']))
		{
			return false;
		}
	
		return (
			$visitor->user_id
			&& $this->hasPermission('editAny')
			&& $this->hasPermission('convertToThread')
		);
	}

	public function canSendModeratorActionAlert()
	{
		$visitor = \XF::visitor();

		return (
			$visitor->user_id
			&& !$this->isContributor()
			&& $this->item_state == 'visible'
		);
	}

	public function canApproveUnapprove(&$error = null)
	{
		$visitor = \XF::visitor();
		
		if (in_array($this->item_state, ['draft','awaiting']))
		{
			return false;
		}
		
		return (
			$visitor->user_id
			&& $this->hasPermission('approveUnapprove')
		);
	}
	
	public function canWarn(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if (in_array($this->item_state, ['draft','awaiting']))
		{
			return false;
		}
		
		if ($this->warning_id
			|| !$this->user_id
			|| !$visitor->user_id
			|| $this->isContributor()
			|| !$this->hasPermission('warn')
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
	
		if ($this->item_state != 'visible')
		{
			return false;
		}
	
		if ($this->isContributor())
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}
	
		return $this->hasPermission('react');
	}
	
	public function canReport(&$error = null, \XF\Entity\User $asUser = null)
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}

	public function canWatch(&$error = null)
	{
		$visitor = \XF::visitor();

		return ($visitor->user_id);
	}

	public function canEditTags(&$error = null)
	{
		$category = $this->Category;
		return $category ? $category->canEditTags($this, $error) : false;
	}

	public function canUseInlineModeration(&$error = null)
	{
		$visitor = \XF::visitor();
		
		if (in_array($this->item_state, ['draft','awaiting']))
		{
			return false;
		}
		
		return ($visitor->user_id && $this->hasPermission('inlineMod'));
	}
	
	public function canViewModeratorLogs(&$error = null)
	{
		$visitor = \XF::visitor();
		return $visitor->user_id && ($this->hasPermission('editAny') || $this->hasPermission('deleteAny'));
	}
	
	public function canLockUnlockComments(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
	
		return (
			$visitor->user_id
			&& $this->isContributor()
			&& $this->hasPermission('lockUnlockCommentsOwn')
		);
	}	
	
	public function canLockUnlockRatings(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if ($this->hasPermission('editAny'))
		{
			return true;
		}
	
		return (
			$visitor->user_id
			&& $this->isContributor()
			&& $this->hasPermission('lockUnlockRatingsOwn')
		);
	}
	

	
	public function getSectionFields($sectionId = null)
	{
		if (!$this->getValue('custom_fields') || !$sectionId)
		{
			return [];
		}
		
		$filterGroup = 'section_' . $sectionId;
	
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $this->custom_fields;
		$definitionSet = $fieldSet->getDefinitionSet()
			->filterOnly($this->Category->field_cache)
			->filterGroup($filterGroup)
			->filterWithValue($fieldSet);
	
		$output = [];
		foreach ($definitionSet AS $fieldId => $definition)
		{
			$output[$fieldId] = $definition->title;
		}
	
		return $output;
	}

	public function hasPermission($permission)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		return $visitor->hasShowcaseItemCategoryPermission($this->category_id, $permission);
	}

	public function hasSections()
	{
		if ($this->message_s2 && $this->message_s2 != '')
		{
			return true;	
		}	
		elseif ($this->message_s3 && $this->message_s3 != '')
		{
			return true;
		}
		elseif ($this->message_s4 && $this->message_s4 != '')
		{
			return true;
		}
		elseif ($this->message_s5 && $this->message_s5 != '')
		{
			return true;
		}
		elseif ($this->message_s6 && $this->message_s6 != '')
		{
			return true;
		}
	
		return false;
	}
	
	public function hasViewableDiscussion()
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
	
	public function isUnread()
	{
		if ($this->item_state == 'deleted')
		{
			return false;
		}
	
		$readDate = $this->getVisitorItemReadDate();
		if ($readDate === null)
		{
			return false;
		}
	
		return $readDate < $this->last_update;
	}
	
	public function getVisitorItemReadDate()
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return null;
		}
	
		$itemRead = $this->Read[$visitor->user_id];
	
		$dates = [\XF::$time - $this->app()->options()->readMarkingDataLifetime * 86400];
		if ($itemRead)
		{
			$dates[] = $itemRead->item_read_date;
		}
	
		return max($dates);
	}
	
	public function isIgnored()
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}

	public function isVisible()
	{
		return ($this->item_state == 'visible');
	}
	
	public function isSearchEngineIndexable() //: bool
	{
		$category = $this->Category;
		if (!$category)
		{
			return false;
		}
	
		if ($category->allow_index == 'criteria')
		{
			$criteria = $category->index_criteria;
	
			if (
				!empty($criteria['max_days_create']) &&
				$this->create_date < \XF::$time - $criteria['max_days_create'] * 86400
			)
			{
				return false;
			}
	
			if (
				!empty($criteria['max_days_last_update']) &&
				$this->last_update < \XF::$time - $criteria['max_days_last_update'] * 86400
			)
			{
				return false;
			}
	
			if (
				!empty($criteria['min_views']) &&
				$this->view_count < $criteria['min_views']
			)
			{
				return false;
			}
				
			if (
				!empty($criteria['min_reviews']) &&
				$this->review_count < $criteria['min_reviews']
			)
			{
				return false;
			}
	
			if (
				isset($criteria['min_rating_avg']) &&
				$this->rating_weighted < $criteria['min_rating_avg']
			)
			{
				return false;
			}
				
			if (
				isset($criteria['min_reaction_score']) &&
				$this->reaction_score < $criteria['min_reaction_score']
			)
			{
				return false;
			}
	
			return true;
		}
	
		return ($category->allow_index == 'allow');
	}
	
	public function isOwner() //: bool
	{
		$visitor = \XF::visitor();
		return $visitor->user_id && $visitor->user_id == $this->user_id;
	}
	
	public function isContributor(int $userId = null) //: bool
	{
		$userId = $userId === null ? \XF::visitor()->user_id : $userId;
		if (!$userId)
		{
			return false;
		}
	
		if ($userId == $this->user_id)
		{
			return true;
		}
	
		if (!$this->Category->allow_contributors)
		{
			return false;
		}
	
		return in_array($userId, $this->contributor_user_ids);
	}
	
	public function isNonOwnerContributor(int $userId = null) //: bool
	{
		$userId = $userId === null ? \XF::visitor()->user_id : $userId;
		if (!$userId)
		{
			return false;
		}
	
		if ($userId == $this->user_id)
		{
			return false;
		}
	
		return $this->isContributor($userId);
	}
	
	public function isInSeries($verify_is_viewable = false)
	{
		if ($verify_is_viewable)
		{
			return (
				$this->series_part_id
				&& $this->SeriesPart
				&& $this->SeriesPart->canView()
			);
		}
	
		return (($this->series_part_id && $this->SeriesPart) ? true : false);
	}
	
	public function isDraft()
	{
		return ($this->item_state == 'draft');
	}
	
	public function isAwaitingPublishing()
	{
		return ($this->item_state == 'awaiting');
	}
	
	public function isAllowComments()
	{
		return ($this->Category->allow_comments);
	}
	
	public function isCommentsOpen()
	{
		return ($this->comments_open);
	}

	public function isAllowRatings()
	{
		return ($this->Category->allow_ratings);
	}
	
	public function isRatingsOpen()
	{
		return ($this->ratings_open);
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
	
	public function isAttachmentCoverImage($attachmentId)
	{
		if (!$this->cover_image_id)
		{
			return false;
		}
	
		if ($attachmentId instanceof \XF\Entity\Attachment)
		{
			$attachmentId = $attachmentId->attachment_id;
		}
	
		return $this->cover_image_id == $attachmentId;
	}
	
	public function getMaxContributorCount() //: int
	{
		return $this->Category->max_allowed_contributors;
	}
	
	public function isBusinessOpen()
	{
		if ($this->item_state == 'deleted')
		{
			return false;
		}
	
		$readDate = $this->getVisitorItemReadDate();
		if ($readDate === null)
		{
			return false;
		}
	
		return $readDate < $this->last_update;
	}
	
	public function isBusinessOpen24Hours($day = null)
	{
		if (!$day)
		{
			return false;
		}
	
		if ($day == 'mon')
		{
			if ($this->business_hours['monday_open_hour'] == '00'
				&& $this->business_hours['monday_open_minute'] == '00'
				&& $this->business_hours['monday_close_hour'] == '00'
				&& $this->business_hours['monday_close_minute'] == '00'
			)
			{
				return true;
			}
		}
		elseif ($day == 'tue')
		{
			if ($this->business_hours['tuesday_open_hour'] == '00'
				&& $this->business_hours['tuesday_open_minute'] == '00'
				&& $this->business_hours['tuesday_close_hour'] == '00'
				&& $this->business_hours['tuesday_close_minute'] == '00'
			)
			{
				return true;
			}
		}
		elseif ($day == 'wed')
		{
			if ($this->business_hours['wednesday_open_hour'] == '00'
				&& $this->business_hours['wednesday_open_minute'] == '00'
				&& $this->business_hours['wednesday_close_hour'] == '00'
				&& $this->business_hours['wednesday_close_minute'] == '00'
			)
			{
				return true;
			}
		}
		elseif ($day == 'thu')
		{
			if ($this->business_hours['thursday_open_hour'] == '00'
				&& $this->business_hours['thursday_open_minute'] == '00'
				&& $this->business_hours['thursday_close_hour'] == '00'
				&& $this->business_hours['thursday_close_minute'] == '00'
			)
			{
				return true;
			}
		}
		elseif ($day == 'fri')
		{
			if ($this->business_hours['friday_open_hour'] == '00'
				&& $this->business_hours['friday_open_minute'] == '00'
				&& $this->business_hours['friday_close_hour'] == '00'
				&& $this->business_hours['friday_close_minute'] == '00'
			)
			{
				return true;
			}
		}
		elseif ($day == 'sat')
		{
			if ($this->business_hours['saturday_open_hour'] == '00'
				&& $this->business_hours['saturday_open_minute'] == '00'
				&& $this->business_hours['saturday_close_hour'] == '00'
				&& $this->business_hours['saturday_close_minute'] == '00'
			)
			{
				return true;
			}
		}
		elseif ($day == 'sun')
		{
			if ($this->business_hours['sunday_open_hour'] == '00'
				&& $this->business_hours['sunday_open_minute'] == '00'
				&& $this->business_hours['sunday_close_hour'] == '00'
				&& $this->business_hours['sunday_close_minute'] == '00'
			)
			{
				return true;
			}
		}
	
		return false;
	}
	

	public function getHours()
	{
		$hours = [];
		for ($i = 0; $i < 24; $i++)
		{
			$hh = str_pad($i, 2, '0', STR_PAD_LEFT);
			$hours[$hh] = $hh;
		}
	
		return $hours;
	}
	
	public function getMinutes()
		{
		$minutes = [];
		for ($i = 0; $i < 60; $i += 5)
		{
			$mm = str_pad($i, 2, '0', STR_PAD_LEFT);
			$minutes[$mm] = $mm;
		}
	
		return $minutes;
	}
	
	public function getBreadcrumbs($includeSelf = true)
	{
		$breadcrumbs = $this->Category ? $this->Category->getBreadcrumbs() : [];
		if ($includeSelf)
		{
			$breadcrumbs[] = [
				'href' => $this->app()->router()->buildLink('showcase', $this),
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
	
	protected function getLdThumbnailUrl()
	{
		$thumbnailUrl = null;
	
		if ($this->CoverImage)
		{
			$thumbnailUrl = $this->CoverImage->getThumbnailUrlFull();
		}
		else if ($this->Category->content_image_url)
		{
			$thumbnailUrl = $this->Category->getCategoryContentImageThumbnailUrlFull();
		}
	
		return $thumbnailUrl;
	}
	
	public function getLdStructuredData(int $page = 1, array $extraData = []): array
	{
		$router = $this->app()->router('public');
		$templater = \XF::app()->templater();
	
		$output = [
			'@context'            => 'https://schema.org',
			'@type'               => 'CreativeWorkSeries',
			'@id'                 => $router->buildLink('canonical:showcase', $this),
			'name'                => $this->title,
			'headline'            => $this->meta_title ?: $this->title,
			'alternativeHeadline' => $this->og_title ?: $this->title,
			'description'         => $this->getLdSnippet($this->meta_description ?: $this->description ?: $this->message),
			"keywords"            => implode(', ', array_column($this->tags, 'tag')),
			'dateCreated'         => gmdate('c', $this->create_date),
			'dateModified'        => gmdate('c', $this->last_update),
			'author'              => [
				'@type' => 'Person',
				'name'  => $this->User->username ?: $this->username,
			]
		];
	
		if ($thumbnailUrl = $this->getLdThumbnailUrl())
		{
			$output['thumbnailUrl'] = $thumbnailUrl;
		}
	
		if ($this->rating_count)
		{
			$output['aggregateRating'] = [
				"@type"       => 'AggregateRating',
				"ratingCount" => $this->rating_count,
				"ratingValue" => $this->rating_avg,
			];
		}
	
		$output['interactionStatistic'] = [
			[
				'@type' => 'InteractionCounter',
				'interactionType' => 'https://schema.org/CommentAction',
				'userInteractionCount' => strval($this->comment_count)
			],
			[
				'@type' => 'InteractionCounter',
				'interactionType' => 'https://schema.org/LikeAction',
				'userInteractionCount' => strval($this->reaction_score)
			],
			[
				'@type' => 'InteractionCounter',
				'interactionType' => 'https://schema.org/ViewAction',
				'userInteractionCount' => strval($this->view_count)
			]
		];
	
		if ($this->hasViewableDiscussion())
		{
			$output['discussionUrl'] = $router->buildLink('canonical:threads', $this->Discussion);
		}
	
		return Arr::filterNull($output, true);
	}	
	
	
	
	

	public function getItemLocationForList()
	{
		$category = $this->Category;
	
		if (
			$category->allow_location
			&& $category->display_location_on_list
			&& $this->location_data
		)
		{
			if (
				$category->location_on_list_display_type == 'city_state'
				&& ($this->location_data['city'] || $this->location_data['state'])
			)
			{
				if ($this->location_data['city'] && !$this->location_data['state'])
				{
					return $this->location_data['city'];
				}
				else if ($this->location_data['state_short'] && !$this->location_data['city'])
				{
					return $this->location_data['state'];
				}
	
				return $this->location_data['city'] . ', ' . $this->location_data['state'];
			}
			else if (
				$category->location_on_list_display_type == 'city_state_short'
				&& ($this->location_data['city'] || $this->location_data['state_short'])
			)
			{
				if ($this->location_data['city'] && !$this->location_data['state_short'])
				{
					return $this->location_data['city'];
				}
				else if ($this->location_data['state_short'] && !$this->location_data['city'])
				{
					return $this->location_data['state_short'];
				}
	
				return $this->location_data['city'] . ', ' . $this->location_data['state_short'];
			}
			else if (
				$category->location_on_list_display_type == 'city_state_country'
				&& ($this->location_data['city'] || $this->location_data['state'] || $this->location_data['country'])
			)
			{
				if ($this->location_data['city'] && !$this->location_data['state'] && !$this->location_data['country'])
				{
					return $this->location_data['city'];
				}
				else if (!$this->location_data['city'] && $this->location_data['state'] && !$this->location_data['country'])
				{
					return $this->location_data['state'];
				}
				else if (!$this->location_data['city'] && !$this->location_data['state'] && $this->location_data['country'])
				{
					return $this->location_data['country'];
				}
				else if ($this->location_data['city'] && $this->location_data['state'] && !$this->location_data['country'])
				{
					return $this->location_data['city'] . ', ' . $this->location_data['state'];
				}
				else if ($this->location_data['city'] && !$this->location_data['state'] && $this->location_data['country'])
				{
					return $this->location_data['city'] . ', ' . $this->location_data['country'];
				}
				else if (!$this->location_data['city'] && $this->location_data['state'] && $this->location_data['country'])
				{
					return $this->location_data['state'] . ', ' . $this->location_data['country'];
				}
	
				return $this->location_data['city'] . ', ' . $this->location_data['state'] . ', ' . $this->location_data['country'];
			}
			else if (
				$category->location_on_list_display_type == 'city_state_country_short'
				&& ($this->location_data['city'] || $this->location_data['state'] || $this->location_data['country_short'])
			)
			{
				if ($this->location_data['city'] && !$this->location_data['state'] && !$this->location_data['country_short'])
				{
					return $this->location_data['city'];
				}
				else if (!$this->location_data['city'] && $this->location_data['state'] && !$this->location_data['country_short'])
				{
					return $this->location_data['state'];
				}
				else if (!$this->location_data['city'] && !$this->location_data['state'] && $this->location_data['country_short'])
				{
					return $this->location_data['country_short'];
				}
				else if ($this->location_data['city'] && $this->location_data['state'] && !$this->location_data['country_short'])
				{
					return $this->location_data['city'] . ', ' . $this->location_data['state'];
				}
				else if ($this->location_data['city'] && !$this->location_data['state'] && $this->location_data['country_short'])
				{
					return $this->location_data['city'] . ', ' . $this->location_data['country_short'];
				}
				else if (!$this->location_data['city'] && $this->location_data['state'] && $this->location_data['country_short'])
				{
					return $this->location_data['state'] . ', ' . $this->location_data['country_short'];
				}
	
				return $this->location_data['city'] . ', ' . $this->location_data['state'] . ', ' . $this->location_data['country_short'];
			}
			else if (
				$category->location_on_list_display_type == 'city_state_short_country_short'
				&& ($this->location_data['city'] || $this->location_data['state_short'] || $this->location_data['country_short'])
			)
			{
				if ($this->location_data['city'] && !$this->location_data['state_short'] && !$this->location_data['country_short'])
				{
					return $this->location_data['city'];
				}
				else if (!$this->location_data['city'] && $this->location_data['state_short'] && !$this->location_data['country_short'])
				{
					return $this->location_data['state_short'];
				}
				else if (!$this->location_data['city'] && !$this->location_data['state_short'] && $this->location_data['country_short'])
				{
					return $this->location_data['country_short'];
				}
				else if ($this->location_data['city'] && $this->location_data['state_short'] && !$this->location_data['country_short'])
				{
					return $this->location_data['city'] . ', ' . $this->location_data['state_short'];
				}
				else if ($this->location_data['city'] && !$this->location_data['state_short'] && $this->location_data['country_short'])
				{
					return $this->location_data['city'] . ', ' . $this->location_data['country_short'];
				}
				else if (!$this->location_data['city'] && $this->location_data['state_short'] && $this->location_data['country_short'])
				{
					return $this->location_data['state_short'] . ', ' . $this->location_data['country_short'];
				}
					
				return $this->location_data['city'] . ', ' . $this->location_data['state_short'] . ', ' . $this->location_data['country_short'];
			}
			else if (
				$category->location_on_list_display_type == 'formatted_address'
				&& $this->location_data['formatted_address']
			)
			{
				return $this->location_data['formatted_address'];
			}
			else
			{
				return '';
			}
		}
	
		return '';
	}
	
	public function getExtraFieldTabs()
	{
		if (!$this->getValue('custom_fields'))
		{
			return [];
		}

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $this->custom_fields;
		$definitionSet = $fieldSet->getDefinitionSet()
			->filterOnly($this->Category->field_cache)
			->filterGroup('new_tab')
			->filterWithValue($fieldSet);

		$output = [];
		foreach ($definitionSet AS $fieldId => $definition)
		{
			$output[$fieldId] = $definition->title;
		}

		return $output;
	}
	
	public function getExtraFieldSidebarBlocks()
	{
		if (!$this->getValue('custom_fields'))
		{
			return [];
		}
	
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $this->custom_fields;
		$definitionSet = $fieldSet->getDefinitionSet()
			->filterOnly($this->Category->field_cache)
			->filterGroup('new_sidebar_block')
			->filterWithValue($fieldSet);
	
		$output = [];
		foreach ($definitionSet AS $fieldId => $definition)
		{
			$output[$fieldId] = $definition->title;
		}
	
		return $output;
	}

	public function getExpectedThreadTitle($currentValues = true)
	{
		$title = $currentValues ? $this->getValue('title') : $this->getExistingValue('title');
		$state = $currentValues ? $this->getValue('item_state') : $this->getExistingValue('item_state');

		$template = '';
		$options = $this->app()->options();

		if($state == 'draft' || $state == 'awaiting')
		{
			$template = '{title} [' . \XF::phraseDeferred('xa_sc_item_awaiting_publishing') . ']';
		}
		elseif ($state != 'visible' && $options->xaScItemDeleteThreadAction['update_title'])
		{
			$template = $options->xaScItemDeleteThreadAction['title_template'];
		}
		
		if (!$template)
		{
			$template = '{title}';
		}

		$threadTitle = str_replace('{title}', $title, $template);
		return $this->app()->stringFormatter()->wholeWordTrim($threadTitle, 100);
	}

	/**
	 * @return \XF\Draft
	 */
	public function getDraftItem()
	{
		return \XF\Draft::createFromEntity($this, 'DraftItems');
	}
	
	public function getTrimmedItem()
	{
		$trimmedItem = null;
		
		if (!$this->canViewFullItem())
		{
			$snippet = $this->app()->stringFormatter()->wholeWordTrim($this->message, $this->app()->options()->xaScLimitedViewItemLength);
			$trimmedItem = $this->app()->bbCode()->render($snippet, 'bbCodeClean', 'sc_item', null);
		}
		
		return $trimmedItem;
	}
	
	public function getItemPages()
	{
		$itemPages = $this->em()->getEmptyCollection();
		
		if ($this->canViewFullItem() && $this->page_count)
		{
			/** @var \XenAddons\Showcase\Repository\ItemPage $itemPageRepo */
			$itemPageRepo = $this->repository('XenAddons\Showcase:ItemPage');
			$itemPages = $itemPageRepo->findPagesInItem($this)->with('full')->fetch();
			
			if ($itemPages)
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = \XF::repository('XF:Attachment');
				$attachmentRepo->addAttachmentsToContent($itemPages, 'sc_page');
			}
		}
		
		return $itemPages;
	}
	
	public function getFieldEditMode()
	{
		$visitor = \XF::visitor();

		$isContributor = ($this->isContributor() || !$this->item_id);
		$isMod = ($visitor->user_id && $this->hasPermission('editAny'));

		if ($isMod || !$isContributor)
		{
			return $isContributor ? 'moderator_user' : 'moderator';
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
		$fieldDefinitions = $this->app()->container('customFields.sc_items'); 

		return new \XF\CustomField\Set($fieldDefinitions, $this);
	}
	
	public function hasImageAttachments($item = false)
	{
		if (!$this->attach_count)
		{
			return false;
		}
		
		if ($item && $item['Attachments'])
		{
			$attachments = $item['Attachments'];
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
	
	public function getNewPage()
	{
		$page = $this->_em->create('XenAddons\Showcase:ItemPage');
		$page->item_id = $this->item_id;
	
		return $page;
	}
	
	public function getNewUpdate()
	{
		$update = $this->_em->create('XenAddons\Showcase:ItemUpdate');
		$update->item_id = $this->item_id;
	
		return $update;
	}
	
	public function getNewUpdateState()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
	
		if ($visitor->user_id && $this->hasPermission('approveUnapprove')) // piggyback off of item permissions
		{
			return 'visible';
		}
	
		if (!$this->hasPermission('submitWithoutApproval')) // piggyback off of item permissions
		{
			return 'moderated';
		}
	
		return 'visible';
	}
	
	public function getNewRating()
	{
		$rating = $this->_em->create('XenAddons\Showcase:ItemRating');
		$rating->item_id = $this->item_id;
	
		return $rating;
	}
	
	public function getNewRatingState()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
	
		if ($visitor->user_id && $this->hasPermission('approveUnapproveReview'))
		{
			return 'visible';
		}
	
		if (!$this->hasPermission('postReviewWithoutApproval'))
		{
			return 'moderated';
		}
	
		return 'visible';
	}
	
	public function getNextPage($itemPages, $itemPage = null)
	{
		if (!$itemPage)
		{
			foreach ($itemPages as $pageID => $page)
			{
				return $page;   // returns the first page since you are viewing the overview
			}
		}
		else
		{
			$thisisit = false;
	
			foreach ($itemPages as $pageID => $page)
			{
				if ($thisisit)
				{
					return $page;
				}
	
				if ($page['page_id'] == $itemPage->page_id)
				{
					$thisisit = true;
				}
			}
	
			return false;  // already viewing the last page, so there won't be a next page
		}
	}
	
	public function getPreviousPage($itemPages, $itemPage = null)
	{
		if (!$itemPage)
		{
			return false;  // already viewing the overview, so there is no previous page!
		}
		else
		{
			$thisIsCurrentPage = false;
			$previousPage = false;
	
			foreach ($itemPages as $pageID => $page)
			{
				// check to see if this is the page being viewed
				if ($page['page_id'] == $itemPage->page_id)
				{
					$thisIsCurrentPage = true;
	
					if ($previousPage)
					{
						return $previousPage;
					}
				}
	
				$previousPage = $page;
			}
	
			return false; // previous page is the item overview!
		}
	}
	
	public function getSeriesToc()
	{
		$seriesToc = $this->em()->getEmptyCollection();
	
		if ($this->isInSeries(true) && $this->canViewFullItem())
		{
			$seriesPartRepo = $this->repository('XenAddons\Showcase:SeriesPart');
	
			/** @var \XenAddons\Showcase\Repository\SeriesPart $seriesPartRepo */
			$seriesPartFinder = $seriesPartRepo->findPartsInSeries($this->SeriesPart->Series)->forTOC()->with('full');
			$seriesToc = $seriesPartFinder->fetch();
		}
	
		return $seriesToc;
	}
	
	public function getNextSeriesPart($seriesToc)
	{
		$thisisit = false;
	
		foreach ($seriesToc as $seriesPartId => $seriesPart)
		{
			if ($thisisit)
			{
				return $seriesPart;
			}
	
			if ($seriesPart['item_id'] == $this->item_id)
			{
				$thisisit = true;
			}
		}
	
		return false;  // already viewing the last series part, so there won't be a next series part!
	}
	
	public function getPreviousSeriesPart($seriesToc)
	{
		$thisIsCurrentSeriesPart = false;
		$previousSeriesPart = false;
	
		foreach ($seriesToc as $seriesPartId => $seriesPart)
		{
			// check to see if this is the series part being viewed
			if ($seriesPart['item_id'] == $this->item_id)
			{
				$thisIsCurrentSeriesPart = true;
					
				if ($previousSeriesPart)
				{
					return $previousSeriesPart;
				}
			}
	
			$previousSeriesPart = $seriesPart;
		}
	
		return false; // previous series part is the item being viewed!
	}

	public function addedToSeries(SeriesPart $seriesPart)
	{
		$this->series_part_id = $seriesPart->series_part_id;
	}
	
	public function removedFromSeries(SeriesPart $seriesPart)
	{
		$this->series_part_id = 0;
	}	
	
	
	public function pageAdded(ItemPage $page)
	{
		$this->page_count++;
		$this->last_update = \XF::$time;
	}
	
	public function pageRemoved(ItemPage $page)
	{
		$this->page_count--;
	}
	
	/**
	 * @return int
	 */
	public function getImageAttachCount()
	{
		$attachments = $this->Attachments;
	
		$imageAttachments = [];
		$fileAttachments = [];
	
		foreach ($attachments AS $key => $attachment)
		{
			if ($attachment['thumbnail_url'])
			{
				$imageAttachments[$key] = $attachment;
			}
			else
			{
				$fileAttachments[$key] = $attachment;
			}
		}
	
		return count($imageAttachments);
	}
	
	/**
	 * @return int
	 */
	public function getRealUpdateCount()
	{
		if (!$this->canViewDeletedUpdates() && !$this->canViewModeratedUpdates())
		{
			return $this->update_count;
		}
		else
		{
			/** @var \XenAddons\Showcase\Repository\Update $updateRepo */
			$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
			return $updateRepo->findUpdatesForItem($this)->total();
		}
	}

	/**
	 * @return int
	 */
	public function getRealReviewCount()
	{
		if (!$this->canViewDeletedReviews())
		{
			return $this->review_count;
		}
		else
		{
			/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
			$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
			return $ratingRepo->findReviewsInItem($this)->total();
		}
	}
	
	/**
	 * @return array
	 */
	public function getItemPageIds()
	{
		return $this->db()->fetchAllColumn("
			SELECT page_id
			FROM xf_xa_sc_item_page
			WHERE item_id = ?
			ORDER BY create_date
		", $this->item_id);
	}
	
	/**
	 * @return array
	 */
	public function getItemUpdateIds()
	{
		return $this->db()->fetchAllColumn("
			SELECT item_update_id
			FROM xf_xa_sc_item_update
			WHERE item_id = ?
			ORDER BY update_date
		", $this->item_id);
	}

	/**
	 * @return array
	 */
	public function getItemRatingIds()
	{
		return $this->db()->fetchAllColumn("
			SELECT rating_id
			FROM xf_xa_sc_item_rating
			WHERE item_id = ?
			ORDER BY rating_date
		", $this->item_id);
	}

	public function rebuildCounters()
	{
		$this->rebuildCommentCount();
		$this->rebuildLastCommentInfo();
		$this->rebuildUpdateCount();
		$this->rebuildPageCount();
		$this->rebuildReviewCount();
		$this->rebuildRating();

		return true;
	}
	
	public function rebuildUpdateCount()
	{
		$this->update_count = $this->db()->fetchOne("
			SELECT COUNT(*)
				FROM xf_xa_sc_item_update
				WHERE item_id = ?
					AND update_state = 'visible'
		", $this->item_id);
	
		return $this->update_count;
	}
	
	public function rebuildPageCount()
	{
		$this->page_count = $this->db()->fetchOne("
			SELECT COUNT(*)
				FROM xf_xa_sc_item_page
				WHERE item_id = ?
					AND page_state = 'visible'
		", $this->item_id);
	
		return $this->page_count;
	}

	public function rebuildReviewCount()
	{
		$this->review_count = $this->db()->fetchOne("
			SELECT COUNT(*)
				FROM xf_xa_sc_item_rating
				WHERE item_id = ?
					AND is_review = 1
					AND rating_state = 'visible'
		", $this->item_id);

		return $this->review_count;
	}

	public function rebuildRating()
	{
		$rating = $this->db()->fetchRow("
			SELECT COUNT(*) AS total,
				SUM(rating) AS sum
			FROM xf_xa_sc_item_rating
			WHERE item_id = ?
				AND rating_state = 'visible'
		", $this->item_id);

		$this->rating_sum = $rating['sum'] ?: 0;
		$this->rating_count = $rating['total'] ?: 0;
	}
	
	public function rebuildLocationData()
	{
		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->repository('XenAddons\Showcase:Item');
		
		$this->location_data = $itemRepo->getLocationDataFromGoogleMapsGeocodingApi($this->location, 'rebuild');
	}	

	public function updateRatingAverage()
	{
		$threshold = self::RATING_WEIGHTED_THRESHOLD;
		$average = self::RATING_WEIGHTED_AVERAGE;

		$this->rating_weighted = ($threshold * $average + $this->rating_sum) / ($threshold + $this->rating_count);

		if ($this->rating_count)
		{
			$this->rating_avg = $this->rating_sum / $this->rating_count;
		}
		else
		{
			$this->rating_avg = 0;
		}
	}
	
	public function updateCoverImageIfNeeded()
	{
		$attachments = $this->Attachments;
		
		$imageAttachments = [];
		$fileAttachments = [];

		foreach ($attachments AS $key => $attachment)
		{
			if ($attachment['thumbnail_url'])
			{
				$imageAttachments[$key] = $attachment;
			}
			else 
			{
				$fileAttachments[$key] = $attachment;
			}
		}
		
		if (!$this->cover_image_id)
		{
			if ($imageAttachments)
			{	
				foreach ($imageAttachments AS $imageAttachment)
				{
					$coverImageId = $imageAttachment['attachment_id'];
					break;
				}
				
				if ($coverImageId)
				{
					$this->db()->query("
						UPDATE xf_xa_sc_item
						SET cover_image_id = ?
						WHERE item_id = ?
					", [$coverImageId, $this->item_id]);
				}	
			}	
		}
		elseif ($this->cover_image_id)
		{
			if (!$imageAttachments || !$imageAttachments[$this->cover_image_id])
			{
				$this->db()->query("
					UPDATE xf_xa_sc_item
					SET cover_image_id = 0
					WHERE item_id = ?
				", $this->item_id);
			}
		}		
		
	}
	
	public function getBbCodeRenderOptions($context, $type)
	{
		return [
			'entity' => $this,
			'user' => $this->User,
			'attachments' => $this->attach_count ? $this->Attachments : [],
			'viewAttachments' => $this->canViewItemAttachments()
		];
	}	

	protected function _preSave()
	{
		if ($this->prefix_id && $this->isChanged(['prefix_id', 'category_id']))
		{
			if (!$this->Category->isPrefixValid($this->prefix_id))
			{
				$this->prefix_id = 0;
			}
		}

		if ($this->isInsert() || $this->isChanged(['rating_sum', 'rating_count']))
		{
			$this->updateRatingAverage();
		}
	}

	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('item_state', 'visible');
		$approvalChange = $this->isStateChanged('item_state', 'moderated');
		$deletionChange = $this->isStateChanged('item_state', 'deleted');
		$draftChange = $this->isStateChanged('item_state', 'draft');
		$awaitingPublishmentChange = $this->isStateChanged('item_state', 'awaiting');
		
		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->itemMadeVisible();

				if ($approvalChange)
				{
					$this->submitHamData();
				}
			}
			else if ($visibilityChange == 'leave')
			{
				$this->itemHidden();
			}

			if ($this->isChanged('category_id'))
			{
				$oldCategory = $this->getExistingRelation('Category');
				if ($oldCategory && $this->Category)
				{
					$this->itemMoved($oldCategory, $this->Category);
				}
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
			if ($this->item_state == 'visible')
			{
				$this->itemInsertedVisible();
			}
			
			if ($this->item_state == 'draft')
			{
				$this->itemInsertedDraft();
			}
				
			if ($this->item_state == 'awaiting')
			{
				$this->itemInsertedAwaiting();
			}
		}

		if ($this->isUpdate())
		{
			if ($this->isChanged('user_id'))
			{
				$this->itemReassigned();
			}

			if ($this->isChanged('discussion_thread_id'))
			{
				if ($this->getExistingValue('discussion_thread_id'))
				{
					/** @var \XF\Entity\Thread $oldDiscussion */
					$oldDiscussion = $this->getExistingRelation('Discussion');
					if ($oldDiscussion && $oldDiscussion->discussion_type == 'sc_item')
					{
						$oldDiscussion->discussion_type = '';
						$oldDiscussion->save(false, false);
					}
				}
					
				if (
					$this->discussion_thread_id 
					&& $this->Discussion 
					&& $this->Discussion->discussion_type === \XF\ThreadType\AbstractHandler::BASIC_THREAD_TYPE
				)
				{
					$this->Discussion->discussion_type = 'sc_item';
					$this->Discussion->save(false, false);
				}
			}
			
			if ($this->discussion_thread_id)
			{
				$newThreadTitle = $this->getExpectedThreadTitle(true);
				if (
					$newThreadTitle != $this->getExpectedThreadTitle(false)
					&& $this->Discussion
					&& $this->Discussion->discussion_type == 'sc_item')
				{
					$this->Discussion->title = $newThreadTitle;
					$this->Discussion->saveIfChanged($saved, false, false);
				}
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

		$this->updateCategoryRecord();

		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('sc_item', $this);
		}
		
		$this->_postSaveBookmarks();
	}

	protected function itemMadeVisible()
	{
		$this->adjustUserItemCountIfNeeded(1);

		if ($this->discussion_thread_id && $this->Discussion && $this->Discussion->discussion_type == 'sc_item')
		{
			$thread = $this->Discussion;

			switch ($this->app()->options()->xaScItemDeleteThreadAction['action'])
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
		$reactionRepo->recalculateReactionIsCounted('sc_item', $this->item_id);
	}

	protected function itemHidden($hardDelete = false)
	{
		$this->adjustUserItemCountIfNeeded(-1);

		if ($this->discussion_thread_id && $this->Discussion && $this->Discussion->discussion_type == 'sc_item')
		{
			$thread = $this->Discussion;

			switch ($this->app()->options()->xaScItemDeleteThreadAction['action'])
			{
				case 'delete':
					$thread->discussion_state = 'deleted';
					break;

				case 'close':
					$thread->discussion_open = false;
					break;
			}
			
			if ($hardDelete)
			{
				$thread->discussion_type == 'discussion'; // unassociate from Showcase and set as a discussion thread type!
			}

			$thread->title = $this->getExpectedThreadTitle();
			$thread->saveIfChanged($saved, false, false);
		}

		if (!$hardDelete)
		{
			// on hard delete the reactions will be removed which will do this
			/** @var \XF\Repository\Reaction $reactionRepo */
			$reactionRepo = $this->repository('XF:Reaction');
			$reactionRepo->recalculateReactionIsCounted('sc_item', $this->item_id, false);
		}
		
		// TODO testing this to see if this helps resolve the issue of cached Read Marking of deleted items (at least for the viewing user that performs the delete action)
		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->repository('XenAddons\Showcase:Item');
		$itemRepo->markItemReadByVisitor($this);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('sc_item', $this->item_id);
		$alertRepo->fastDeleteAlertsForContent('sc_update', $this->item_update_ids);
		$alertRepo->fastDeleteAlertsForContent('sc_rating', $this->item_rating_ids);
	}

	protected function itemInsertedVisible()
	{
		$this->adjustUserItemCountIfNeeded(1);
	}
	
	protected function itemInsertedDraft()
	{
		// TODO any actions needed to be performed when initially saving a Draft Item!
	}
	
	protected function itemInsertedAwaiting()
	{
		// TODO any actions needed to be performed when initially saving a Awaiting Publishing (delayed publishing) Item!
	}

	protected function submitHamData()
	{
		/** @var \XF\Spam\ContentChecker $submitter */
		$submitter = $this->app()->container('spam.contentHamSubmitter');
		$submitter->submitHam('sc_item', $this->item_id);
	}

	protected function itemMoved(Category $from, Category $to)
	{
	}

	protected function itemReassigned()
	{
		if ($this->item_state == 'visible')
		{
			$this->adjustUserItemCountIfNeeded(-1, $this->getExistingValue('user_id'));
			$this->adjustUserItemCountIfNeeded(1);
		}
	}

	protected function adjustUserItemCountIfNeeded($amount, $userId = null)
	{
		if ($userId === null)
		{
			$userId = $this->user_id;
		}

		if ($userId)
		{
			$this->db()->query("
				UPDATE xf_user
				SET xa_sc_item_count = GREATEST(0, xa_sc_item_count + ?)
				WHERE user_id = ?
			", [$amount, $userId]);
		}
	}

	protected function updateCategoryRecord()
	{
		if (!$this->Category)
		{
			return;
		}

		$category = $this->Category;

		if ($this->isUpdate() && $this->isChanged('category_id'))
		{
			// moved, trumps the rest
			if ($this->item_state == 'visible')
			{
				$category->itemAdded($this);
				$category->save();
			}

			if ($this->getExistingValue('item_state') == 'visible')
			{
				/** @var Category $oldCategory */
				$oldCategory = $this->getExistingRelation('Category');
				if ($oldCategory)
				{
					$oldCategory->itemRemoved($this);
					$oldCategory->save();
				}
			}

			if ($this->discussion_thread_id && $this->Discussion && $this->Discussion->discussion_type == 'sc_item')
			{
				$thread = $this->Discussion;
				if ($category->thread_node_id)
				{
					$thread->node_id = $category->thread_node_id;
					$thread->prefix_id = $category->thread_prefix_id;
					if ($this->item_state == 'visible' && $thread->discussion_state == 'deleted')
					{
						// presumably the thread was soft deleted by being moved to a category without a thread
						$thread->discussion_state = 'visible';
					}
				}

				$thread->saveIfChanged($saved, false, false);
			}

			return;
		}

		// check for entering/leaving visible
		$visibilityChange = $this->isStateChanged('item_state', 'visible');
		if ($visibilityChange == 'enter' && $category)
		{
			$category->itemAdded($this);
			$category->save();
		}
		else if ($visibilityChange == 'leave' && $category)
		{
			$category->itemRemoved($this);
			$category->save();
		}
		else if ($this->isUpdate() && $this->item_state == 'visible')
		{
			$category->itemDataChanged($this);
			$category->save();
		}
	}

	protected function _postDelete()
	{
		if ($this->item_state == 'visible')
		{
			$this->itemHidden(true);
		}

		if ($this->Category && $this->item_state == 'visible')
		{
			$this->Category->itemRemoved($this);
			$this->Category->save();
		}

		if ($this->item_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}

		if ($this->item_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}
		
		if ($this->has_poll && $this->Poll)
		{
			$this->Poll->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_item', $this, 'delete_hard');
		}

		$db = $this->db();

		$db->delete('xf_xa_sc_comment_read', 'item_id = ?', $this->item_id);
		
		$db->delete('xf_xa_sc_item_feature', 'item_id = ?', $this->item_id);
		$db->delete('xf_xa_sc_item_field_value', 'item_id = ?', $this->item_id);
		$db->delete('xf_xa_sc_item_read', 'item_id = ?', $this->item_id);
		$db->delete('xf_xa_sc_item_reply_ban', 'item_id = ?', $this->item_id);
		$db->delete('xf_xa_sc_item_watch', 'item_id = ?', $this->item_id);
		
		$db->delete('xf_approval_queue', 'content_id = ? AND content_type = ?', [$this->item_id, 'sc_item']);
		$db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->item_id, 'sc_item']);
		$db->delete('xf_edit_history', 'content_id = ? AND content_type = ?', [$this->item_id, 'sc_item']);
		
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_item', $this->item_id);
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('sc_item', $this->item_id);
		
		$this->_postDeleteComments();
		
		$pageIds = $this->item_page_ids;
		if ($pageIds)
		{
			$this->_postDeletePages($pageIds);
		}
		
		$updateIds = $this->item_update_ids;
		if ($updateIds)
		{
			$this->_postDeleteUpdates($updateIds);
		}
		
		$ratingIds = $this->item_rating_ids;
		if ($ratingIds)
		{
			$this->_postDeleteRatings($ratingIds);
		}
		
		if ($this->series_part_id)
		{
			/** @var \XenAddons\Showcase\Entity\SeriesPart $seriesPart */
			$seriesPart = $this->em()->find('XenAddons\Showcase:SeriesPart', $this->series_part_id);
			if($seriesPart)
			{
				if($seriesPart->Series)
				{
					$seriesPart->Series->partRemoved($seriesPart);
					$seriesPart->Series->save();
				}
		
				$db->delete('xf_xa_sc_series_part', 'series_part_id = ?', $this->series_part_id);
			}
		}
		
		$this->_postDeleteBookmarks();
	}
	
	protected function _postDeletePages(array $pageIds)
	{
		$db = $this->db();
	
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_page', $pageIds);
	
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('sc_page', $pageIds);
		
		$db->delete('xf_xa_sc_item_page', 'page_id IN (' . $db->quote($pageIds) . ')');
	
		$db->delete('xf_deletion_log', 'content_id IN (' . $db->quote($pageIds) . ') AND content_type = ?', 'sc_page');
		$db->delete('xf_edit_history', 'content_id IN (' . $db->quote($pageIds) . ') AND content_type = ?', 'sc_page');
	}
	
	protected function _postDeleteUpdates(array $updateIds)
	{
		$db = $this->db();
	
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_update', $updateIds);
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('sc_update', $updateIds);
		
		$db->delete('xf_xa_sc_item_update', 'item_update_id IN (' . $db->quote($updateIds) . ')');
		$db->delete('xf_xa_sc_item_update_reply', 'item_update_id IN (' . $db->quote($updateIds) . ')');
		$db->delete('xf_xa_sc_update_field_value', 'item_update_id IN (' . $db->quote($updateIds) . ')');
	
		$db->delete('xf_approval_queue', 'content_id IN (' . $db->quote($updateIds) . ') AND content_type = ?', 'sc_update');
		$db->delete('xf_deletion_log', 'content_id IN (' . $db->quote($updateIds) . ') AND content_type = ?', 'sc_update');
		$db->delete('xf_edit_history', 'content_id IN (' . $db->quote($updateIds) . ') AND content_type = ?', 'sc_update');
	}
	
	protected function _postDeleteRatings(array $ratingIds)
	{
		$db = $this->db();
		
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_rating', $ratingIds);
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('sc_rating', $ratingIds);
		
		$db->delete('xf_xa_sc_item_rating', 'rating_id IN (' . $db->quote($ratingIds) . ')');
		$db->delete('xf_xa_sc_item_rating_reply', 'rating_id IN (' . $db->quote($ratingIds) . ')');
		$db->delete('xf_xa_sc_review_field_value', 'rating_id IN (' . $db->quote($ratingIds) . ')');
		
		$db->delete('xf_approval_queue', 'content_id IN (' . $db->quote($ratingIds) . ') AND content_type = ?', 'sc_rating');
		$db->delete('xf_deletion_log', 'content_id IN (' . $db->quote($ratingIds) . ') AND content_type = ?', 'sc_rating');
		$db->delete('xf_edit_history', 'content_id IN (' . $db->quote($ratingIds) . ') AND content_type = ?', 'sc_rating');
	}

	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();

		if ($this->item_state == 'deleted')
		{
			return false;
		}

		$this->item_state = 'deleted';

		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;

		$this->save();

		return true;
	}
	
	public function rebuildItemFieldValuesCache()
	{
		$this->repository('XenAddons\Showcase:ItemField')->rebuildItemFieldValuesCache($this->item_id);
	}
	
	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
	{
		$route = ($canonical ? 'canonical:' : '') . 'showcase';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}
	
	public function getContentPublicRoute()
	{
		return 'showcase';
	}
	
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('xa_sc_item_x', [
			'title' => $this->title
		]);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item';
		$structure->shortName = 'XenAddons\Showcase:Item';
		$structure->primaryKey = 'item_id';
		$structure->contentType = 'sc_item';
		
		$structure->columns = [
			'item_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],  
			'category_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'username' => ['type' => self::STR, 'maxLength' => 50,
				'required' => 'please_enter_valid_name'
			],	
			'contributor_user_ids' => ['type' => self::LIST_COMMA, 'default' => [],
				'list' => ['type' => 'posint', 'unique' => true]
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
			'description' => ['type' => self::STR, 'maxLength' => 255,
				'default' => '',
				'censor' => true
			],
			'meta_description' => ['type' => self::STR, 'maxLength' => 320,
				'default' => '',
				'censor' => true
			],
			'item_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted', 'awaiting', 'draft']
			],
			'sticky' => ['type' => self::BOOL, 'default' => false],
			'message' => ['type' => self::STR, 'default' => ''],
			'message_s2' => ['type' => self::STR, 'default' => ''],
			'message_s3' => ['type' => self::STR, 'default' => ''],
			'message_s4' => ['type' => self::STR, 'default' => ''],
			'message_s5' => ['type' => self::STR, 'default' => ''],
			'message_s6' => ['type' => self::STR, 'default' => ''],
			'create_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'last_update' => ['type' => self::UINT, 'default' => \XF::$time],
			'last_feature_date' => ['type' => self::UINT, 'default' => 0],
			'edit_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'attach_count' => ['type' => self::UINT, 'default' => 0],
			'view_count' => ['type' => self::UINT, 'default' => 0],
			'watch_count' => ['type' => self::UINT, 'default' => 0],
			'update_count' => ['type' => self::UINT, 'default' => 0],
			'page_count' => ['type' => self::UINT, 'default' => 0],
			'rating_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
			'rating_sum' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
			'rating_avg' => ['type' => self::FLOAT, 'default' => 0],
			'rating_weighted' => ['type' => self::FLOAT, 'default' => 0],
			'review_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
			'comment_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
			'cover_image_id' => ['type' => self::UINT, 'default' => 0],	
			'discussion_thread_id' => ['type' => self::UINT, 'default' => 0],	
			'custom_fields' => ['type' => self::JSON_ARRAY, 'default' => []],
			'prefix_id' => ['type' => self::UINT, 'default' => 0],
			'last_review_date' => ['type' => self::UINT, 'default' => 0],
			'author_rating' => ['type' => self::FLOAT, 'default' => 0],
			'tags' => ['type' => self::JSON_ARRAY, 'default' => []],
			'has_poll' => ['type' => self::BOOL, 'default' => false],
			'comments_open' => ['type' => self::BOOL, 'default' => true],
			'ratings_open' => ['type' => self::BOOL, 'default' => true],
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
			'edit_count' => ['type' => self::UINT, 'default' => 0],
			'location' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'location_data' => ['type' => self::JSON_ARRAY, 'default' => []],
			'business_hours' => ['type' => self::JSON_ARRAY, 'default' => []],
			'series_part_id' => ['type' => self::UINT, 'default' => 0],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
		];
		$structure->getters = [
			'custom_fields' => true,
			'real_comment_count' => true,
			'real_review_count' => true,
			'real_update_count' => true,
			'image_attach_count' => true,
			'item_page_ids' => true,
			'item_update_ids' => true,
			'item_rating_ids' => true,
			'draft_item' => true,
		];
		$structure->behaviors = [
			'XF:Reactable' => ['stateField' => 'item_state'],
			'XF:ReactableContainer' => [
				'childContentType' => 'sc_comment',
				'childIds' => function($item) { return $item->comment_ids; },
				'stateField' => 'item_state'
			],
			'XF:Taggable' => ['stateField' => 'item_state'],
			'XF:Indexable' => [
				'checkForUpdates' => ['title', 'og_title', 'meta_title', 'description', 'meta_description', 'message', 'message_s2', 'message_s3', 'message_s4', 'message_s5', 'message_s6', 'category_id', 'user_id', 'prefix_id', 'tags', 'item_state']
			],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField' => 'create_date'
			],
			'XF:CustomFieldsHolder' => [
				'valueTable' => 'xf_xa_sc_item_field_value',
				'checkForUpdates' => ['category_id'],
				'getAllowedFields' => function($item) { return $item->Category ? $item->Category->field_cache : []; }
			],
			'XF:ContentVotableContainer' => [
				'childContentType' => 'sc_rating',
				'childIds' => function($item) { return $item->item_rating_ids; },
				'stateField' => 'item_state'
			],
		];
		$structure->relations = [
			'Category' => [
				'entity' => 'XenAddons\Showcase:Category',
				'type' => self::TO_ONE,
				'conditions' => 'category_id',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Contributors' => [
				'entity' => 'XenAddons\Showcase:ItemContributor',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'user_id',
				'with' => ['User','User.Profile'],
				'order' => 'User.username'
			],
			'Pages' => [
				'entity' => 'XenAddons\Showcase:ItemPage',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'page_id'
			],
			'Updates' => [
				'entity' => 'XenAddons\Showcase:ItemUpdate',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'user_id'
			],
			'Ratings' => [
				'entity' => 'XenAddons\Showcase:ItemRating',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'user_id'
			],
			'CoverImage' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_item'],
					['content_id', '=', '$item_id'],
					['attachment_id', '=', '$cover_image_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],			
			'Attachments' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'sc_item'],
					['content_id', '=', '$item_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],
			'Read' => [
				'entity' => 'XenAddons\Showcase:ItemRead',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'user_id'
			],
			'Discussion' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => [['thread_id', '=', '$discussion_thread_id']],
				'primary' => true
			],
			'Featured' => [
				'entity' => 'XenAddons\Showcase:ItemFeature',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			],
			'SeriesPart' => [
				'entity' => 'XenAddons\Showcase:SeriesPart',
				'type' => self::TO_ONE,
				'conditions' => 'series_part_id',
				'primary' => true
			],
			'Prefix' => [
				'entity' => 'XenAddons\Showcase:ItemPrefix',
				'type' => self::TO_ONE,
				'conditions' => 'prefix_id',
				'primary' => true
			],
			'Watch' => [
				'entity' => 'XenAddons\Showcase:ItemWatch',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'user_id'
			],
			'ReplyBans' => [
				'entity' => 'XenAddons\Showcase:ItemReplyBan',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'user_id'
			],
			'Poll' => [
				'entity' => 'XF:Poll',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_item'],
					['content_id', '=', '$item_id']
				]
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_item'],
					['content_id', '=', '$item_id']
				],
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_item'],
					['content_id', '=', '$item_id']
				],
				'primary' => true
			],
			'DraftItems' => [
				'entity'     => 'XF:Draft',
				'type'       => self::TO_MANY,
				'conditions' => [
					['draft_key', '=', 'xa-sc-item-', '$item_id']
				],
				'key' => 'user_id'
			],
			'CustomFields' => [
				'entity' => 'XenAddons\Showcase:ItemFieldValue',
				'type' => self::TO_MANY,
				'conditions' => 'item_id',
				'key' => 'field_id'
			],
			'Tags' => [
				'entity' => 'XF:TagContent',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'sc_item'],
					['content_id', '=', '$item_id']
				],
				'key' => 'tag_id'
			]
		];
		$structure->defaultWith = [
			'User'
		];
		$structure->options = [
			'log_moderator' => true
		];
		$structure->withAliases = [
			'full' => [
				'User',
				'Featured',
				'CoverImage',
				function()
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return [
							'Read|' . $userId, 
							'Watch|' . $userId,
							'Reactions|' . $userId,
							'Bookmarks|' . $userId
						];
					}
				
					return null;
				}
			],
			'fullCategory' => [
				'full',
				function()
				{
					$with = ['Category'];
				
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						$with[] = 'Category.Watch|' . $userId;
					}
				
					return $with;
				}
			]
		];
		
		static::addCommentableStructureElements($structure);
		static::addReactableStructureElements($structure);
		static::addBookmarkableStructureElements($structure);
		
		return $structure;
	}
}