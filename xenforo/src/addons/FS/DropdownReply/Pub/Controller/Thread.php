<?php 
namespace FS\DropdownReply\Pub\Controller;
use XF\Mvc\ParameterBag;


class Thread extends XFCP_Thread
{
/**
	 * @param \XF\Entity\Thread $thread
	 *
	 * @return \XF\Service\Thread\Replier
	 */


	 
	public function actionAddReply(ParameterBag $params)
	{
		
		$thread = $this->Finder('XF:Thread')->where('thread_id',$params->thread_id)->fetchOne();
		
		if ($thread->is_dropdown_active==1){
            $post = $this->Finder('XF:Post')->where('thread_id',$thread->thread_id)->where('user_id',\XF::visitor()->user_id)->where('message_state','!=','deleted')->fetch();
            if ($thread->user_id == \XF::visitor()->user_id){
		        $msg_count =  (count($post)-1);
            
            }
            else{
				$msg_count =  count($post);
            }

			if ($msg_count>=1){
			return $this->error(\XF::phrase('message_not_allowed'));
			}else{
				return parent::actionAddReply($params);
				

			}
        }
        else{
			return parent::actionAddReply($params);
        }
	
	}

}