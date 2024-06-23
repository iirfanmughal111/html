<?php

namespace FS\MovePost\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post {

   

    public function actionMovePost(ParameterBag $params) {

        if (!\XF::visitor()->hasPermission('fs_move_post_perm_group', 'fs_move_post_as_thread')){
            return $this->noPermission();
        }
        $type = 'post';
        $handler = $this->getInlineModHandler($type);

        $action = $this->filter('action', 'str');
        $action = 'move';
        
        $actionHandler = $handler->getAction($action);

        $redirect = $this->getDynamicRedirect();
        $confirmed = false;

        if ($this->isPost()){
		    $confirmed = $this->filter('confirmed', 'bool');
        }
        
        if ($confirmed)
        {
            if (!$this->request->exists('ids'))
            {
                return $this->error('Developer: No ids param submitted.');
            }

            $ids = $this->filter('ids', 'array-uint');
            $ids = array_unique($ids);
        }
        else
        {
            $ids[] = $params->post_id;
        }
        
        $entities = $handler->getEntities($ids);

        if (!$entities->count())
        {
            return $this->redirect($redirect);
        }

        if ($confirmed)
        {
            $options = $actionHandler->getFormOptions($entities, $this->request);
        }
        else
        {
            $options = [];
        }
        
        if ($confirmed){
		    $actionHandler->apply($entities, $options);
        }
        
        $reply = $this->redirect($actionHandler->getReturnUrl() ?: $redirect);
        $actionHandler->postApply($entities, $reply, $this->app->response());
        
        if ($confirmed){
           return $reply;
        }
        
		$nodeRepo = $this->app()->repository('XF:Node');
		$nodes = $nodeRepo->getFullNodeList()->filterViewable();
		$viewParams = [
			'posts' => $entities,
			'total' => count($entities),
			'nodeTree' => $nodeRepo->createNodeTree($nodes),
			'first' => $entities->first(),
			'prefixes' => $entities->first()->Thread->Forum->getUsablePrefixes()
		];
		return $this->view('FS\MovePost', 'fs_movePost_inline_mod_post_move', $viewParams);
        
        }
        
        protected function getInlineModHandler($type)
        {
            return $this->plugin('XF:InlineMod')->getInlineModHandler($type);
        }
    
}