<?php

namespace FS\SearchNodeForum\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class SearchForum extends XFCP_SearchForum
{

	public function actionSearch(ParameterBag $params){

		$filters = $this->filterSearchConditions();
		$searchForum = $this->assertViewableSearchForum(
			$params->node_id ?: $params->node_name,
			$this->getSearchForumViewExtraWith()
		);
		if ($this->responseType == 'rss')
		{
			return $this->getSearchForumRss($searchForum);
		}
		
		$page = $this->filterPage($params->page);
		$perPage = $this->options()->discussionsPerPage;

		 $db = \XF::db();
		 $user_id = \XF::visitor()->user_id;
        $threadIds = $db->query("SELECT * FROM `xf_search_forum_cache_user` WHERE user_id = $user_id")->fetch();
		
		if (!$threadIds){
			
		return $this->redirect($this->buildLink('search-forums/', $searchForum), \XF::phrase('fs_search_node_forum_redirect_error'));
			
		}

		$finder = $this->getSearchForumRepo()->getThreadByApplyFilter( explode (",", $threadIds['results']));
		$total  = $finder->total();

		$this->assertValidPage(
			$page,
			$perPage,
			$total,
			'search-forums',
			$searchForum
		);


		$finder->limitByPage($page, $perPage);

		$threads = $finder->fetch();
		$canInlineMod = false;
		foreach ($threads AS $thread)
		{
			if ($thread->canUseInlineModeration())
			{
				$canInlineMod = true;
				break;
			}
		}

		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');
		$nodes = $nodeRepo->getNodeList($searchForum->Node);
		$nodeTree = count($nodes)
			? $nodeRepo->createNodeTree($nodes, $searchForum->node_id)
			: null;
		$nodeExtras = $nodeTree
			? $nodeRepo->getNodeListExtras($nodeTree)
			: null;

			if (!empty($filters['starter_id']))
		{
			$starterFilter = $this->em()->find('XF:User', $filters['starter_id']);
		}
		else
		{
			$starterFilter = null;
		}
		
		$viewParams = [
			'searchForum' => $searchForum,
			'nodeTree' => $nodeTree,
			'nodeExtras' => $nodeExtras,
			'threads' => $threads,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			'filters' => $filters,
			'starterFilter'=> $starterFilter,
			'canInlineMod' => $canInlineMod
		];
		return $this->view(
			'XF:SearchForum\View',
			'search_forum_view',
			$viewParams
		);
	
	
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

		$forum = $this->assertViewableSearchForum($params->node_id ?: $params->node_name);

		$filters = $this->filterSearchConditions();

		if ($this->filter('apply', 'bool'))
		{
			if (!empty($filters['last_days']))
			{
				unset($filters['no_date_limit']);
			}
			return $this->redirect($this->buildLink('search-forums', $forum, $filters));
		}
		if (!empty($filters['starter_id']))
		{

			$starterFilter = $this->em()->find('XF:User', $filters['starter_id']);
		}
		else
		{			
			
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
			'forum' => $forum,
			'prefixes' =>$prefixes['prefixesGrouped'],
			'filters' => $filters,
			'starterFilter' => $starterFilter,
			'allowedThreadTypes' => $allowedThreadTypes,
		];
		return $this->view('FS:SearchNodeForum\FilSearchNodeForumters', 'fs_searchNodeForum_search_filter', $viewParams);

	}

    public function actionView(ParameterBag $params)
	{
        $parent =  parent::actionView($params);
	
        if  ($parent instanceof  \XF\Mvc\Reply\View ) {
            $parent->setParam('filters',$this->filterSearchConditions());	
        }
        
        return $parent;

	}

}