<?php


namespace XenBulletins\Tournament\Cron;


class Convsersation {
    
    
    

    public static function userconversation() {
      
       $ctime = \XF::$time;
       
     $tournaments = \XF::finder('XenBulletins\Tournament:Tournament')->fetch();
     
     
     foreach($tournaments as $tour){
         
         
        $tournamentone = \XF::finder('XenBulletins\Tournament:Tournament')->where('tourn_id', $tour['tourn_id'])->fetchOne();
           
      
        
        if ($ctime > $tournamentone['tourn_startdate'] and $tournamentone['conversation']=='0'){
            
            
    $users = \XF::Finder('XenBulletins\Tournament:Register')->with('User')->where('tourn_id', $tournamentone['tourn_id'])->fetch();

    

    
              
       if($users){
           
           foreach($users as $user){
               
               
              
               
                //echo '<pre>';
              self::conversationuser($user->User->username,$tournamentone->tourn_title,$tournamentone);
             
                 \XF::db()->update('xf_tournament', [
                    'conversation' => 1,
                        ], 'tourn_id = ?', $tournamentone['tourn_id']);
           }
           
       }
    
  
    
    
            
        }
           
        
     }
     
     
     

        
        
        
        
    }
     
        public static function conversationuser($username,$tourn_title,$tournamentone){
            
            
          
        
        $option = \XF::options();





         $recipients = $username;
        
        
        

        if ($option->tourn_user_conversation_id) {


            $user = \XF::finder('XF:User')
                            ->where('user_id', $option->tourn_user_conversation_id)->fetchOne();
        } else {

            $user = \XF::finder('XF:User')
                            ->where('user_id', 1)->fetchOne();
        }


        $title =$tourn_title;

        
        $msg=self::getThreadMessage($username,$tourn_title,$tournamentone);
        
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
    }
    
    
      protected static function  getThreadMessage($username, $tourn_title,$tournamentone) {


        $phrase = \XF::phrase('tournament_message_body', [
                    'username' => $username,
                    'title' => $tourn_title,
                    'link' => \XF::app()->router('public')->buildLink('canonical:uptourn')."#tournament-".$tournamentone->tourn_id
        ]);

        return $phrase->render('raw');
    }
    

}



