<?php

namespace Siropu\Chat\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class Message extends AbstractHandler
{
	protected function canActionContent(Report $report)
	{
		$visitor = \XF::visitor();
		return $visitor->hasPermission('siropuChat', 'reportMessages');
	}
	public function setupReportEntityContent(Report $report, Entity $content)
	{
		$report->content_user_id = $content->message_user_id;
		$report->content_info = [
			'room_id'   => $content->Room->room_id,
			'room_name' => $content->Room->room_name,
			'user_id'   => $content->message_user_id,
			'username'  => $content->message_username,
			'message' => [
				'message_id' => $content->message_id,
				'username'   => $content->message_username,
				'text'       => $content->message_text
			]
		];
	}
	public function getContentTitle(Report $report)
	{
		return \XF::phrase('siropu_chat_message_in_room_x', [
			'name' => \XF::app()->stringFormatter()->censorText($report->content_info['room_name'])
		]);
	}
	public function getContentMessage(Report $report)
	{
		return $report->content_info['message']['text'];
	}
	public function getContentLink(Report $report)
	{
		return \XF::app()->router()->buildLink('canonical:chat/archive/message', $report->content_info['message']);
	}
	public function getEntityWith()
	{
		return ['Room', 'User'];
	}
}
