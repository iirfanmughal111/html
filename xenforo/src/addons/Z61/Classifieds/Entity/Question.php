<?php

namespace Z61\Classifieds\Entity;

use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Question extends Entity implements \XF\BbCode\RenderableContentInterface
{
    use ReactionTrait;

    public function canView(&$error = null)
    {
        $listing = $this->Listing;

        if ($this->question_state == 'deleted')
        {
            if (!$listing->hasPermission('viewDeleted'))
            {
                return false;
            }
        }

        return ($listing ? $listing->canView($error) && $listing->canViewQuestions($error) : false);
    }

    public function canViewQuestionImages()
    {
        $listing = $this->Listing;

        return $listing->hasPermission('viewQuestionAttach');
    }

    public function canEdit(&$error = null)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        $listing = $this->Listing;

        if ($listing->hasPermission('editAnyQuestion'))
        {
            return true;
        }

        if ($this->user_id == $visitor->user_id && $listing->hasPermission('editQuestion'))
        {
            $editLimit = $listing->hasPermission('editOwnQuestionTimeLimit');
            if ($editLimit != -1 && (!$editLimit || $this->question_date < \XF::$time - 60 * $editLimit))
            {
                $error = \XF::phrase('z61_classifieds_time_limit_to_edit_this_question_x_minutes_has_expired', ['editLimit' => $editLimit]);
                return false;
            }

            return true;
        }

        return false;
    }

    public function canEditSilently(&$error = null)
    {
        $listing = $this->Listing;
        $visitor = \XF::visitor();
        if (!$visitor->user_id || !$listing)
        {
            return false;
        }

        if ($listing->hasPermission('editAnyQuestion'))
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

        if ($this->Listing->hasPermission('editAnyQuestion'))
        {
            return true;
        }

        return false;
    }

    public function canReassign(&$error = null)
    {
        $visitor = \XF::visitor();

        $listing = $this->Listing;

        return (
            $visitor->user_id
            && $listing->hasPermission('reassignQuestion')
        );
    }

    public function canMove(&$error = null)
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id)
        {
            return false;
        }

        $listing = $this->Listing;

        return $listing->hasPermission('editAnyQuestion');
    }

    public function canDelete($type = 'soft', &$error = null)
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id)
        {
            return false;
        }

        $listing = $this->Listing;

        if ($type != 'soft' && !$listing->hasPermission('hardDeleteAnyQuestion'))
        {
            return false;
        }

        if ($listing->hasPermission('deleteAnyQuestion'))
        {
            return true;
        }

        if ($this->user_id == $visitor->user_id && $listing->hasPermission('deleteQuestion'))
        {
            $editLimit = $listing->hasPermission('editOwnQuestionTimeLimit');
            if ($editLimit != -1 && (!$editLimit || $this->question_date < \XF::$time - 60 * $editLimit))
            {
                $error = \XF::phrase('z61_classifieds_time_limit_to_delete_this_question_x_minutes_has_expired', ['editLimit' => $editLimit]);
                return false;
            }

            return true;
        }

        return false;
    }

    public function canUndelete(&$error = null)
    {
        $visitor = \XF::visitor();
        $listing = $this->Listing;

        if (!$visitor->user_id || !$listing)
        {
            return false;
        }

        return $listing->hasPermission('undelete');
    }

    public function canApproveUnapprove(&$error = null)
    {
        if (!$this->Listing)
        {
            return false;
        }

        return $this->Listing->hasPermission('approveUnapproveQuestion');
    }

    public function canReport(&$error = null, \XF\Entity\User $asUser = null)
    {
        $asUser = $asUser ?: \XF::visitor();
        return $asUser->canReport($error);
    }

    public function canWarn(&$error = null)
    {
        $visitor = \XF::visitor();
        $listing = $this->Listing;

        if ($this->warning_id
            || !$listing
            || !$visitor->user_id
            || $this->user_id == $visitor->user_id
            || !$listing->hasPermission('warn')
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
        $listing = $this->Listing;

        $replyBans = $listing->ReplyBans;
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

        return (
            $this->question_state == 'visible'
            && $visitor->user_id
            && $listing
            && $listing->hasPermission('questionReply')
        );
    }

    public function canViewDeletedReplies()
    {
        $visitor = \XF::visitor();
        $listing = $this->Listing;

        return $listing->hasPermission('viewDeletedQuestions');
    }

    public function canViewModeratedReplies()
    {
        $visitor = \XF::visitor();
        $listing = $this->Listing;

        return $listing->hasPermission('viewModeratedQuestions');
    }

    public function canReact(&$error = null)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if ($this->question_state != 'visible')
        {
            return false;
        }

        if ($this->user_id == $visitor->user_id)
        {
            $error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
            return false;
        }

        return $this->Listing->hasPermission('reactQuestion');
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

        if ($this->question_state != 'visible')
        {
            return false;
        }

        return true;
    }

    public function canUseInlineModeration(&$error = null)
    {
        $visitor = \XF::visitor();
        return ($visitor->user_id && $this->Listing->hasPermission('inlineModQuestion'));
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
            $this->question_state == 'visible'
            && $this->Listing
            && $this->Listing->isVisible()
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
            'viewAttachments' => $this->canViewQuestionImages()
        ];
    }

    /**
     * @return string
     */
    public function getListingTitle()
    {
        return $this->Listing ? $this->Listing->title : '';
    }

    /**
     * @return Listing
     */
    public function getContent()
    {
        return $this->Listing;
    }

    public function hasImageAttachments($question = false)
    {
        if ($question && $question['Attachments'])
        {
            $attachments = $question['Attachments'];
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
			FROM xf_z61_classifieds_question_reply
			WHERE question_id = ?
			ORDER BY reply_date
		", $this->question_id);
    }

    /**
     * @return ArrayCollection|null
     */
    public function getLatestReplies()
    {
        $this->repository('Z61\Classifieds:Question')->addRepliesToQuestions([$this->question_id => $this]);

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

    public function replyAdded(QuestionReply $reply)
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

    public function replyRemoved(QuestionReply $reply)
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

        $this->rebuildHelpfulCounts();

        return true;
    }

    public function rebuildFirstReplyInfo()
    {
        $firstReply = $this->db()->fetchRow("
			SELECT reply_id, reply_date, user_id, username
			FROM xf_z61_classifieds_question_reply
			WHERE question_id = ?
				AND reply_state = 'visible'
			ORDER BY reply_date
			LIMIT 1
		", $this->question_id);

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
			FROM xf_z61_classifieds_question_reply
			WHERE question_id = ?
				AND reply_state = 'visible'
			ORDER BY reply_date DESC
			LIMIT 1
		", $this->question_id);

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
			FROM xf_z61_classifieds_question_reply
			WHERE question_id = ?
				AND reply_state = 'visible'
		", $this->question_id);

        $this->reply_count = $visibleReplies;

        return $this->reply_count;
    }

    public function rebuildLatestReplyIds()
    {
        $this->latest_reply_ids = $this->repository('Z61\Classifieds:Question')->getLatestReplyCache($this);
    }

    protected function _preSave()
    {
        if (!$this->user_id || !$this->listing_id)
        {
            throw new \LogicException("Need user and listing IDs");
        }
    }

    protected function _postSave()
    {
        $visibilityChange = $this->isStateChanged('question_state', 'visible');
        $approvalChange = $this->isStateChanged('question_state', 'moderated');
        $deletionChange = $this->isStateChanged('question_state', 'deleted');

        if ($this->isUpdate())
        {
            if ($visibilityChange == 'enter')
            {
                $this->questionMadeVisible();
            }
            else if ($visibilityChange == 'leave')
            {
                $this->questionHidden();
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
            if ($this->question_state == 'visible')
            {
                $this->questionMadeVisible();
            }
        }

        if ($approvalChange == 'enter')
        {
            $approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
            $approvalQueue->content_date = $this->question_date;
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
            $this->app()->logger()->logModeratorChanges('classifieds_question', $this);
        }
    }

    protected function questionMadeVisible()
    {
        $listing = $this->Listing;

        if ($listing)
        {
            $listing->question_count++;
        }

        if ($listing)
        {
            $listing->saveIfChanged();
        }

        /** @var \XF\Repository\Reaction $reactionRepo */
        $reactionRepo = $this->repository('XF:Reaction');
        $reactionRepo->recalculateReactionIsCounted('classifieds_question_reply', $this->reply_ids);
    }

    protected function questionHidden($hardDelete = false)
    {
        $listing = $this->Listing;

        if ($listing)
        {
            $listing->question_count--;
        }

        if ($listing)
        {
            $listing->saveIfChanged();
        }

        /** @var \XF\Repository\UserAlert $alertRepo */
        $alertRepo = $this->repository('XF:UserAlert');
        $alertRepo->fastDeleteAlertsForContent('classifieds_question', $this->question_id);
        $alertRepo->fastDeleteAlertsForContent('classifieds_question_reply', $this->reply_ids);

        if (!$hardDelete)
        {
            /** @var \XF\Repository\Reaction $reactionRepo */
            $reactionRepo = $this->repository('XF:Reaction');
            $reactionRepo->recalculateReactionIsCounted('classifieds_question_reply', $this->reply_ids, false);
        }
    }

    protected function _postDelete()
    {
        if ($this->question_state == 'visible')
        {
            $this->questionHidden(true);
        }

        if ($this->question_state == 'deleted' && $this->DeletionLog)
        {
            $this->DeletionLog->delete();
        }

        if ($this->question_state == 'moderated' && $this->ApprovalQueue)
        {
            $this->ApprovalQueue->delete();
        }

        if ($this->getOption('log_moderator'))
        {
            $this->app()->logger()->logModeratorAction('classifieds_question', $this, 'delete_hard');
        }

        $db = $this->db();

        $db->delete('xf_z61_classifieds_question_helpful', 'question_id = ?', $this->question_id);

        $db->delete('xf_approval_queue', 'content_id = ? AND content_type = ?', [$this->question_id, 'classifieds_question']);
        $db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->question_id, 'classifieds_question']);
        $db->delete('xf_edit_history', 'content_id = ? AND content_type = ?', [$this->question_id, 'classifieds_question']);

        /** @var \XF\Repository\Attachment $attachRepo */
        $attachRepo = $this->repository('XF:Attachment');
        $attachRepo->fastDeleteContentAttachments('classifieds_question', $this->question_id);

        $replyIds = $this->reply_ids;
        if ($replyIds)
        {
            $quotedIds = $db->quote($replyIds);

            $db->delete('xf_z61_classifieds_question_reply', "reply_id IN ({$quotedIds})");
            $db->delete('xf_z61_classifieds_question_reply_helpful', "reply_id IN ({$quotedIds})");
            $db->delete('xf_approval_queue', "content_id IN ({$quotedIds}) AND content_type = 'classifieds_question_reply'");
            $db->delete('xf_deletion_log', "content_id IN ({$quotedIds}) AND content_type = 'classifieds_question_reply'");
        }
    }

    public function softDelete($reason = '', \XF\Entity\User $byUser = null)
    {
        $byUser = $byUser ?: \XF::visitor();

        if ($this->question_state == 'deleted')
        {
            return false;
        }

        $this->question_state = 'deleted';

        /** @var \XF\Entity\DeletionLog $deletionLog */
        $deletionLog = $this->getRelationOrDefault('DeletionLog');
        $deletionLog->setFromUser($byUser);
        $deletionLog->delete_reason = $reason;

        $this->save();

        return true;
    }

    public function getNewReply()
    {
        $reply = $this->_em->create('Z61\Classifieds:QuestionReply');
        $reply->question_id = $this->question_id;
        $reply->listing_id = $this->listing_id;

        return $reply;
    }

    public function getNewContentState()
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

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_question';
        $structure->shortName = 'Z61\Classifieds:Question';
        $structure->primaryKey = 'question_id';
        $structure->contentType = 'classifieds_question';
        $structure->columns = [
            'question_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'listing_id' => ['type' => self::UINT, 'required' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'username' => ['type' => self::STR, 'maxLength' => 50,
                'required' => 'please_enter_valid_name'
            ],
            'question_date' => ['type' => self::UINT, 'default' => \XF::$time],
            'question_state' => ['type' => self::STR, 'default' => 'visible',
                'allowedValues' => ['visible', 'moderated', 'deleted']
            ],
            'message' => ['type' => self::STR, 'default' => ''],
            'attach_count' => ['type' => self::UINT, 'default' => 0],
            'reply_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
            'latest_reply_ids' => ['type' => self::JSON_ARRAY, 'default' => []],
            'first_reply_date' => ['type' => self::UINT, 'default' => 0],
            'last_reply_date' => ['type' => self::UINT, 'default' => 0],
            'warning_id' => ['type' => self::UINT, 'default' => 0],
            'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
            'last_edit_date' => ['type' => self::UINT, 'default' => 0],
            'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
            'edit_count' => ['type' => self::UINT, 'default' => 0],
            'ip_id' => ['type' => self::UINT, 'default' => 0],
            'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
        ];
        $structure->getters = [
            'Content' => true,
            'listing_title' => true,
            'reply_ids' => true,
            'LatestReplies' => true,
        ];
        $structure->behaviors = [
            'XF:Reactable' => ['stateField' => 'question_state'],
            'XF:ReactableContainer' => [
                'childContentType' => 'classifieds_question_reply',
                'childIds' => function($question) { return $question->reply_ids; },
                'stateField' => 'reply_state'
            ],
            'XF:NewsFeedPublishable' => [
                'usernameField' => 'username',
                'dateField' => 'question_date'
            ],
            'XF:Indexable' => [
                'checkForUpdates' => ['message', 'user_id', 'listing_id', 'question_date', 'question_state']
            ],
            'XF:IndexableContainer' => [
                'childContentType' => 'classifieds_question_reply',
                'childIds' => function($question) { return $question->reply_ids; },
                'checkForUpdates' => ['question_id', 'reply_state']
            ],
        ];
        $structure->relations = [
            'Listing' => [
                'entity' => 'Z61\Classifieds:Listing',
                'type' => self::TO_ONE,
                'conditions' => 'listing_id',
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
                    ['content_type', '=', 'classifieds_question'],
                    ['content_id', '=', '$question_id']
                ],
                'with' => 'Data',
                'order' => 'attach_date'
            ],
            'ApprovalQueue' => [
                'entity' => 'XF:ApprovalQueue',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'classifieds_question'],
                    ['content_id', '=', '$question_id']
                ],
                'primary' => true
            ],
            'DeletionLog' => [
                'entity' => 'XF:DeletionLog',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'classifieds_question'],
                    ['content_id', '=', '$question_id']
                ],
                'primary' => true
            ],
            'Replies' => [
                'entity' => 'Z61\Classifieds:QuestionReply',
                'type' => self::TO_MANY,
                'conditions' => 'question_id',
                'primary' => true
            ]
        ];
        $structure->options = [
            'log_moderator' => true
        ];
        $structure->defaultWith = ['Listing', 'User'];

        static::addReactableStructureElements($structure);

        return $structure;
    }
}