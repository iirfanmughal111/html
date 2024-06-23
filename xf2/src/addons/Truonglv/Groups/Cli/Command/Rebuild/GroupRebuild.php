<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Cli\Command\Rebuild;

use Symfony\Component\Console\Input\InputOption;
use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class GroupRebuild extends AbstractRebuildCommand
{
    /**
     * Name of the rebuild command suffix (do not include the command namespace)
     *
     * @return string
     */
    protected function getRebuildName()
    {
        return 'tlg-groups';
    }

    /**
     * @return string
     */
    protected function getRebuildDescription()
    {
        return 'Rebuilds group counters.';
    }

    /**
     * @return string
     */
    protected function getRebuildClass()
    {
        return 'Truonglv\Groups:GroupRebuild';
    }

    /**
     * @return void
     */
    protected function configureOptions()
    {
        $this->addOption(
            'counter',
            null,
            InputOption::VALUE_NONE,
            'Default: true'
        );

        $this->addOption(
            'last_activity',
            null,
            InputOption::VALUE_NONE,
            'Default: true'
        );
    }
}
