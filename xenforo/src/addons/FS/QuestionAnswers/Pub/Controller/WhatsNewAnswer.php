<?php

namespace FS\QuestionAnswers\Pub\Controller;

use XF\Pub\Controller\AbstractWhatsNewFindType;

/**
 * Class WhatsNewAnswer
 *
 * @package  FS\QuestionAnswers\Pub\Controller
 */
class WhatsNewAnswer extends AbstractWhatsNewFindType
{
	/**
	 * @return string
	 */
	protected function getContentType(): string
	{
		return 'fs_answer';
	}
}