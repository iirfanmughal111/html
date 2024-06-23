<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\ReactionTrait;
use XF\Entity\BookmarkTrait;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $item_update_id
 * @property int $item_id
 * @property int $user_id
 * @property string $username
 * @property int $update_date
 * @property int $edit_date
 * @property string $update_state
 * @property string $message
 * @property int $attach_count
 * @property array $custom_fields_
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 * @property array $custom_fields_
 * @property int $last_edit_date
 * @property int $last_edit_user_id
 * @property int $edit_count
 * @property int $reply_count
 * @property int $first_reply_date
 * @property int $last_reply_date
 * @property array $latest_reply_ids
 * @property int $ip_id
 * @property array $embed_metadata
 *  
 * GETTERS
 * @property Item $Content
 * @property string $item_title
 * @property array $reply_ids
 * @property ArrayCollection|null $LatestReplies
 * @property \XF\CustomField\Set $custom_fields
 * @property mixed $reactions
 * @property mixed $reaction_users
 * 
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \XF\Entity\User $User
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\BookmarkItem[] $Bookmarks
 * @property \XF\Mvc\Entity\AbstractCollection|\XenAddons\Showcase\Entity\ItemUpdateReply[] $Replies
 */
class ItemUpdate extends Entity implements \XF\BbCode\RenderableContentInterface, \XF\Entity\LinkableInterface
{
	use ReactionTrait, BookmarkTrait;
	
	public function canView(&$error = null)
	{
		$item = $this->Item;
		
		if (!$item || !$item->canView($error) || !$item->canViewUpdates($error))
		{
			return false;
		}

		$visitor = \XF::visitor();
		
		if ($this->update_state == 'moderated')
		{
			if (
				!$item->hasPermission('viewModerated')
				&& (!$visitor->user_id || !$item->isContributor())
			)
			{
				return false;
			}
		}
		else if ($this->update_state == 'deleted')
		{
			if (!$item->hasPermission('viewDeleted')) 
			{
				return false;
			}
		}

		return true;
	}
	
	public function canViewUpdateImages()
	{
		return $this->Item->hasPermission('viewUpdateAttach');
	}
	
	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		$item = $this->Item;

		if ($item->hasPermission('editAny'))
		{
			return true;
		}

		if ($item->isContributor() && $item->hasPermission('editOwn'))
		{
			$editLimit = $item->hasPermission('editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->update_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_edit_this_update_x_minutes_has_expired', ['editLimit' => $editLimit]);
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
	
		if ($item->hasPermission('editAny'))
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
	
		if ($this->Item->hasPermission('editAny'))
		{
			return true;
		}
	
		return false;
	}
	
