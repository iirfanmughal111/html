<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $reply_id
 * @property int $rating_id
 * @property int $user_id
 * @property string $username
 * @property int $reply_date
 * @property string $reply_state
 * @property string $message
 * @property int $warning_id
 * @property string $warning_message
 * @property int $ip_id
 * @property array|null $embed_metadata
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 * 
 * GETTERS
 * @property mixed $Unfurls
 * @property mixed $reactions
 * @property mixed $reaction_users
 * 
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \XenAddons\Showcase\Entity\ItemRating $ItemRating
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 */
class ItemRatingReply extends Entity implements \XF\BbCode\RenderableContentInterface, \XF\Entity\LinkableInterface
{
	use ReactionTrait;
	
	public function canView(&$error = null)
	{
		$visitor = \XF::visitor();

		/** @var \XenAddons\Showcase\Entity\ItemRating $itemRating */
		$itemRating = $this->ItemRating;
		if (!$itemRating)
		{
			return false;
		}

		if ($this->reply_state == 'moderated')
		{
			if (
				!$itemRating->canViewModeratedReplies()
				&& (!$visitor->user_id || $visitor->user_id != $this->user_id)
			)
			{
				$error = \XF::phraseDeferred('xa_sc_requested_reply_not_found');
				return false;
			}
		}
		else if ($this->reply_state == 'deleted')
		{
			if (!$itemRating->canViewDeletedReplies())
			{
				$error = \XF::phraseDeferred('xa_sc_requested_reply_not_found');
				return false;
			}
		}

		return $itemRating->canView($error);
	}

	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return false;
		}
		
		/** @var \XenAddons\Showcase\Entity\ItemRating $review */
		$review = $this->ItemRating;
		
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $this->ItemRating->Item;
		
		if($item->hasPermission('editAnyReview'))
		{
			return true;
		}

		if ($this->user_id == $visitor->user_id && $item->hasPermission('editReview'))
		{
			$editLimit = $item->hasPermission('editOwnReviewTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->reply_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_edit_this_review_reply_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return false;
	}

	public function canChangeDate(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if (!$visitor->user_id)
		{
			return false;
		}
	
		// TODO maybe expend this to have its own permission?
		return $visitor->hasPermission('xa_showcase', 'editAnyReview');
	}
	
	public function canReassign(&$error = null)
	{
		$visitor = \XF::visitor();
	
		$itemRating = $this->ItemRating;
	
		return (
			$visitor->user_id
			&& $itemRating->canReassign()
		);
	}
	
	public function canDelete($type = 'soft', &$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		/** @var \XenAddons\Showcase\Entity\ItemRating $review */
		$review = $this->ItemRating;
		
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $this->ItemRating->Item;

		if ($type != 'soft' && !$item->hasPermission('hardDeleteAnyReview'))
		{
			return false;
		}

		if($item->hasPermission('deleteAnyReview'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id && $item->hasPermission('deleteReview'))
		{
			$editLimit = $item->hasPermission('editOwnReviewTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->reply_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_delete_this_review_reply_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return false;		
	}

	public function canUndelete(&$error = null)
	{
		$visitor = \XF::visitor();
		return ($visitor->user_id && $visitor->hasPermission('xa_showcase', 'undeleteReview'));
	}

	public function canApproveUnapprove(&$error = null)
	{
		$visitor = \XF::visitor();
		return ($visitor->user_id && $visitor->hasPermission('xa_showcase', 'approveUnapproveReview'));
	}

	public function canWarn(&$error = null)
	{
		$visitor = \XF::visitor();

		if ($this->warning_id
			|| !$this->user_id
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$visitor->hasPermission('xa_showcase', 'warnReview')
		)
		{
			return false;
		}

		return ($this->User && $this->User->isWarnable());
	}

	public function canReport(&$error = null, \XF\Entity\User $asUser = null)
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}

	public function canReact(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->reply_state != 'visible')
		{
			return false;
		}

		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}

		return $visitor->hasPermission('xa_showcase', 'reactReview');
	}

	public function canSendModeratorActionAlert()
	{
		$visitor = \XF::visitor();
		
		if (!$visitor->user_id || $visitor->user_id == $this->user_id)
		{
			return false;
		}
		
		return $this->isVisible();
	}

	public function isVisible()
	{
		return (
			$this->reply_state == 'visible'
			&& $this->ItemRating
			&& $this->ItemRating->rating_state == 'visible'
		);
	}

	public function isIgnored()
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}

	public function isLastReply()
	{
		return (
			$this->ItemRating
			&& $this->ItemRating->last_reply_date == $this->reply_date
		);
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

	public function canCleanSpam()
	{
		return (\XF::visitor()->canCleanSpam() && $this->User && $this->User->isPossibleSpammer());
	}
	
	public function getBbCodeRenderOptions($context, $type)
	{
		return [
			'entity' => $this,
			'user' => $this->User,
			'treatAsStructuredText' => true,
			'unfurls' => $this->Unfurls ?: []
		];
	}
	
	public function getUnfurls()
	{
		return isset($this->_getterCache['Unfurls']) ? $this->_getterCache['Unfurls'] : [];
	}
	
	public function setUnfurls($unfurls)
	{
		$this->_getterCache['Unfurls'] = $unfurls;
	}

	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('reply_state', 'visible');
		$approvalChange = $this->isStateChanged('reply_state', 'moderated');
		$deletionChange = $this->isStateChanged('reply_state', 'deleted');

		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->replyMadeVisible();

				if ($approvalChange)
				{
					$this->submitHamData();
				}
			}
			else if ($visibilityChange == 'leave')
			{
				$this->replyHidden();
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

		if ($approvalChange == 'enter')
		{
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->reply_date;
			$approvalQueue->save();
		}
		else if ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}

		$this->updateItemRatingRecord();

		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('sc_rating_reply', $this);
		}
	}

	protected function replyMadeVisible()
	{
	}

	protected function replyHidden($hardDelete = false)
	{
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('sc_rating_reply', $this->reply_id);
	}

	protected function updateItemRatingRecord()
	{
		if (!$this->ItemRating || !$this->ItemRating->exists())
		{
			return;
		}

		$visibilityChange = $this->isStateChanged('reply_state', 'visible');
		if ($visibilityChange == 'enter')
		{
			$this->ItemRating->replyAdded($this);
			$this->ItemRating->save();
		}
		else if ($visibilityChange == 'leave')
		{
			$this->ItemRating->replyRemoved($this);
			$this->ItemRating->save();
		}
	}

	protected function submitHamData()
	{
		/** @var \XF\Spam\ContentChecker $submitter */
		$submitter = $this->app()->container('spam.contentHamSubmitter');
		$submitter->submitHam('sc_rating_reply', $this->reply_id);
	}

	protected function _postDelete()
	{
		if ($this->reply_state == 'visible')
		{
			$this->replyHidden(true);
		}

		if ($this->ItemRating && $this->reply_state == 'visible')
		{
			$this->ItemRating->replyRemoved($this);
			$this->ItemRating->save();
		}

		if ($this->reply_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}

		if ($this->reply_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_rating_reply', $this, 'delete_hard');
		}
	}

	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();

		if ($this->reply_state == 'deleted')
		{
			return false;
		}

		$this->reply_state = 'deleted';

		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;

		$this->save();

		return true;
	}

	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
	{
		$route = ($canonical ? 'canonical:' : '') . 'showcase/review-reply';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}
	
	public function getContentPublicRoute()
	{
		return 'showcase/review-reply';
	}
	
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('xa_sc_item_review_reply_in_x', [
			'title' => $this->ItemRating->Item->title
		]);
	}
	
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_rating_reply';
		$structure->shortName = 'XenAddons\Showcase:ItemRatingReply';
		$structure->contentType = 'sc_rating_reply';
		$structure->primaryKey = 'reply_id';
		$structure->columns = [
			'reply_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'rating_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'username' => ['type' => self::STR, 'maxLength' => 50,
				'required' => 'please_enter_valid_name'
			],
			'reply_date' => ['type' => self::UINT, 'required' => true, 'default' => \XF::$time],
			'message' => ['type' => self::STR,
				'required' => 'please_enter_valid_message'
			],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'reply_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted']
			],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
		];
		$structure->behaviors = [
			'XF:Reactable' => ['stateField' => 'reply_state'],
			'XF:Indexable' => [
				'checkForUpdates' => ['message', 'user_id', 'reply_date', 'reply_state']
			],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField' => 'reply_date'
			]
		];
		$structure->getters = [
			'Unfurls' => true
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'ItemRating' => [
				'entity' => 'XenAddons\Showcase:ItemRating',
				'type' => self::TO_ONE,
				'conditions' => 'rating_id',
				'primary' => true
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_rating_reply'],
					['content_id', '=', '$reply_id']
				],
				'primary' => true
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_rating_reply'],
					['content_id', '=', '$reply_id']
				],
				'primary' => true
			]
		];
		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = ['ItemRating'];

		$structure->withAliases = [
			'full' => [
				'User',
				function()
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return ['Reactions|' . $userId];
					}
				}
			]
		];
		
		static::addReactableStructureElements($structure);
		
		return $structure;
	}
}