<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Import\Data;

use XF\Import\Data\AbstractField;

class Field extends AbstractField
{
    /**
     * @return string
     */
    protected function getEntityShortName()
    {
        return 'Truonglv\Groups:Field';
    }

    /**
     * @return string
     */
    public function getImportType()
    {
        return 'tl_group_field';
    }
}
