<?php

namespace FS\DropdownReply\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class dropdownReply extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {

        if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
            return $this->message('Not Allowed');
            } 
            
       $thread = $this->Finder('XF:Thread')->whereId($params->thread_id)->fetchOne();


        $viewpParams = [
            'dropdownReplys' => $thread, 


        ];

        return $this->view('FS\DropdownReply', 'index_dropdownReply', $viewpParams);

    }



    public function actionAdd(ParameterBag $params)
    {

        if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
            
            return $this->message('Not Allowed');
            } 
        $viewpParams = [
            'thread_id' => $params['thread_id']
        ];
            return $this->view('FS\DropdownReply', 'addEdit_dropdownReply', $viewpParams);
        
    }


    public function actionSave()
    {
        $input = [];
        $input = $this->filter([

            'status' => 'int',
            'options' => 'array',
            'thread_id' => 'int',

        ]);
        
        $tempOptionArray = array();
        foreach ($input['options'] as $key => $option){
            
            if (!empty($option)){
                $tempOptionArray[] = $option;
            }
        }
        $input['options'] = $tempOptionArray;
        
        if (!count(array_filter($input['options']))){
           return $this->error('Please add at least one option');
        }
        
    $thread = $this->Finder('XF:Thread')->whereId($input['thread_id'])->fetchOne();
;
        $thread->bulkSet([
            'dropdwon_options'=> $input['options'],
            'is_dropdown_active'=> $input['status'],
        ]);
       $thread->save();

        return $this->redirect($this->buildLink('opt-reply/'.$input['thread_id']), 'Dropdown Reply Added Successfully.', 'permanent');
        
    }

    public function actionEdit(ParameterBag $params)
    {
         if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
            return $this->message('Not Allowed');
            }  
            
        if ($params->thread_id){
            $thread = $this->Finder('XF:Thread')->where('thread_id',$params->thread_id)->fetchOne();

            $viewParams = ['thread'=>$thread];       
            
                return $this->view('FS\DropdownReply', 'addEdit_dropdownReply', $viewParams);
           
    
          
        return $this->message('Edit');
    }
}


    public function actionUpdateSingle(ParameterBag $params){
        if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
            return $this->message('Not Allowed');
            } 
   
        if ($params->thread_id){
            $thread = $this->Finder('XF:Thread')->where('thread_id',$params->thread_id)->fetchOne();
            $indexParam = $this->filter('id','int');

            $input = $this->filter([
                'arrayIndex' => 'int',
                'option' => 'str',
    
            ]);
            
            $arr = $thread->dropdwon_options;
                
            $arr[$input['arrayIndex']] = $input['option'];
           
            $thread->dropdwon_options = $arr;
         
           $thread->save();
           return $this->redirect($this->buildLink('opt-reply/'.$params->thread_id), 'Dropdown Reply Added Successfully.', 'permanent');
           
            
        }
       
    }


    public function actionEditSingle(ParameterBag $params){
      
        if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
            return $this->message('Not Allowed');
            } 
            
        if ($params->thread_id){
            $thread = $this->Finder('XF:Thread')->where('thread_id',$params->thread_id)->fetchOne();
            
            $viewParams = [
                'thread_id' => $params->thread_id,
                'array_index' =>$this->filter('id','int'),
                'array_value' =>$thread->dropdwon_options[$this->filter('id','int')]

            ];
         
            
            if (\XF::visitor()->is_admin || \XF::visitor()->is_moderator) {
                return $this->view('FS\DropdownReply', 'editSingleOption', $viewParams);
            } else {
                return $this->message('You are not allowed to add this');
            }
            
            
        }
       
    }


    
    public function actionDeleteSingle(ParameterBag $params){


        if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
            return $this->message('Not Allowed');
            } 
      
     
           if($this->isPost()){
        
             $thread = $this->Finder('XF:Thread')->where('thread_id',$this->filter('thread_id','int'))->fetchOne();
            
             $threadOptions = $thread->dropdwon_options;

             unset($threadOptions[$this->filter('id','int')]);
       
             $thread->dropdwon_options = $threadOptions;

            $thread->save();

            return $this->redirect($this->buildLink('opt-reply/'.$this->filter('thread_id','int')));
        }

        $thread = $this->Finder('XF:Thread')->where('thread_id',$params->thread_id)->fetchOne();
        $threadOptions = $thread->dropdwon_options;
           

            $viewpParams=[
                
                'id'=>$this->filter('id','int'),
                'thread_id'=>$params->thread_id,
                'confirmUrl'=>$this->buildLink('/opt-reply/'.$params->thread_id.'/single-delete'),
                'optiontitle'=>$threadOptions[$this->filter('id','int')],
            ];
      
            return $this->view('FS\DropdownReply', 'single_option_delete', $viewpParams);
                          
            
    }

    

    /**
   * @param string $id
   * @param array|string|null $with
   * @param null|string $phraseKey
   *
   * @return \CRUD\XF\Entity\Crud
   */
  protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
  {
      return $this->assertRecordExists('XF:Thread', $id, $extraWith, $phraseKey);
  }

  public function actionDelete(ParameterBag $params)
    {
        /**  @var \FS\ForumAutoReply\Entity\ForumAutoReply $replyExists */
        $replyExists = $this->assertDataExists($params->thread_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');

        if ($this->isPost()) {

            $this->preDeletethread($replyExists);

            return $this->redirect($this->buildLink('opt-reply/'.$replyExists->thread_id));
        }

        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('opt-reply/delete', $replyExists),
            $this->buildLink('opt-reply/edit', $replyExists),
            $this->buildLink('dropdownreply'),
            \XF::phrase('fs_are_you_sure_to_delete')
        );
    }
  public function preDeletethread($thread){
    $thread->bulkSet([
        'dropdwon_options'=> null,
        'is_dropdown_active'=> 0,
    ]);
 

    $thread->save();
    

    }


    public function preDeletethreadSingle($thread,$index){
        
        $threadOptions = $thread->dropdwon_options;
        
       unset($threadOptions[$index]);
       $thread->dropdwon_options = $threadOptions;
       $thread->save();
                
        }

        public function permissionCheck(){
            if (!(\XF::visitor()->is_admin || \XF::visitor()->is_moderator)) {
                return $this->message('Not Allowed');
                } 
        }
}