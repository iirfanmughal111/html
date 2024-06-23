<?php

namespace FS\SearchNodeForum\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Search extends XFCP_Search
{

	public function actionExplore(ParameterBag $params){


		$filters = $this->filterSearchConditions();
        
        $search = $this->em()->find('XF:Search', $params->search_id);

		$page = $this->filterPage();
		$perPage = $this->options()->searchResultsPerPage;
		
		$threadResult = $this->getThreadsData($search);
		$postResult = $this->getPostsData($search);
		$searchRsults = array_merge($threadResult,$postResult);

			if (count($searchRsults)<1){
			return $this->message(\XF::phrase('no_results_found'));
		}
		
         $total  = count($searchRsults);

        
		$this->assertValidPage(
			$page,
			$perPage,
			$total,
			'search/explore',
			$search
		);
		$searcher = $this->app()->search();
       
		$resultSet = $searcher->getResultSet($searchRsults);

		$resultSet->sliceResultsToPage($page, $perPage);
	
		if (!$resultSet->countResults())
		{
			return $this->message(\XF::phrase('no_results_found'));
		}

		$maxPage = ceil($total / $perPage);

		
	 $getOlderResultsDate = null;
		

		$resultOptions = [
			'search' => $search,
			'term' => $search->search_query
		];
		$resultsWrapped = $searcher->wrapResultsForRender($resultSet, $resultOptions);

		$modTypes = [];
		foreach ($resultsWrapped AS $wrapper)
		{
			$handler = $wrapper->getHandler();
			$entity = $wrapper->getResult();
			if ($handler->canUseInlineModeration($entity))
			{
				$type = $handler->getContentType();	
				if (!isset($modTypes[$type]))
				{
					$modTypes[$type] = $this->app->getContentTypePhrase($type);
				}

			}
		}

		$mod = $this->filter('mod', 'str');
		if ($mod && !isset($modTypes[$mod]))
		{
			$mod = '';
		}
	
		
			if (!empty($filters['starter_id']))
		{
			$starterFilter = $this->em()->find('XF:User', $filters['starter_id']);
		}
		else
		{
			$starterFilter = null;
		}
		
		$viewParams = [
			'search' => $search,
			'results' => $resultsWrapped,
            'filters' => $filters,
			'page' => $page,
			'perPage' => $perPage,
            'total'=>$total, 
			'modTypes' => $modTypes,
			'activeModType' => $mod,
			'starterFilter'=>$starterFilter,
			'getOlderResultsDate' => $getOlderResultsDate
		];
		return $this->view(
			'XF:Search\Results', 
            'search_results',
			$viewParams
		);
	
	
	}

	protected function getThreadsData($search){
		
		$threadIds=[];
		if(isset($search['search_results']) && count($search['search_results'])>0){
		array_filter($search['search_results'], function($key) use(&$threadIds) {
			
					$a = explode('-',$key);
					if($a[0]=='thread')
					{
						$threadIds[] = $a[1];

					}
								
				return strpos($key, 'thread-') === 0;
				}, ARRAY_FILTER_USE_KEY);
			
		}
		
		
		$finder = $this->repository('XF:SearchForum')->getThreadByApplyFilter( $threadIds);

        $threads = $finder->pluckfrom('thread_id')->fetch()->toArray();
		$searchRsults =[];
         foreach ($threads as $thread){
			$value = [];
			$value[] = 'thread';
			$value[] = $thread;
			$searchRsults['thread-'.$thread] = $value;  
		}

		return $searchRsults;
	
	}


