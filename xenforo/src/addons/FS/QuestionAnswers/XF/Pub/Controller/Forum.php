<?php

namespace FS\QuestionAnswers\XF\Pub\Controller;
use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{

    	public function actionList(ParameterBag $params)
	{
            
            
                return parent::actionList($params);
                
                
                
                
//		$this->assertNotEmbeddedImageRequest();
//
//		if ($params->node_id || $params->node_name)
//		{
//			$forum = $this->assertViewableForum($params->node_id ?: $params->node_name);
//			return $this->redirectPermanently($this->buildLink('forums', $forum));
//		}
//
//		$selfRoute = ($this->options()->forumsDefaultPage == 'forums' ? 'forums' : 'forums/list');
//
//		$this->assertCanonicalUrl($this->buildLink($selfRoute));
//
//		$nodeRepo = $this->getNodeRepo();
//		$nodes = $nodeRepo->getNodeList();
//                
//               
//                //------------ Remove QuestionAnswers Forum from forum list ---------------------------
//                
//                $questionForumId = intval(\XF::options()->fs_questionAnswerForum);
//                unset($nodes[$questionForumId]);
//                
//                //-------------------------------------------------------------------------------------
//
//
//		$nodeTree = $nodeRepo->createNodeTree($nodes);                
//		$nodeExtras = $nodeRepo->getNodeListExtras($nodeTree);
//                
//		$viewParams = [
//			'nodeTree' => $nodeTree,
//			'nodeExtras' => $nodeExtras,
//			'selfRoute' => $selfRoute
//		];
//		return $this->view('XF:Forum\Listing', 'forum_list', $viewParams);
	}
    
}
