<?php

namespace FS\QuestionAnswers\Widget;

use XF\Widget\AbstractWidget;

class questionAnswerStatistics extends AbstractWidget
{
	public function render()
	{
        // $finder = $this->app->finder('XF:User');
        $questionAnswerCount = \XF::db()->fetchAll('SELECT SUM(question_count) as question,SUM(answer_count) as ans FROM xf_user');
       
        
		$viewParams = [
			'questionAnswerCount' => $questionAnswerCount[0],
		];
		return $this->renderer('fs_widget_ques_ans_statistics', $viewParams);
	}

	public function getOptionsTemplate()
	{
		return null;
	}
}