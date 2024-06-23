<?php

namespace Siropu\Chat\EditHistory;

use XF\Mvc\Entity\Entity;

class Message extends \XF\EditHistory\AbstractHandler
{
	public function canViewHistory(Entity $content)
	{
		return ($content->canView() && $content->canViewHistory());
	}
	public function canRevertContent(Entity $content)
	{
		return $content->canEdit();
	}
	public function getContentTitle(Entity $content)
	{
		return \XF::phrase('message');
	}
	public function getContentText(Entity $content)
	{
		return $content->message_text;
	}
	public function getContentLink(Entity $content)
	{
		return \XF::app()->router()->buildLink('chat/message/view', $content);
	}
	public function getBreadcrumbs(Entity $content)
	{
		return [];
	}
	public function revertToVersion(Entity $content, \XF\Entity\EditHistory $history, \XF\Entity\EditHistory $previous = null)
	{
		$content->message_text = $history->old_text;
		$content->save();
	}
	public function getHtmlFormattedContent($text, Entity $content = null)
	{
		return \XF::app()->templater()->fn('bb_code', [$text, 'siropu_chat_room_message', $content]);
	}
	public function getSectionContext()
	{
		return 'room';
	}
	public function getEditCount(Entity $content)
	{
		return $content->message_edit_count;
	}
}
