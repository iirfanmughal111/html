<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\LinkableInterface;
use XF\Entity\QuotableInterface;
use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $comment_id
 * @property int $item_id
 * @property string $message
 * @property int $user_id
 * @property string $username
 * @property int $ip_id
 * @property int $comment_date
 * @property string $comment_state
 * @property int $warning_id
 * @property string $warning_message
 * @property int $attach_count
 * @property int $last_edit_date
 * @property int $last_edit_user_id
 * @property int $edit_count
 * @property array|null $embed_metadata
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 * 
 * GETTERS
 * @property Item $Content
 * @property mixed $reactions
 * @property mixed $reaction_users
 * 
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\User $User
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 */
class Comment extends Entity implements LinkableInterface, QuotableInterface, \XF\BbCode\RenderableContentInterface
{
	use ReactionTrait;
	
	public function canView(&$error = null)
	{
		$visitor = \XF::visitor();
		
		$content = $this->Content;
		
		if ($this->comment_state == 'moderated')
		{
			if (
				!$content->hasPermission('viewModeratedComments')
				&& (!$visitor->user_id || $visitor->user_id != $this->user_id)
			)
			{
				return false;
			}
		}
		else if ($this->comment_state == 'deleted')
		{
			if (!$content->hasPermission('viewDeletedComments'))
			{
				return false;
			}
		}
		
		return ($content ? $content->canView($error) && $content->canViewComments($error) : false);
	}
	
	public function canViewCommentImages()
	{
		$content = $this->Content;
	
		return $content->hasPermission('viewCommentAttach');
	}

	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		$content = $this->Content;

		if ($content->hasPermission('editAnyComment'))
		{
			return true;
		}

		if ($this->user_id == $visitor->user_id && $content->hasPermission('editComment'))
		{
			$editLimit = $content->hasPermission('editOwnCommentTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->comment_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_edit_this_comment_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}

			return true;
		}

		return false;
	}

	public function canEditSilently(&$error = null)
	{
		$content = $this->Content;
		$visitor = \XF::visitor();
		if (!$visitor->user_id || !$content)
		{
			return false;
		}

		if ($content->hasPermission('editAnyComment'))
		{
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

		if ($this->Content->hasPermission('editAnyComment'))
		{
			return true;
		}

		return false;
	}

	public function canReassign(&$error = null)
	{
		$visitor = \XF::visitor();
	
		return (
			$visitor->user_id
			&& $this->Content->hasPermission('reassignComment')
		);
	}
	
	public function canChangeDate(&$error = null)
	{
		$visitor = \XF::visitor();
	
		if (!$visitor->user_id)
		{
			return false;
		}

		return  $this->Content->hasPermission('editAnyComment');
	}
	
	public function canDelete($type = 'soft', &$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		$content = $this->Content;

		if ($type != 'soft' && !$content->hasPermission('hardDeleteAnyComment'))
		{
			return false;
		}

		if ($content->hasPermission('deleteAnyComment'))
		{
			return true;
		}

		if ($this->user_id == $visitor->user_id && $content->hasPermission('deleteComment'))
		{
			$editLimit = $content->hasPermission('editOwnCommentTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->comment_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_delete_this_comment_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}

			return true;
		}

		return false;
	}

	public function canUndelete(&$error = null)
	{
		$visitor = \XF::visitor();
		$content = $this->Content;
		if (!$visitor->user_id || !$content)
		{
			return false;
		}

		return $content->hasPermission('undeleteComment');
	}

	public function canApproveUnapprove(&$error = null)
	{
		if (!$this->Content)
		{
			return false;
		}

		return $this->Content->hasPermission('approveUnapproveComment');
	}

	public function canMerge(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		$content = $this->Content;
	
		if ($content->hasPermission('editAnyComment'))
		{
			return true;
		}
	}
	
	public function canWarn(&$error = null)
	{
		$visitor = \XF::visitor();

		if ($this->warning_id
			|| !$this->user_id
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$this->Content->hasPermission('warnComment')
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

		if ($this->comment_state != 'visible')
		{
			return false;
		}

		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}

		return $this->Content->hasPermission('reactComment');
	}

	public function canCleanSpam()
	{
		return (\XF::visitor()->canCleanSpam() && $this->User && $this->User->isPossibleSpammer());
	}

	public function canSendModeratorActionAlert()
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id || $visitor->user_id == $this->user_id)
		{
			return false;
		}

		if ($this->comment_state != 'visible')
		{
			return false;
		}

		return true;
	}

	public function canUseInlineModeration(&$error = null)
	{
		$visitor = \XF::visitor();
		return ($visitor->user_id && $this->Content->hasPermission('inlineModComment'));
	}

	public function isVisible()
	{
		if ($this->comment_state != 'visible')
		{
			return false;
		}

		$content = $this->Content;
		if (!$content)
		{
			return false;
		}
		else if ($content instanceof Item)
		{
			return ($content->item_state == 'visible');
		}
		else
		{
			return true;
		}
	}

	public function isLastComment() //: bool
	{
		return (
			$this->Content &&
			$this->Content->last_comment_date == $this->comment_date
		);
	}

	public function isUnread()
	{
		$content = $this->Content;

		if (!$content)
		{
			return false;
		}

		$visitor = \XF::visitor();

		if (!isset($content->CommentRead[$visitor->user_id]))
		{
			return true;
		}

		$readDate = $content->getVisitorReadDate();
		if ($readDate === null)
		{
			return false;
		}

		return $readDate < $this->comment_date;
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

	public function isIgnored()
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}
	
	public function getQuoteWrapper($inner)
	{
		return '[QUOTE="'
			. ($this->User ? $this->User->username : $this->username)
			. ', sc-comment: ' . $this->comment_id
			. ($this->User ? ", member: $this->user_id" : '')
			. '"]'
			. $inner
			. "[/QUOTE]\n";
	}

