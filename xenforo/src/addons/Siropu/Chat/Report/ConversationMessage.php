<?php

namespace Siropu\Chat\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class ConversationMessage extends AbstractHandler
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
			'user_id'  => $content->message_user_id,
			'username' => $content->message_username,
			'message' => [
				'message_id' => $content->message_id,
				'username'   => $content->message_username,
				'text'       => $content->message_text
			]
		];
	}
	public function getContentTitle(Report $report)
	{
		return \XF::phrase('siropu_chat_message_in_conversation_with_x', ['username' => $report->content_info['username']]);
	}
	public function getContentMessage(Report $report)
	{
		return $report->content_info['message']['text'];
	}
	public function getContentLink(Report $report)
	{

	}
}
