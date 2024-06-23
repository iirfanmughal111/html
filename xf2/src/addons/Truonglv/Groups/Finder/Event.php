<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Finder;

use XF;
use DateTime;
use DateTimeZone;
use XF\Mvc\Entity\Finder;

class Event extends Finder
{
    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return $this
     */
    public function inGroup(\Truonglv\Groups\Entity\Group $group)
    {
        $this->where('group_id', $group->group_id);

        return $this;
    }

    /**
     * @return $this
     */
    public function upcoming()
    {
        $dt = new DateTime();
        $dt->setTimezone($this->getVisitorTimezone());

        $this->where('begin_date', '>=', $dt->getTimestamp());

        return $this;
    }

    /**
     * @return $this
     */
    public function closed()
    {
        $dt = new DateTime();
        $dt->setTimezone($this->getVisitorTimezone());

        $this->where('end_date', '<=', $dt->getTimestamp());

        return $this;
    }

    /**
     * @return $this
     */
    public function ongoing()
    {
        $dt = new DateTime();
        $dt->setTimezone($this->getVisitorTimezone());

        $this->where('begin_date', '<=', $dt->getTimestamp());
        // not ended
        $this->where('end_date', '>', $dt->getTimestamp());

        return $this;
    }

    protected function getVisitorTimezone(): DateTimeZone
    {
        $visitor = XF::visitor();

        return $visitor->user_id > 0
            ? new DateTimeZone($visitor->timezone)
            : new DateTimeZone(XF::app()->options()->guestTimeZone);
    }
}
