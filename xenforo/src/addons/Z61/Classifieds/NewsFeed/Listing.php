<?php

namespace Z61\Classifieds\NewsFeed;

use XF\NewsFeed\AbstractHandler;

class Listing  extends AbstractHandler
{
    public function getEntityWith()
    {
        $visitor = \XF::visitor();

        return ['User', 'Featured', 'Category', 'Category.Permissions|' . $visitor->permission_combination_id];
    }

    protected function addAttachmentsToContent($content)
    {
        /** @var \XF\Repository\Attachment $attachmentRepo */
        $attachmentRepo = \XF::repository('XF:Attachment');
        $attachmentRepo->addAttachmentsToContent($content, 'classifieds_listing');

        return $content;
    }
}