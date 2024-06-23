<?php

namespace FS\UpdateUrl\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class UpdateUrl extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        
        if (!(\XF::visitor()->is_admin)) {
            return $this->message( \XF::phrase('fs_update_url_not_allowwd'));
        } 
            
       $thread = $this->Finder('XF:Thread')->whereId($params->thread_id)->fetchOne();
       
        if (!$thread){
            return $this->message( \XF::phrase('fs_update_url_thread_not_founnd'));
        }

        $viewpParams = [
            'thread' => $thread, 
        ];

        return $this->view('FS\UpdateUrl', 'index_url_update', $viewpParams);

    }
   
    public function actionSave()
    {
        $input = [];
        $input = $this->filter([
            'url_string' => 'str',
            'thread_id' => 'int',
        ]);
        $options = \XF::options();   
        
        
    $thread = $this->Finder('XF:Thread')->whereId($input['thread_id'])->fetchOne();
    if ($thread){
        $thread->bulkSet([
            'url_string'=> substr($input['url_string'],0,$options->fs_updateUrl_limit)
        ]);
       $thread->save();
    }else{
        return $this->message( \XF::phrase('fs_update_url_thread_not_founnd'));
    }

        return $this->redirect($this->buildLink('threads/'.$input['thread_id']), \XF::phrase('fs_update_url_success'));
        
    }

}