<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service;

use XF;
use XF\Entity\User;

trait CommentableTrait
{
    /**
     * @var \Truonglv\Groups\Entity\Comment
     */
    protected $comment;
    /**
     * @var \Truonglv\Groups\Service\Comment\Preparer
     */
    protected $commentPreparer;

    /**
     * @return \Truonglv\Groups\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $message
     * @param bool $format
     * @return void
     */
    public function setMessage(string $message, $format = true)
    {
        $this->commentPreparer->setMessage($message, $format);
    }

    /**
     * @param string $attachmentHash
     * @return void
     */
    public function setAttachmentHash(string $attachmentHash)
    {
        $this->commentPreparer->setAttachmentHash($attachmentHash);
    }

    /**
     * @param User $user
     * @param string $contentType
     * @param int $contentId
     * @param null|int $postDate
     * @return void
     */
    public function setupComment(User $user, string $contentType, $contentId, $postDate = null)
    {
        $this->comment->user_id = $user->user_id;
        $this->comment->username = $user->username;
        $this->comment->comment_date = $postDate !== null ? $postDate : XF::$time;
        $this->comment->content_type = $contentType;
        $this->comment->content_id = $contentId;
    }

    /**
     * @param \Truonglv\Groups\Entity\Comment|null $comment
     * @return void
     */
    protected function setupCommentDefaults(\Truonglv\Groups\Entity\Comment & $comment = null)
    {
        if ($comment === null) {
            /** @var \Truonglv\Groups\Entity\Comment $mixed */
            $mixed = $this->em()->create('Truonglv\Groups:Comment');
            $this->comment = $mixed;
            $comment = $mixed;
        } else {
            $this->comment = $comment;
        }

        /** @var \Truonglv\Groups\Service\Comment\Preparer $commentPreparer */
        $commentPreparer = $this->service('Truonglv\Groups:Comment\Preparer', $this->comment);
        $this->commentPreparer = $commentPreparer;
    }
}
