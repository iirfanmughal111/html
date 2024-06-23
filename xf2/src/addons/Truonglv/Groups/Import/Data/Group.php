<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

class Group extends AbstractEmulatedData
{
    /**
     * @return string
     */
    protected function getEntityShortName()
    {
        return 'Truonglv\Groups:Group';
    }

    /**
     * @return string
     */
    public function getImportType()
    {
        return 'tl_group';
    }
}
