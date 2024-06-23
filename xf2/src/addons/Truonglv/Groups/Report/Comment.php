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

class Comment extends AbstractHandler
{
    /**
     * @param Report $report
     * @return \XF\Phrase
     */
    public function getContentTitle(Report $report)
    {
        return XF::phrase('tlg_comment_in_group_x', [
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
        if (!($content instanceof \Truonglv\Groups\Entity\Comment)) {
            return;
        }

        $group = $content->Group;
        if (!($group instanceof \Truonglv\Groups\Entity\Group)) {
            return;
        }

        $report->content_user_id = $content->user_id;
        $report->content_info = [
            'comment_id' => $content->comment_id,
            'message' => $content->message,
            'user_id' => $content->user_id,
            'username' => $content->username,
            'group_id' => $group->group_id,
            'group_name' => $group->name
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
        return XF::app()
            ->router('public')
            ->buildLink('group-comments', ['comment_id' => $report->content_info['comment_id']]);
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['full'];
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_report_content_comment';
    }

    /**
     * @param mixed $id
     * @return \XF\Mvc\Entity\ArrayCollection|Entity|null
     */
    public function getContent($id)
    {
        $entities = parent::getContent($id);

        if ($entities !== null) {
            App::commentRepo()->addContentIntoComments($entities);
        }

        return $entities;
    }

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
}
