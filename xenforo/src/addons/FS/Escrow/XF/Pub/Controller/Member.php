<?php

namespace FS\Escrow\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
    public function actionMyEscrow(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $user = $this->assertViewableUser($params->user_id);
        $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0)->where('user_id', $user->user_id);

        $maxItems = 25;
        $beforeId = $this->filter('before_id', 'uint');
        if (isset($beforeId) && $beforeId != null) {
            $finder->where('escrow_id', '<', $beforeId);
        }

        $items = $finder->order('escrow_id', 'desc')->fetch($maxItems);

        $items = $items->slice(0, $maxItems);

        $lastItem = $items->last();

        $oldestItemId = $lastItem ? $lastItem->escrow_id : 0;

        $viewParams = [
            'escrows' => $items,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'type' => 'my',
            'user' => $user

        ];


        return $this->view('FS\Escrow', 'fs_escrow_escrow_list', $viewParams);
    }

    public function actionMentionedEscrow(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $user = $this->assertViewableUser($params->user_id);
        $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0)->where('to_user', $user->user_id);

        $maxItems = 25;
        $beforeId = $this->filter('before_id', 'uint');
        if (isset($beforeId) && $beforeId != null) {
            $finder->where('escrow_id', '<', $beforeId);
        }

        $items = $finder->order('escrow_id', 'desc')->fetch($maxItems);

        $items = $items->slice(0, $maxItems);

        $lastItem = $items->last();

        $oldestItemId = $lastItem ? $lastItem->escrow_id : 0;
        $viewParams = [
            'escrows' => $items,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'type' => 'mentioned',
            'user' => $user

        ];

        return $this->view('FS\Escrow', 'fs_escrow_escrow_list', $viewParams);
    }
    public function actionLogs(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);

        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $finder = $this->finder('FS\Escrow:Transaction')->where('user_id', $user->user_id)->where('status','!=',0);

        $maxItems = 50;
        $beforeId = $this->filter('before_id', 'uint');
        if (isset($beforeId) && $beforeId != null) {
            $finder->where('transaction_id', '<', $beforeId);
        }

        $logs = $finder->order('transaction_id', 'desc')->fetch($maxItems);
        $logs = $logs->slice(0, $maxItems);

        $lastItem = $logs->last();

        $oldestItemId = $lastItem ? $lastItem->transaction_id : 0;
        $viewParams = [
            'logs' => $logs,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'type' => 'mentioned',
            'user' => $user

        ];

        return $this->view('FS\Escrow', 'fs_escrow_logs', $viewParams);
    }
    
    public function actiontransReport(){
        
        $trans_id=$this->filter('id','int');
        $transction = $this->finder('FS\Escrow:Transaction')->where('transaction_id',$trans_id)->fetchOne();
        
        if(!$transction){
            
            throw $this->exception($this->noPermission());
        }
        
        if($transction->user_id!=\xf::visitor()->user_id){
            
            throw $this->exception($this->noPermission());
        }
      
        $userId=$transction->user_id;
      
        $status=$transction->status;
        $amount=$transction->transaction_amount;
        $title=$this->Title($status);
        $masg=$this->Masg($status,$amount);
        $conversation = $this->conversationCreate($userId,$title,$masg);
        
        $transction->conversation_id=$conversation->conversation_id;
        $transction->save();
       
        return $this->redirect($this->buildLink('conversations', $conversation));
     
        
    }
    
   public function Title($status) {

        if ( $status == 2) {
            return \XF::phrase("fs_escrow_deposit_title_error");

         //   return "Depost issue";
        }

        if ($status == 1) {
        return \XF::phrase("fs_escrow_wirthraw_error");

//            return "Withdraw Issue";
        }
        
       if ($status == 3) {
        return \XF::phrase("fs_escrow_relaase_error");

     //       return "Release Payment Issue";
        }
    }

    public function Masg($status, $amount) {
        
        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
       $dec_trans_amount = $escrowService->decrypt($amount);
        $dec_amount = $dec_trans_amount['amount'];
        if ( $status == 2) {
            return \XF::phrase("fs_escrow_deposit_error_2",['amount'=>$dec_amount]);
         //   return "Depost issue of payment ".$dec_amount."$";
        }

        if ($status == 1) {
            return \XF::phrase("fs_escrow_withdraw_error_2",['amount'=>$dec_amount]);

       //     return "With Draw Issue of payamen" .$amount."$";
        }
        
       if ($status == 3) {
        return \XF::phrase("fs_escrow_release_error_2",['amount'=>$dec_amount]);

          //  return "Release Payment Issue of payment " .$amount."$";
        }
    }

    public function conversationCreate($userId,$title,$msg){
        
        $option = \XF::options();


        $Reciver = \XF::finder('XF:User')
                            ->where('user_id', 1)->fetchOne();
        $recipients = $Reciver->username;


        $user = \XF::finder('XF:User')
                            ->where('user_id', $userId)->fetchOne();
        


        
        $conversationLocked = false;

        $creator = \XF::service('XF:Conversation\Creator', $user);

        $options = [
            'open_invite' => false,
            'conversation_open' => !$conversationLocked,
        ];

        $creator->setOptions($options);
        $creator->setRecipients($recipients);
        $creator->setContent($title, $msg);

        $conversation = $creator->getConversation();

        $conversation = $creator->save();
        
        return $conversation;
    }
}