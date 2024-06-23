<?php

namespace FS\ForumButtons\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

use function count, in_array, intval, is_int, strval;

class Forum extends XFCP_Forum
{
    public function actionForum(ParameterBag $params)
	{
      
        $parent =  parent::actionForum($params);
        
        $btn_phrase = \XF::phrase('post_thread');
        
		if (in_array($params->node_id,explode(',',$this->app->options()->fs_register_Reviews_ids))){
			$btn_phrase = \XF::phrase('post_review');
		}else if(in_array($params->node_id,explode(',',$this->app->options()->fs_register_alert_ids))){
			$btn_phrase = \XF::phrase('post_alert');
		}else if(in_array($params->node_id,explode(',',$this->app->options()->fs_register_compainion_ids))){
			$btn_phrase = \XF::phrase('post_compainion');
		}else if(in_array($params->node_id,explode(',',$this->app->options()->fs_register_discussion_ids))){
			$btn_phrase = \XF::phrase('post_discussion');
		}
        $parent->setParam('btn_phrase',$btn_phrase);
        return $parent;
    }

}