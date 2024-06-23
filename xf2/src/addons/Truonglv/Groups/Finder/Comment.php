<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Finder;

use XF;
use function count;
use function array_keys;
use XF\Mvc\Entity\Finder;

class Comment extends Finder
{
    /**
     * @param string $contentType
     * @param int $contentId
     * @return $this
     */
    public function inContent($contentType, $contentId)
    {
        $this->where('content_type', $contentType);
        $this->where('content_id', $contentId);

        $this->orderByDate();

        return $this;
    }

    /**
     * @param int $date
     * @return $this
     */
    public function newerThan($date)
    {
        $this->where('comment_date', '>', $date);

        return $this;
    }

    /**
     * @param string $direction
     * @return $this
     */
    public function orderByDate($direction = 'ASC')
    {
        $this->setDefaultOrder('comment_date', $direction);

        return $this;
    }

    /**
     * @param \XF\Entity\User|null $user
     * @return $this
     */
    public function skipIgnored(\XF\Entity\User $user = null)
    {
        if ($user === null) {
            $user = XF::visitor();
        }

        if ($user->user_id <= 0) {
            return $this;
        }

        if ($user->Profile !== null && count($user->Profile->ignored) > 0) {
            $this->where('user_id', '<>', array_keys($user->Profile->ignored));
        }

        return $this;
    }
}
