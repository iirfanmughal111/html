<?php

namespace FS\QuestionAnswers\Option;
use XF\Option\AbstractOption;

class Forum extends AbstractOption
{
	public static function renderQuestionAnswersSelect(\XF\Entity\Option $option, array $htmlParams)
	{
		$data = self::getSelectData($option, $htmlParams);

		return self::getTemplater()->formSelectRow(
			$data['controlOptions'], $data['choices'], $data['rowOptions']
		);
	}

//	public static function renderSelectMultiple(\XF\Entity\Option $option, array $htmlParams)
//	{
//		$data = self::getSelectData($option, $htmlParams);
//		$data['controlOptions']['multiple'] = true;
//		$data['controlOptions']['size'] = 8;
//
//		return self::getTemplater()->formSelectRow(
//			$data['controlOptions'], $data['choices'], $data['rowOptions']
//		);
//	}

	protected static function getSelectData(\XF\Entity\Option $option, array $htmlParams)
	{
		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = \XF::repository('XF:Node');

		$choices = $nodeRepo->getNodeOptionsData(true, 'Forum', 'option');
               
                
		$choices = array_map(function($v) {
                    
			$v['label'] = \XF::escapeString($v['label']);
                       
			return $v;
		}, $choices);
                                                
                $questionNodeIds = \XF::finder('XF:Forum')->where('forum_type_id','question')->pluckFrom('node_id')->fetch()->toArray();
                
                $filteredChoices[] = $choices[0];
                foreach($questionNodeIds as $questionNodeId)
                {              
                    if(isset($choices[$questionNodeId]))
                    {
                        $filteredChoices[]= $choices[$questionNodeId];
                    }
                }

		return [
			'choices' => $filteredChoices,
			'controlOptions' => self::getControlOptions($option, $htmlParams),
			'rowOptions' => self::getRowOptions($option, $htmlParams)
		];
	}
}