<?php

namespace Z61\Classifieds\Pub\Controller;

use XF\Pub\Controller\AbstractWhatsNewFindType;

class WhatsNewListing extends AbstractWhatsNewFindType
{
    protected function getContentType()
    {
        return 'classifieds_listing';
    }

}