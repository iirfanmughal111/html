<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

class Post extends AbstractEmulatedData
{
    /**
     * @return string
     */
    public function getImportType()
    {
        return 'tl_group_post';
    }

    /**
     * @return string
     */
    protected function getEntityShortName()
    {
        return 'Truonglv\Groups:Post';
    }
}
