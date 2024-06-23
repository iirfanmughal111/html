<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

class Category extends AbstractEmulatedData
{
    /**
     * @return string
     */
    protected function getEntityShortName()
    {
        return 'Truonglv\Groups:Category';
    }

    /**
     * @return string
     */
    public function getImportType()
    {
        return 'tl_group_category';
    }
}
