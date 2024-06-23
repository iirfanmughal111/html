<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class EventRebuild extends AbstractRebuildCommand
{
    /**
     * Name of the rebuild command suffix (do not include the command namespace)
     *
     * @return string
     */
    protected function getRebuildName()
    {
        return 'tlg-events';
    }

    /**
     * @return string
     */
    protected function getRebuildDescription()
    {
        return 'Rebuilds event counters.';
    }

    /**
     * @return string
     */
    protected function getRebuildClass()
    {
        return 'Truonglv\Groups:EventRebuild';
    }
}
