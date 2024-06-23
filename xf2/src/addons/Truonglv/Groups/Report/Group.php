<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Report;

use XF;
use XF\Entity\Report;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class Group extends AbstractHandler
{
    /**
     * @param Report $report
     * @return bool
     */
    protected function canActionContent(Report $report)
    {
        $visitor = XF::visitor();

        return ($visitor->hasPermission(App::PERMISSION_GROUP, 'editGroupAny')
            || $visitor->hasPermission(App::PERMISSION_GROUP, 'deleteGroupAny'));
    }

    /**
     * @param Report $report
     * @return \XF\Phrase
     */
    public function getContentTitle(Report $report)
    {
        return XF::phrase('tlg_group_x', [
            'name' => XF::app()->stringFormatter()->censorText($report->content_info['group_name'])
        ]);
    }

    /**
     * @param Report $report
     * @param Entity $content
     * @return void
     */
    public function setupReportEntityContent(Report $report, Entity $content)
    {
        if (!($content instanceof \Truonglv\Groups\Entity\Group)
            || $content->Category === null
        ) {
            return;
        }

        $report->content_user_id = $content->owner_user_id;
        $report->content_info = [
            'message' => $content->description,
            'user_id' => $content->owner_user_id,
            'username' => $content->owner_username,
            'category_id' => $content->Category->category_id,
            'category_title' => $content->Category->category_title,
            'group_id' => $content->group_id,
            'group_name' => $content->name
        ];
    }

    /**
     * @param Report $report
     * @return string
     */
    public function getContentMessage(Report $report)
    {
        return $report->content_info['message'];
    }

    /**
     * @param Report $report
     * @return string
     */
    public function getContentLink(Report $report)
    {
        return XF::app()->router('public')->buildLink('groups', ['group_id' => $report->content_info['group_id']]);
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['Category'];
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_report_content_group';
    }
}
