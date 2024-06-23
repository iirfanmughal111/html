<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Comment;

use XF;
use Exception;
use LogicException;
use function in_array;
use Truonglv\Groups\Entity\Post;
use XF\Service\AbstractNotifier;
use Truonglv\Groups\Entity\Event;
use Truonglv\Groups\Entity\Comment;

class Notifier extends AbstractNotifier
{
    /**
     * @var Comment
     */
    protected $comment;
    /**
     * @var string
     */
    protected $actionType;

    /**
     * Notifier constructor.
     * @param \XF\App $app
     * @param Comment $comment
     * @param string $actionType
     */
    public function __construct(\XF\App $app, Comment $comment, $actionType)
    {
        parent::__construct($app);

        switch ($actionType) {
            case 'comment':
            case 'reply':
            // submit new post
            case 'post':
                // submit new event
            case 'event':
                break;
            default:
                throw new LogicException('Unknown action type (' . $actionType . ')');
        }

        $this->comment = $comment;
        $this->actionType = $actionType;
    }

    /**
     * @param array $users
     * @return void
     */
    protected function loadExtraUserData(array $users)
    {
    }

    /**
     * @return array
     */
    protected function loadNotifiers()
    {
        $comment = $this->comment;
        $app = $this->app;
        $actionType = $this->actionType;

        $content = $comment->getContent();
        if ($content === null) {
            return [];
        }

        $notifiers = [
            'quote' => $app->notifier('Truonglv\Groups:Comment\Quote', $comment),
            'mention' => $app->notifier('Truonglv\Groups:Comment\Mention', $comment)
        ];

        if ($content instanceof Post) {
            if ($actionType === 'post') {
                $notifiers['newPosts'] = $app->notifier('Truonglv\Groups:Post\NewPosts', $content);
            } else {
                $notifiers['postWatch'] = $app->notifier('Truonglv\Groups:Post\Watch', $comment, $actionType);
            }
        } elseif ($content instanceof Event && in_array($actionType, ['comment'], true)) {
            $notifiers['eventWatch'] = $app->notifier('Truonglv\Groups:Event\EventWatch', $comment, $actionType);
        }

        return $notifiers;
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     * @throws Exception
     */
    protected function canUserViewContent(\XF\Entity\User $user)
    {
        return XF::asVisitor($user, function () {
            return $this->comment->canView();
        });
    }

    /**
     * @return array
     */
    protected function getExtraJobData()
    {
        return [
            'commentId' => $this->comment->comment_id,
            'actionType' => $this->actionType
        ];
    }

    /**
     * @param array $extraData
     * @return \XF\Service\AbstractService|null
     */
    public static function createForJob(array $extraData)
    {
        /** @var Comment|null $comment */
        $comment = XF::em()->find('Truonglv\Groups:Comment', $extraData['commentId']);
        if ($comment === null) {
            return null;
        }

        return XF::service('Truonglv\Groups:Comment\Notifier', $comment, $extraData['actionType']);
    }
}