	protected function getPostsData($search){
		$inputs = \XF::app()->request->filter([
			'prefix_id' => 'uint',
		]);
		
		$postIds=[];
		if(isset($search['search_results']) && count($search['search_results'])>0 && !$inputs['prefix_id']){
		array_filter($search['search_results'], function($key) use(&$postIds) {
			
					$a = explode('-',$key);
					if($a[0]=='post')
					{
						$postIds[] = $a[1];

					}
								
				return strpos($key, 'post-') === 0;
				}, ARRAY_FILTER_USE_KEY);
			
		}

		$finder = $this->repository('FS\SearchNodeForum\XF:Post')->getPostByApplyFilter($postIds);
		
        $posts = $finder->pluckfrom('post_id')->fetch()->toArray();
		$searchRsults =[];
         foreach ($posts as $post){
			$value = [];
			$value[] = 'post';
			$value[] = $post;
			$searchRsults['post-'.$post] = $value;  
		}
		return $searchRsults;
	
	}

	
    protected function filterSearchConditions()
	{
		$filters = [];
		$inputs=  $this->filter([
			'prefix_id' => 'uint',
			'starter_id' => 'int',
			'starter' => 'str',
			'last_days' => 'int',
			'order' => 'str',
			'direction' => 'str',
			'no_date_limit' => 'bool',
			'thread_type' => 'str'
		]);

		if ($inputs['prefix_id'] && $inputs['prefix_id'] != 0){
			
			$filters['prefix_id'] =  $inputs['prefix_id'];
		}
		if ($inputs['starter_id'])
		{
			$filters['starter_id'] = $inputs['starter_id'];
		}
		else if ($inputs['starter'])
		{
			$user = $this->em()->findOne('XF:User', ['username' => $inputs['starter']]);
			if ($user)
			{
				$filters['starter_id'] = $user->user_id;
			//	$filters['starter'] = $inputs['starter'];

			}
		}
		
		if ($inputs['last_days'] && $inputs['last_days'] > 0){
			$filters['last_days'] = $inputs['last_days'];
			
		}
		if ($inputs['thread_type'] ){
			
			$filters['thread_type'] = $inputs['thread_type'];
		}

		if ($inputs['order'] ){
		
			$filters['order'] = $inputs['order'];
		}
		if ($inputs['direction'] ){
		
			$filters['direction'] = $inputs['direction'];
		}
		
		return $filters;
	}
    
    protected function getAvailableDateLimits( )
	{
		return [-1, 7, 14, 30, 60, 90, 182, 365];
	}
    
    public function actionFilters(ParameterBag $params)
	{
        if (!$params->search_id)
		{
			return $this->rerouteController(__CLASS__, 'index', $params);
		}

     $search = $this->em()->find('XF:Search', $params->search_id);
		$filters = $this->filterSearchConditions();

		if ($this->filter('apply', 'bool'))
		{
			if (!empty($filters['last_days']))
			{
				unset($filters['no_date_limit']);
			}
			return $this->redirect($this->buildLink('search',  $filters));
		}
		if (!empty($filters['starter_id']))
		{

			$starterFilter = $this->em()->find('XF:User', $filters['starter_id']);
		}
		else
		{			
			// 
			$starterFilter = null;
		}
	
		$allowedThreadTypes = [];
		if (isset($forum['search_criteria']['thread_type'])){
		    foreach ($forum['search_criteria']['thread_type'] AS $threadType)
		        {
				    $allowedThreadTypes[] = $threadType;	
		        }   
	    }
		/** @var \XF\Repository\ThreadPrefix $prefixRepo */
		$prefixRepo = \XF::repository('XF:ThreadPrefix');
 		$prefixes = $prefixRepo->getVisiblePrefixListData();
		
		$viewParams = [
			'forum' => $search,
			'prefixes' =>$prefixes['prefixesGrouped'],
			'filters' => $filters,
			'starterFilter' => $starterFilter,
			'allowedThreadTypes' => $allowedThreadTypes,
		];
		return $this->view('FS:SearchNodeForum\FilSearchNodeForumters', 'fs_advance_search_node_filter', $viewParams);

	}

    public function actionResults(ParameterBag $params)
	{
        $parent =  parent::actionResults($params);
	
        if  ($parent instanceof  \XF\Mvc\Reply\View ) {
            $parent->setParam('filters',$this->filterSearchConditions());
			
		$filters = $this->filterSearchConditions();
     
        }     
        return $parent;
    }


}