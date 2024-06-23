<?php

namespace Z61\Classifieds\Cli\Command;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildUserListingCounts extends AbstractRebuildCommand
{
    protected function getRebuildName()
    {
        return 'classifieds-user-listing-counts';
    }

    protected function getRebuildDescription()
    {
        return 'Rebuilds user listing counters.';
    }

    protected function getRebuildClass()
    {
        return 'Z61\Classifieds:UserListingCount';
    }
}