<?php


namespace FS\ThreadHash\Service;

use XF\Service\AbstractService;


class HashGenerator extends AbstractService
{
    public function getHash($id){
        return substr(hash('sha256', mt_rand()  . $id . time()), 0, 20);
    }

    public function ExistedThreadHash(){
       
        $threads = $this->finder('XF:Thread')->fetch();
        foreach($threads as $thread){
            $thread->fastUpdate('thread_hash',$this->getHash($thread->thread_id));
        }
    }

}