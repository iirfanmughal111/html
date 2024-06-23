<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Comment;

use XF;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Comment;
use XF\Service\ValidateAndSavableTrait;

class Editor extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Comment
     */
    protected $comment;

    /**
     * @var Preparer
     */
    protected $preparer;
    /**
     * @var bool
     */
    protected $logEdit = true;

    public function __construct(\XF\App $app, Comment $comment)
    {
        parent::__construct($app);

        $this->setComment($comment);
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
        /** @var Preparer $preparer */
        $preparer = $this->service('Truonglv\Groups:Comment\Preparer', $comment);
        $this->preparer = $preparer;
    }

    /**
     * @param bool $bool
     * @return bool
     */
    public function setLogEdit(bool $bool)
    {
        $this->logEdit = $bool;

        return true;
    }

    public function setIsAutomated(): void
    {
        $this->setLogEdit(false);
    }

    /**
     * @param string $message
     * @param bool $format
     * @return void
     */
    public function setMessage($message, $format = true)
    {
        $this->preparer->setMessage($message, $format);
    }

    /**
     * @param string $attachmentHash
     * @return void
     */
    public function setAttachmentHash($attachmentHash)
    {
        $this->preparer->setAttachmentHash($attachmentHash);
    }

    /**
     * @return void
     */
    protected function finalizeSetup()
    {
        if ($this->logEdit) {
            $this->comment->last_edit_date = XF::$time;
            $this->comment->last_edit_user_id = XF::visitor()->user_id;
        }
        $this->comment->edit_count++;
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();

        $this->comment->preSave();

        return $this->comment->getErrors();
    }

    /**
     * @return Comment
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $comment = $this->comment;

        $comment->save();
        $this->preparer->afterUpdate();

        return $comment;
    }
}