	public function canDelete($type = 'soft', &$error = null)
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return false;
		}

		$item = $this->Item;
		
		if ($type != 'soft')
		{
			return $item->hasPermission('hardDeleteAny');
		}
		
		if ($item->hasPermission('deleteAny'))
		{
			return true;
		}
		
		if ($item->isContributor() && $item->hasPermission('deleteOwn'))
		{
			$editLimit = $item->hasPermission('editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->update_date < \XF::$time - 60 * $editLimit))
			{
				$error = \XF::phrase('xa_sc_time_limit_to_delete_this_update_x_minutes_has_expired', ['editLimit' => $editLimit]);
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
	
		return $this->Item->hasPermission('approveUnapprove');
	}	

	protected function canBookmarkContent(&$error = null)
	{
		return $this->isVisible();
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
		
		if (!$item)
		{
			return false;
		}
	
		if ($this->warning_id
			|| !$this->user_id
			|| !$visitor->user_id
			|| $item->isContributor()
			|| !$item->hasPermission('warn')
		)
		{
			return false;
		}
	
		return ($this->User && $this->User->isWarnable());
	}
	
	public function canReply(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->update_state != 'visible')
		{
			return false;
		}
	
		$item = $this->Item;
	
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
		
		return $item->hasPermission('updateReply');
	}
	
	public function canViewDeletedReplies()
	{
		return $this->Item->hasPermission('viewDeleted');
	}
	
	public function canViewModeratedReplies()
	{
		return $this->Item->hasPermission('viewModerated');
	}
	
	public function canReact(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->update_state != 'visible')
		{
			return false;
		}
	
		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}
	
		return $this->Item->hasPermission('reactUpdate');
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
		
		if ($this->update_state != 'visible')
		{
			return false;
		}
		
		return true;
	}
	
	public function canUseInlineModeration(&$error = null)
	{
		return (\XF::visitor()->user_id && $this->Item->hasPermission('inlineMod'));
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
			$this->update_state == 'visible'
			&& $this->Item
			&& $this->Item->isVisible()
		);
	}

	public function isIgnored()
	{
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
			'viewAttachments' => $this->canViewUpdateImages()
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
	
		$isSelf = ($visitor->user_id == $this->user_id || !$this->item_update_id);
		$isMod = ($visitor->user_id && $this->Item->hasPermission('editAny'));
	
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
		$fieldDefinitions = $this->app()->container('customFields.sc_updates');
	
		return new \XF\CustomField\Set($fieldDefinitions, $this);
	}
	
	/**
	 * @return \XF\CustomField\Set
	 */
	public function getUpdateFields()
	{
		/** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
		$fieldDefinitions = $this->app()->container('customFields.sc_updates');
	
		return new \XF\CustomField\Set($fieldDefinitions, $this);
	}
	
	public function hasImageAttachments($update = false)
	{
		if ($update && $update['Attachments'])
		{
			$attachments = $update['Attachments'];
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
			FROM xf_xa_sc_item_update_reply
			WHERE item_update_id = ?
			ORDER BY reply_date
		", $this->item_update_id);
	}
	
	/**
	 * @return ArrayCollection|null
	 */
	public function getLatestReplies()
	{
		$this->repository('XenAddons\Showcase:ItemUpdate')->addRepliesToItemUpdates([$this->item_update_id => $this]);
	
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
	
	public function replyAdded(ItemUpdateReply $reply)
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
	
	public function replyRemoved(ItemUpdateReply $reply)
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
			FROM xf_xa_sc_item_update_reply
			WHERE item_update_id = ?
				AND reply_state = 'visible'
			ORDER BY reply_date
			LIMIT 1
		", $this->item_update_id);
	
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
			FROM xf_xa_sc_item_update_reply
			WHERE item_update_id = ?
				AND reply_state = 'visible'
			ORDER BY reply_date DESC
			LIMIT 1
		", $this->item_update_id);
	
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
			FROM xf_xa_sc_item_update_reply
			WHERE item_update_id = ?
				AND reply_state = 'visible'
		", $this->item_update_id);
	
		$this->reply_count = $visibleReplies;
	
		return $this->reply_count;
	}
	
	public function rebuildLatestReplyIds()
	{
		$this->latest_reply_ids = $this->repository('XenAddons\Showcase:ItemUpdate')->getLatestReplyCache($this);
	}

	protected function _preSave()
	{
		// currently no pre save actions (might be some in the future)
	}

	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('update_state', 'visible');
		$approvalChange = $this->isStateChanged('update_state', 'moderated');
		$deletionChange = $this->isStateChanged('update_state', 'deleted');

		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->updateMadeVisible();
			}
			else if ($visibilityChange == 'leave')
			{
				$this->updateHidden();
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
			if ($this->update_state == 'visible')
			{
				$this->updateMadeVisible();
			}
		}

		if ($approvalChange == 'enter')
		{
			$approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
			$approvalQueue->content_date = $this->update_date;
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
			$this->app()->logger()->logModeratorChanges('sc_update', $this);
		}
		
		$this->_postSaveBookmarks();
	}

	protected function updateMadeVisible()
	{
		$item = $this->Item;

		if ($item)
		{
			$item->update_count++;
			$item->last_update = \XF::$time;

			$item->saveIfChanged();
		}
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->recalculateReactionIsCounted('sc_update_reply', $this->reply_ids);
	}

	protected function updateHidden($hardDelete = false)
	{
		$item = $this->Item;

		if ($item)
		{
			$item->update_count--;

			$item->saveIfChanged();
		}

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('sc_update', $this->item_update_id);
		$alertRepo->fastDeleteAlertsForContent('sc_update_reply', $this->reply_ids);
		
		if (!$hardDelete)
		{
			// on hard delete the reactions will be removed which will do this
			/** @var \XF\Repository\Reaction $reactionRepo */
			$reactionRepo = $this->repository('XF:Reaction');
			$reactionRepo->recalculateReactionIsCounted('sc_update_reply', $this->reply_ids, false);
		}
	}

	protected function _postDelete()
	{
		if ($this->update_state == 'visible')
		{
			$this->updateHidden(true);
		}

		if ($this->update_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}
		
		if ($this->update_state == 'moderated' && $this->ApprovalQueue)
		{
			$this->ApprovalQueue->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_update', $this, 'delete_hard');
		}
		
		$db = $this->db();
		
		$db->delete('xf_xa_sc_update_field_value', 'item_update_id = ?', $this->item_update_id);
		
		$db->delete('xf_approval_queue', 'content_id = ? AND content_type = ?', [$this->item_update_id, 'sc_update']);
		$db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->item_update_id, 'sc_update']);
		$db->delete('xf_edit_history', 'content_id = ? AND content_type = ?', [$this->item_update_id, 'sc_update']);
		
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_update', $this->item_update_id);
		
		$replyIds = $this->reply_ids;
		if ($replyIds)
		{
			$quotedIds = $db->quote($replyIds);
		
			$db->delete('xf_xa_sc_item_update_reply', "reply_id IN ({$quotedIds})");
			$db->delete('xf_approval_queue', "content_id IN ({$quotedIds}) AND content_type = 'sc_update_reply'");
			$db->delete('xf_deletion_log', "content_id IN ({$quotedIds}) AND content_type = 'sc_update_reply'");
		}
		
		$this->_postDeleteBookmarks();
	}

	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();

		if ($this->update_state == 'deleted')
		{
			return false;
		}

		$this->update_state = 'deleted';

		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;

		$this->save();

		return true;
	}
	
	public function getNewReply()
	{
		$reply = $this->_em->create('XenAddons\Showcase:ItemUpdateReply');
		$reply->item_update_id = $this->item_update_id;
	
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
		$route = ($canonical ? 'canonical:' : '') . 'showcase/update';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}
	
	public function getContentPublicRoute()
	{
		return 'showcase/update';
	}
	
	public function getContentTitle(string $context = '')
	{
		return \XF::phrase('xa_sc_item_update_in_x', [
			'title' => $this->Item->title
		]);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_update';
		$structure->shortName = 'XenAddons\Showcase:ItemUpdate';
		$structure->primaryKey = 'item_update_id';
		$structure->contentType = 'sc_update';
		$structure->columns = [
			'item_update_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'username' => ['type' => self::STR, 'maxLength' => 50,
				'required' => 'please_enter_valid_name'
			],
			'title' => ['type' => self::STR, 'maxLength' => 100,
				'required' => 'please_enter_valid_title',
				'censor' => true
			],
			'update_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'edit_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'update_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted']
			],
			'message' => ['type' => self::STR, 'default' => ''],
			'attach_count' => ['type' => self::UINT, 'default' => 0],
			
			'custom_fields' => ['type' => self::JSON_ARRAY, 'default' => []],
			
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
			'edit_count' => ['type' => self::UINT, 'default' => 0],
			
			'reply_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
			'first_reply_date' => ['type' => self::UINT, 'default' => 0],
			'last_reply_date' => ['type' => self::UINT, 'default' => 0],
			'latest_reply_ids' => ['type' => self::JSON_ARRAY, 'default' => []],

			'ip_id' => ['type' => self::UINT, 'default' => 0],
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
			'XF:Reactable' => ['stateField' => 'update_state'],
			'XF:ReactableContainer' => [
				'childContentType' => 'sc_update_reply',
				'childIds' => function($itemUpdate) { return $itemUpdate->reply_ids; },
				'stateField' => 'reply_state'
			],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField' => 'update_date'
			],	
			'XF:CustomFieldsHolder' => [
				'valueTable' => 'xf_xa_sc_update_field_value',
				'checkForUpdates' => ['category_id'],
				'getAllowedFields' => function($update) { return $update->Item->Category ? $update->Item->Category->update_field_cache : []; }
			],
			'XF:Indexable' => [
				'checkForUpdates' => ['title', 'message', 'user_id', 'item_id', 'update_date', 'update_state']
			],
			'XF:IndexableContainer' => [
				'childContentType' => 'sc_update_reply',
				'childIds' => function($itemUpdate) { return $itemUpdate->reply_ids; },
				'checkForUpdates' => ['item_update_id', 'reply_state']
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
					['content_type', '=', 'sc_update'],
					['content_id', '=', '$item_update_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],		
			'ApprovalQueue' => [
				'entity' => 'XF:ApprovalQueue',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_update'],
					['content_id', '=', '$item_update_id']
				],
				'primary' => true
			],			
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_update'],
					['content_id', '=', '$item_update_id']
				],
				'primary' => true
			],
			'Replies' => [
				'entity' => 'XenAddons\Showcase:ItemUpdateReply',
				'type' => self::TO_MANY,
				'conditions' => 'item_update_id',
				'primary' => true
			]
		];
		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = ['Item', 'User'];

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
							'Bookmarks|' . $userId
						];
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