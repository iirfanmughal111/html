<?php

namespace FS\ThreadHash\Cron;

class UpdateHash
{

    public static function ChangeHash()
    {
        $HashGenerator = \XF::service('FS\ThreadHash:HashGenerator');
        $threads = \XF::finder('XF:Thread')->fetch();
        foreach($threads as $thread){
            $thread->fastUpdate('thread_hash',$HashGenerator->getHash($thread->thread_id));
        }
    }


}