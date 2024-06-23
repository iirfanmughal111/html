<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Cli\Command\Rebuild;

use Symfony\Component\Console\Input\InputOption;
use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class MemberRebuild extends AbstractRebuildCommand
{
    /**
     * Name of the rebuild command suffix (do not include the command namespace)
     *
     * @return string
     */
    protected function getRebuildName()
    {
        return 'tlg-members';
    }

    /**
     * @return string
     */
    protected function getRebuildDescription()
    {
        return 'Rebuilds member counters.';
    }

    /**
     * @return string
     */
    protected function getRebuildClass()
    {
        return 'Truonglv\Groups:MemberRebuild';
    }

    /**
     * @return void
     */
    protected function configureOptions()
    {
        $this->addOption('remove_deleted', null, InputOption::VALUE_NONE, 'Default: false');
        $this->addOption('remove_banned', null, InputOption::VALUE_NONE, 'Default: false');
    }
}
