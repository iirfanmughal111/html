<?php

namespace Z61\Classifieds\Bookmark;


use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Listing extends AbstractHandler
{
    public function getContentTitle(Entity $content)
    {
        return \XF::phrase('z61_classifieds_listing_x', [
            'title' => $content->title
        ]);
    }

    public function getContentRoute(Entity $content)
    {
        return 'classifieds';
    }
}