	public function getBbCodeRenderOptions($context, $type)
	{
		return [
			'entity' => $this,
			'user' => $this->User,
			'attachments' => $this->attach_count ? $this->Attachments : [],
			'viewAttachments' => $this->canViewCommentImages()
		];
	}
	
	public function hasImageAttachments($comment = false)
	{
		if ($comment && $comment['Attachments'])
		{
			$attachments = $comment['Attachments'];
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
	 * @return Item
	 */
	public function getContent()
	{
		return $this->Item;
	}

	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('comment_state', 'visible');
		$approvalChange = $this->isStateChanged('comment_state', 'moderated');
		$deletionChange = $this->isStateChanged('comment_state', 'deleted');

		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->commentMadeVisible();
				
				if ($approvalChange)
				{
					$this->submitHamData();
				}
			}
			else if ($visibilityChange == 'leave')
			{
				$this->commentHidden();
			}

			if ($this->isChanged('user_id'))
			{
				$this->commentReassigned();
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
			if ($this->comment_state == 'visible')
			{
				$this->commentMadeVisible();
			}
		}

		if ($approvalChange == 'enter')
		{
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->comment_date;
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
			$this->app()->logger()->logModeratorChanges('sc_comment', $this);
		}
	}

	protected function commentMadeVisible()
	{
		$content = $this->Content;
	
		if ($content)
		{
			$content->commentAdded($this);
			$this->adjustUserCommentCountIfNeeded(1);
			$content->saveIfChanged();
		}
	}
	
	protected function commentHidden($hardDelete = false)
	{
		$content = $this->Content;
	
		if ($content)
		{
			$content->commentRemoved($this);
			$this->adjustUserCommentCountIfNeeded(-1);
			$content->saveIfChanged();
		}
	
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('sc_comment', $this->comment_id);
	}
	
	protected function commentInsertedVisible()
	{
		$this->adjustUserCommentCountIfNeeded(1);
	}
	
	protected function commentReassigned()
	{
		if ($this->comment_state == 'visible')
		{
			$this->adjustUserCommentCountIfNeeded(-1, $this->getExistingValue('user_id'));
			$this->adjustUserCommentCountIfNeeded(1);
		}
	}
	
	protected function adjustUserCommentCountIfNeeded($amount, $userId = null)
	{
		if ($userId === null)
		{
			$userId = $this->user_id;
		}
	
		if ($userId)
		{
			$this->db()->query("
				UPDATE xf_user
				SET xa_sc_comment_count = GREATEST(0, xa_sc_comment_count + ?)
				WHERE user_id = ?
			", [$amount, $userId]);
		}
	}

	protected function submitHamData()
	{
		/** @var \XF\Spam\ContentChecker $submitter */
		$submitter = $this->app()->container('spam.contentHamSubmitter');
		$submitter->submitHam('sc_comment', $this->comment_id);
	}

	protected function _postDelete()
	{
		if ($this->Content && $this->comment_state == 'visible')
		{
			$this->commentHidden(true);
		}

		if ($this->comment_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}

		if ($this->comment_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_comment', $this, 'delete_hard');
		}
		
		$db = $this->db();
		
		$db->delete('xf_approval_queue', 'content_id = ? AND content_type = ?', [$this->comment_id, 'sc_comment']);
		$db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->comment_id, 'sc_comment']);
		$db->delete('xf_edit_history', 'content_id = ? AND content_type = ?', [$this->comment_id, 'sc_comment']);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('sc_comment', $this->comment_id);
		
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_comment', $this->comment_id);
	}
	
	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();

		if ($this->comment_state == 'deleted')
		{
			return false;
		}

		$this->comment_state = 'deleted';

		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;

		$this->save();

		return true;
	}

	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
	{
		$route = ($canonical ? 'canonical:' : '') . 'showcase/comments';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}
	
	public function getContentPublicRoute()
	{
		return 'showcase/comments';
	}
	
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('xa_sc_comment_by_x_item_y', [
			'user' => $this->username,
			'title' => $this->Content->title
		]);
	}
	
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_comment';
		$structure->shortName = 'XenAddons\Showcase:Comment';
		$structure->contentType = 'sc_comment';
		$structure->primaryKey = 'comment_id';
		$structure->columns = [
			'comment_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],

			'message' => ['type' => self::STR,
				'required' => 'please_enter_valid_message'
			],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'username' => ['type' => self::STR, 'maxLength' => 50],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'comment_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'comment_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted']
			],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'attach_count' => ['type' => self::UINT, 'default' => 0],
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
			'edit_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
		];
		$structure->behaviors = [
			'XF:Reactable' => ['stateField' => 'comment_state'],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField' => 'comment_date'
			],
			'XF:Indexable' => [
				'checkForUpdates' => ['message', 'user_id', 'item_id', 'comment_date', 'comment_state']
			]
		];
		$structure->getters = [
			'Content' => true
		];
		$structure->relations = [
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => [
					['item_id', '=', '$item_id']
				]
			],
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_comment'],
					['content_id', '=', '$comment_id']
				],
				'primary' => true
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_comment'],
					['content_id', '=', '$comment_id']
				],
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
					['content_type', '=', 'sc_comment'],
					['content_id', '=', '$comment_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			]
		];
		$structure->defaultWith = [
			'Item', 'User'
		];
		$structure->options = [
			'log_moderator' => true
		];

		$structure->withAliases = [
			'full' => [
				'User',
				'User.Profile',
				function()
				{
					if (\XF::options()->showMessageOnlineStatus)
					{
						return 'User.Activity';
					}
				},
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