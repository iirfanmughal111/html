<?php

namespace Z61\Classifieds\Alert;

use XF\Alert\AbstractHandler;

class Question extends AbstractHandler
{
    public function getEntityWith()
    {
        $visitor = \XF::visitor();

        return ['Listing', 'Listing.Category', 'Listing.Category.Permissions|' . $visitor->permission_combination_id];
    }

    public function getOptOutActions()
    {
        return [
            'insert',
            'quote',
            'mention',
            'reaction'
        ];
    }

    public function getOptOutDisplayOrder()
    {
        return 300;
    }
}