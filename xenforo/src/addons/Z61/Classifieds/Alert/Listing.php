<?php

namespace Z61\Classifieds\Alert;

use XF\Alert\AbstractHandler;

class Listing extends AbstractHandler
{
    public function getEntityWith()
    {
        $visitor = \XF::visitor();

        return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
    }

    public function getOptOutActions()
    {
        return [
            'insert',
            'mention',
            'like'
        ];
    }

    public function getOptOutDisplayOrder()
    {
        return 200;
    }
}