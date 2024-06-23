<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Comment;

use function trim;
use function count;
use XF\Repository\User;
use Truonglv\Groups\App;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Comment;

class Preparer extends AbstractService
{
    /**
     * @var Comment
     */
    protected $comment;

    /**
     * @var string
     */
    protected $attachmentHash;
    /**
     * @var bool
     */
    protected $logIp = true;
    /**
     * @var array
     */
    protected $quotedPosts = [];
    /**
     * @var array
     */
    protected $mentionedUsers = [];
    /**
     * @var bool
     */
    protected $supportAttachments = true;

    public function __construct(\XF\App $app, Comment $comment)
    {
        parent::__construct($app);

        $this->comment = $comment;
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param bool $supportAttachments
     * @return void
     */
    public function setSupportAttachments(bool $supportAttachments)
    {
        $this->supportAttachments = $supportAttachments;
    }

    /**
     * @param bool $logIp
     * @return void
     */
    public function logIp(bool $logIp)
    {
        $this->logIp = $logIp;
    }

    /**
     * @return array
     */
    public function getQuotedPosts()
    {
        return $this->quotedPosts;
    }

    /**
     * @return array
     */
    public function getQuotedUserIds()
    {
        if (count($this->quotedPosts) === 0) {
            return [];
        }

        $commentIds = \array_keys($this->quotedPosts);
        $quotedUserIds = [];

        $db = $this->db();
        $commentUserMap = $db->fetchPairs('
			SELECT comment_id, user_id
			FROM xf_tl_group_comment
			WHERE comment_id IN (' . $db->quote($commentIds) . ')
		');
        foreach ($commentUserMap as $commentId => $userId) {
            if (!isset($this->quotedPosts[$commentId]) || !$userId) {
                continue;
            }

            $quote = $this->quotedPosts[$commentId];
            if (!isset($quote['member']) || $quote['member'] == $userId) {
                $quotedUserIds[] = $userId;
            }
        }

        return $quotedUserIds;
    }

    /**
     * @param bool $limitPermissions
     * @return array
     */
    public function getMentionedUsers($limitPermissions = true)
    {
        if ($limitPermissions) {
            /** @var User $userRepo */
            $userRepo = $this->repository('XF:User');
            /** @var \XF\Entity\User|null $user */
            $user = $this->comment->User;
            if ($user === null) {
                $user = $userRepo->getGuestUser();
            }

            return $user->getAllowedUserMentions($this->mentionedUsers);
        } else {
            return $this->mentionedUsers;
        }
    }

    /**
     * @param bool $limitPermissions
     * @return array
     */
    public function getMentionedUserIds($limitPermissions = true)
    {
        return array_keys($this->getMentionedUsers($limitPermissions));
    }

    /**
     * @param string $message
     * @param bool $format
     * @return bool
     */
    public function setMessage($message, $format = true)
    {
        $preparer = $this->getMessagePreparer($format);

        $this->comment->message = $preparer->prepare($message, true);
        $this->comment->embed_metadata = $preparer->getEmbedMetadata();

        $this->quotedPosts = $preparer->getQuotesKeyed(App::CONTENT_TYPE_COMMENT);
        $this->mentionedUsers = $preparer->getMentionedUsers();

        return $preparer->pushEntityErrorIfInvalid($this->comment);
    }

    /**
     * @param string $hash
     * @return void
     */
    public function setAttachmentHash(string $hash)
    {
        if ($this->supportAttachments) {
            $this->attachmentHash = $hash;
        }
    }

    /**
     * @return void
     */
    public function afterInsert()
    {
        if (trim($this->attachmentHash) !== '' && $this->supportAttachments) {
            $this->associateAttachments($this->attachmentHash);
        }

        if ($this->logIp) {
            $ip =  $this->app->request()->getIp();
            $this->writeIpLog($ip);
        }
    }

    /**
     * @return void
     */
    public function afterUpdate()
    {
        if (trim($this->attachmentHash) !== '' && $this->supportAttachments) {
            $this->associateAttachments($this->attachmentHash);
        }
    }

    /**
     * @param string $hash
     * @return void
     */
    protected function associateAttachments(string $hash)
    {
        $comment = $this->comment;

        /** @var \XF\Service\Attachment\Preparer $preparer */
        $preparer = $this->service('XF:Attachment\Preparer');
        $associated = $preparer->associateAttachmentsWithContent(
            $hash,
            App::CONTENT_TYPE_COMMENT,
            $comment->comment_id
        );

        if ($associated) {
            $comment->fastUpdate('attach_count', $comment->attach_count + $associated);
        }
    }

    /**
     * @param bool $format
     * @return \XF\Service\Message\Preparer
     */
    protected function getMessagePreparer($format = true)
    {
        /** @var \XF\Service\Message\Preparer $preparer */
        $preparer = $this->service('XF:Message\Preparer', App::CONTENT_TYPE_COMMENT, $this->comment);
        if (!$format) {
            $preparer->disableAllFilters();
        }

        return $preparer;
    }

    /**
     * @param string $ip
     * @return void
     */
    protected function writeIpLog(string $ip)
    {
        if (trim($ip) === '') {
            return;
        }

        $comment = $this->comment;

        /** @var \XF\Repository\Ip $ipRepo */
        $ipRepo = $this->repository('XF:Ip');
        $ipEnt = $ipRepo->logIp(
            $comment->user_id,
            $ip,
            App::CONTENT_TYPE_COMMENT,
            $comment->comment_id
        );

        if ($ipEnt !== null && $ipEnt->ip_id > 0) {
            $comment->fastUpdate('ip_id', $ipEnt->ip_id);
        }
    }
}
