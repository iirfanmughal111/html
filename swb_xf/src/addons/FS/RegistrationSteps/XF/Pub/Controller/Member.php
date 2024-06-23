<?php

namespace FS\RegistrationSteps\XF\Pub\Controller;
use XF\Mvc\ParameterBag;
class Member extends XFCP_Member
{
	public function actionMyvouch(ParameterBag $params){
		$user = $this->assertViewableUser($params->user_id);
	
            $lists = $this->finder('FS\RegistrationSteps:Vouch')->where('vouch_from_user_id',$user->user_id)->order('created_at','DESC')->fetch();
            //$vouch = $this->finder('FS\RegistrationSteps:Vouch')->where('vouch_from_user_id',\XF::visitor()->user_id)->where('vouch_to_user_id',$user->user_id)->fetchOne();
            $viewParams = [
			'lists' => $lists,
			//'vouch' => $vouch ? 1 : 0
		];

		return $this->view('FS\RegistrationSteps', 'fs_register_vouch_list', $viewParams);
		}
		



    public function actionAddvouch(ParameterBag $params){
    
        $visitor = \XF::visitor();
        $viewParams = [
			'user_id' => $params->user_id,
			'vouch' => $this->filter('vouch', 'uint')
		];
        if ($visitor->user_id){
		    return $this->view('FS\RegistrationSteps', 'fs_register_vouch_message',$viewParams); 
        }else{
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_not_allowed"))
            );
        }
    }

   public function actionSavevouch(ParameterBag $params){
             $visitor = \XF::visitor();
        $user_id = $this->filter('user_id', 'uint');
        $vouchStatus = $this->filter('vouch', 'uint');
        $user = $this->assertViewableUser($user_id);
	
        if ($visitor->user_id && $user_id != $visitor->user_id && $this->isPost()){
        	
			if ($vouchStatus==2){
			    $vouch = $this->finder('FS\RegistrationSteps:Vouch')->where('vouch_from_user_id',$visitor->user_id)->where('vouch_to_user_id',$user_id)->fetchOne();
			    if ($vouch)
			    {
				    $vouch->delete();
			    }
			    $vouchFrom = $this->finder('FS\RegistrationSteps:Vouch')->where('vouch_to_user_id',$visitor->user_id)->where('vouch_from_user_id',$user_id)->fetchOne();
			    if ($vouchFrom)
			    {
				    $vouchFrom->delete(); 
			    }
			    $redirect_msg = \XF::phrase('fs_register_un_vouch_successfully',['username'=>$user->username]);
			    
		}elseif ($vouchStatus==1) {
                $this->saveProcess($visitor->user_id,$user_id);
                $this->saveProcess($user_id,$visitor->user_id);
                $redirect_msg = \XF::phrase('fs_register_vouch_successfully',['username'=>$user->username]);
            
        }else{
			throw $this->exception(
					$this->notFound(\XF::phrase("fs_register_not_allowed"))
				);
		}
        }else{
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_not_allowed"))
            );
        }
        return $this->redirect($this->buildLink('members/name.'.$user_id.'/#myvouch'),$redirect_msg);
        
     //  return $this->redirect($this->buildLink('members',$user).'/#myvouch');

    }

    public function saveProcess($from,$to){
        $voucher = $this->em()->create('FS\RegistrationSteps:Vouch');
                $voucher->bulkSet([
                'vouch_from_user_id'=> $from,
                'vouch_to_user_id'=> $to,
            ]);
            $voucher->save();
    }
    
        public function actionView(ParameterBag $params)
	{
        $parent =  parent::actionView($params);
	
        $vouch = $this->finder('FS\RegistrationSteps:Vouch')->where('vouch_to_user_id',\XF::visitor()->user_id)->where('vouch_from_user_id',$params->user_id)->fetchOne();
      
        if  ($parent instanceof  \XF\Mvc\Reply\View ) {
            $status = $vouch  ? 2 : 1;
            $parent->setParam('vouch', $status);
        }     
        return $parent;
    }
    
    
    public function actionMailpopup(ParameterBag $params){
        $visitor = \XF::visitor();
        $viewParams = [
			'user_id' => $params->user_id,
		];
        if ($visitor && $visitor->account_type==1){
		    return $this->view('FS\RegistrationSteps', 'fs_register_mail_input',$viewParams); 
        }
    }
    
    public function getMailInputs(){
    	 $inputs =  $this->filter([
            'contact' => 'str',
            'date' => 'str',
            'duration' => 'str',
            'time' => 'str',
            'contact_mobile' => 'str',
            'contact_email' => 'str',
            'type' => 'str',
            'city' => 'str',
            'user_id' => 'uint',

        ]); 
      
        
         if ($inputs['date'] == '' || $inputs['duration'] == '' || $inputs['time'] == '' || $inputs['type'] == '' || $inputs['city'] == '' || $inputs['user_id'] == '') {
         	throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_fill_all_fields"))
            );
         
         }
      
        return  $inputs;
       
    }

    public function actionSendmail(){
     

        $inputs = $this->getMailInputs();
        
        $contact = '';
       
         if ($inputs['contact_mobile'] ){
            $contact = $inputs['contact_mobile'];
            
        }else if($inputs['contact_email']  ){
            $contact = $inputs['contact_email'];
        }else{
        	throw $this->exception(
                $this->notFound(\XF::phrase("fs_register_add_contact_detail"))
            );
        }
     	$appoint = $this->em()->create('FS\RegistrationSteps:Appointment');
      	$appoint->bulkSet([
                'time'=> $inputs['time'],
                'date'=> $inputs['date'],
                'from_user_id'=> \XF::visitor()->user_id,
                'to_user_id'=> $inputs['user_id'],
                'contact'=> $contact,
                'duration'=> $inputs['duration'],
                'appt_type'=> 'Appointment',
                'city'=> $inputs['city'],               
                
            ]);
            $appoint->save();
            
            
         $user = $this->assertViewableUser($inputs['user_id']);
        //$user = $this->finder('XF:User')->where('user_id',$inputs['user_id'])->fetchOne();
     
        if ( $user && $user->email){
        $mail = $this->app->mailer()->newMail()->setTo($user->email);
        $mail->setTemplate('male_email_send_template', [
            'date' => $inputs['date'],
            'duration' => $inputs['duration'],
            'contact' => $contact,
            'city' => $inputs['city'],
            'type' => $inputs['type'],
            'time' => $inputs['time'],

        ]);
        $mail->send();
    }
    return $this->redirect($this->getDynamicRedirect(),\XF::phrase('fs_register_email_send'));
     
    
    }
    
        public function actionAddreview(ParameterBag $params){
        
        
		$visitor = \XF::visitor();
		    if ($params->user_id == $visitor->user_id  || !($visitor->user_id)){
			    throw $this->exception(
				$this->notFound(\XF::phrase("fs_register_not_allowed"))
			    );
			}
			$user = $this->assertViewableUser($params->user_id);
			if ($user &&  $user->account_type==1){
			    throw $this->exception(
				$this->notFound(\XF::phrase("fs_register_can_not_review_male"))
			    );
			}
        
		if (!$visitor->canCreateThread($error) && !$visitor->canCreateThreadPreReg())
		{
			return $this->noPermission($error);
		}

		$nodeRepo = $this->repository('XF:Node');
		$reviews_ids = explode(',',\XF::app()->options()->fs_register_Reviews_ids);
		$parentFinder = $nodeRepo->findNodesForList();
		$parentIds = $parentFinder->where('node_id',$reviews_ids )->pluckfrom('parent_node_id')->fetch()->toArray();
		$forum_ids = array_merge($parentIds,$reviews_ids);
							
		$finder = $nodeRepo->findNodesForList();
		$nodes = $finder->where('node_id',$forum_ids )->fetch();
		$nodeTree = $nodeRepo->createNodeTree($nodes);
				
	
		$nodeExtras = $nodeRepo->getNodeListExtras($nodeTree);

		$viewParams = [
			'nodeTree' => $nodeTree,
			'nodeExtras' => $nodeExtras,
			'user_id' => $params->user_id,
		];
		return $this->view('XF:Forum\PostThreadChooser', 'forum_post_review_chooser', $viewParams);
        
    }
    
			public function getSWBuserItems($ids,$beforeId,$user_id){
				$finder = $this->finder('XF:Thread')->where('node_id', $ids)->where('review_for', $user_id);

					$maxItems = 25;
				
				if (isset($beforeId) && $beforeId != null) {
				$finder->where('thread_id', '<', $beforeId);
				}

				$items = $finder->order('post_date', 'desc')->fetch($maxItems);
				$items = $items->slice(0, $maxItems);
				return $items;

			}
        public function actionReviews(ParameterBag $params)
    {
        $visitor = \XF::visitor();
     
        $user = $this->assertViewableUser($params->user_id);
        $reviews_ids = explode(',',\XF::app()->options()->fs_register_Reviews_ids);
		$beforeId = $this->filter('before_id', 'uint');
        $items = $this->getSWBuserItems( $reviews_ids,$beforeId,$user->user_id);

        $lastItem = $items->last();

        $oldestItemId = $lastItem ? $lastItem->thread_id : 0;

        $viewParams = [
            'reviews' => $items,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'user' => $user
        ];

        return $this->view('FS\RegistrationSteps', 'fs_register_user_review_list', $viewParams);
    }
    
    
    
       public function actionAlert(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);
        $alert_ids = explode(',',\XF::app()->options()->fs_register_alert_ids);
     
        $beforeId = $this->filter('before_id', 'uint');
		   
		$items = $this->getSWBuserItems( $alert_ids,$beforeId,$user->user_id);
        $lastItem = $items->last();
        $oldestItemId = $lastItem ? $lastItem->thread_id : 0;

        $viewParams = [
            'alerts' => $items,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'user' => $user
        ];

        return $this->view('FS\RegistrationSteps', 'fs_register_user_alert_list', $viewParams);
    }
	
		public function actionAds(ParameterBag $params){
		
		 $user = $this->assertViewableUser($params->user_id);
		 $compainion_ids = explode(',',\XF::app()->options()->fs_register_compainion_ids);
			
		 $beforeId = $this->filter('before_id', 'uint');
		   
		 $items = $this->getAdsuserItems($compainion_ids,$beforeId,$user->user_id);
			
        $lastItem = $items->last();
        $oldestItemId = $lastItem ? $lastItem->thread_id : 0;

        $viewParams = [
            'ads' => $items,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'user' => $user
        ];

        return $this->view('FS\RegistrationSteps', 'fs_register_user_ads_list', $viewParams);
		
	
	}
	public function getAdsuserItems($ids,$beforeId,$user_id){
				$finder = $this->finder('XF:Thread')->where('node_id', $ids)->where('user_id', $user_id);

					$maxItems = 25;
				
				if (isset($beforeId) && $beforeId != null) {
				$finder->where('thread_id', '<', $beforeId);
				}

				$items = $finder->order('post_date', 'desc')->fetch($maxItems);
				$items = $items->slice(0, $maxItems);
				return $items;

			
	}

    
}
