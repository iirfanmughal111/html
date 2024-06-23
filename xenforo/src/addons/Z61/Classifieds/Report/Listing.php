<?php

namespace Z61\Classifieds\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class Listing extends AbstractHandler
{
    public function setupReportEntityContent(Report $report, Entity $content)
    {
        /** @var \Z61\Classifieds\Entity\Listing $listing */
        $listing = $content;
        $category = $listing->Category;

        if (!empty($listing->prefix_id))
        {
            $title = $listing->Prefix->title . ' - ' . $listing->title;
        }
        else
        {
            $title = $listing->title;
        }

        $report->content_user_id = $listing->user_id;
        $report->content_info = [
            'listing' => [
                'listing_id' => $listing->listing_id,
                'title' => $title,
                'prefix_id' => $listing->prefix_id,
                'category_id' => $listing->category_id,
                'user_id' => $listing->user_id,
                'username' => $listing->username,
                'content' => $listing->content
            ],
            'category' => [
                'category_id' => $category->category_id,
                'title' => $category->title
            ]
        ];
    }

    public function getContentTitle(Report $report)
    {
        return \XF::phrase('z61_classifieds_listing_x', [
            'title' => \XF::app()->stringFormatter()->censorText($report->content_info['listing']['title'])
        ]);
    }

    public function getContentMessage(Report $report)
    {
        return $report->content_info['listing']['content'];
    }

    public function getContentLink(Report $report)
    {
        $info = $report->content_info;

        return \XF::app()->router()->buildLink(
            'canonical:classifieds',
            [
                'listing_id' => $info['listing']['listing_id'],
                'title' => $info['listing']['title'],
            ]
        );
    }

    public function getEntityWith()
    {
        return ['Category'];
    }

    protected function canActionContent(Report $report)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        $categoryId = $report->content_info['listing']['category_id'];

        if (!method_exists($visitor, 'hasClassifiedsCategoryPermission'))
        {
            return false;
        }

        return (
            $visitor->hasClassifiedsCategoryPermission($categoryId, 'deleteAny')
            || $visitor->hasClassifiedsCategoryPermission($categoryId, 'editAny')
        );
    }

    protected function canViewContent(Report $report)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        $categoryId = $report->content_info['listing']['category_id'];

        if (!method_exists($visitor, 'hasClassifiedsCategoryPermission'))
        {
            return false;
        }

        return $visitor->hasClassifiedsCategoryPermission($categoryId, 'view');
    }

}