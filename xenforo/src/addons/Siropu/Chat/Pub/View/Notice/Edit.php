<?php

namespace Siropu\Chat\Pub\View\Notice;

class Edit extends \XF\Mvc\View
{
	public function renderJson()
	{
		$class      = \XF::app()->extendClass('Siropu\Chat\Data');
		$chatData   = new $class();

		$noticeHtml = '';

		if ($notice = $chatData->getNotice($this->getParams()['notice']))
		{
			$noticeHtml = \XF::app()->templater()->renderMacro('public:siropu_chat_notice_macros', 'notice', [
				'notice' => $notice
			]);
		}

		return [
			'message' => \XF::phrase('siropu_chat_notice_saved'),
			'notice'  => $noticeHtml
		];
	}
}
