<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

class Event extends AbstractEmulatedData
{
    /**
     * @return string
     */
    protected function getEntityShortName()
    {
        return 'Truonglv\Groups:Event';
    }

    /**
     * @return string
     */
    public function getImportType()
    {
        return 'tl_group_event';
    }
}
