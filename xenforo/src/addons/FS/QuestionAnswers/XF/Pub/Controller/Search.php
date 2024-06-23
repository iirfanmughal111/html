<?php

namespace FS\QuestionAnswers\XF\Pub\Controller;
use XF\Mvc\ParameterBag;

class Search extends XFCP_Search
{
    	public function actionMember()
	{
		$this->assertNotEmbeddedImageRequest();

		$userId = $this->filter('user_id', 'uint');
		$user = $this->assertRecordExists('XF:User', $userId, null, 'requested_member_not_found');

		$constraints = ['users' => $user->username];

		$searcher = $this->app->search();
		$query = $searcher->getQuery();
		$query->byUserId($user->user_id)
			->orderedBy('date');

		$content = $this->filter('content', 'str');
		$type = $this->filter('type', 'str');
                
		if ($content && $searcher->isValidContentType($content))
		{
			$typeHandler = $searcher->handler($content);
                        
			$query->forTypeHandlerBasic($typeHandler);
			// this applies the type limits that make sense

			$constraints['content'] = $content;

			$grouped = $this->filter('grouped', 'bool');
			if ($grouped)
			{
				$query->withGroupedResults();
			}
		}
		else if ($type && $searcher->isValidContentType($type))
		{
			$query->inType($type);
			$constraints['type'] = $type;
		}
                
		$threadType = $this->filter('thread_type', 'str');
                
		if ($threadType && $query->getTypes() == ['thread'])
		{
			$query->withMetadata('thread_type', $threadType);
			$constraints['thread_type'] = $threadType;
		}

                
                //----- apply question&Answers node constrains ------
                    $nodeIds = $this->filter('qa_nodes', 'array-uint');

                    $nodeIds = array_unique($nodeIds);
                    if ($nodeIds && reset($nodeIds))
                    {
                        $query->withMetadata('node', $nodeIds);
                    }
                //----------------------------------------------------------
                
		$before = $this->filter('before', 'uint');
		if ($before)
		{
			$query->olderThan($before);
		}

		return $this->runSearch($query, $constraints, false);
	}
}