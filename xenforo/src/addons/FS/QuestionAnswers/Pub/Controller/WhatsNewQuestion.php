<?php

namespace FS\QuestionAnswers\Pub\Controller;

use XF\Pub\Controller\AbstractWhatsNewFindType;

/**
 * Class WhatsNewQuestion
 *
 * @package  FS\QuestionAnswers\Pub\Controller
 */
class WhatsNewQuestion extends AbstractWhatsNewFindType
{
	/**
	 * @return string
	 */
	protected function getContentType(): string
	{
		return 'fs_question';
	}
}