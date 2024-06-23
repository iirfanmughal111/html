<?php

namespace FS\QuestionAnswers\XF\Admin\Controller;

class Option extends XFCP_Option 
{

    public function actionUpdate()
    {        
        $this->assertPostOnly();

        $input = $this->filter([
                'options_listed' => 'array-str',
                'options' => 'array'
        ]);

        $options = $input['options'];
        
        $parent = parent::actionUpdate();

        if(array_key_exists('fs_questionAnswerForum', $options))
        {
            $this->updateQuestionAnswersNav($options);
            $this->updateQuestionAnswerRouteFilter($options);
        }
       
        return $parent;       
    }
    
    
    
    
    public function updateQuestionAnswersNav($options)
    {
        $navigation = $this->assertNavigationExists('fs_questionAnswer_nav');

        if($navigation)
        {
            $typeId = $navigation->navigation_type_id;
            if($typeId == 'node')
            {
                $questionForumId = $options['fs_questionAnswerForum'];
                
                if($questionForumId)
                {
                        $typeConfig =  [
                            "node_id" => $questionForumId,
                            "node_title" => "1",
                            "extra_attr_names" => [
                                "0" => ""
                            ],
                            "extra_attr_values" => [
                                "0" => ""
                            ]
                      ];

                    $navigation->setTypeFromInput($typeId, $typeConfig);
                    $navigation->enabled = 1;
                }
                else
                {
                    $navigation->enabled = 0;
                }
                
                $navigation->save();
            }
        }
    }
    
    
    public function updateQuestionAnswerRouteFilter($options)
    {
        $routeFilterId = $options['fs_qa_routeFilterId'];
        
        if($routeFilterId)
        {
            $routeFilter = $this->assertRouteFilterExists($routeFilterId);            
            if($routeFilter)
            {
                $questionForumId = $options['fs_questionAnswerForum'];
                if($questionForumId)
                {
                    $questionForum = $this->em()->find('XF:Forum',$questionForumId);

                    if($questionForum)
                    {   
                        $forumLink = $this->buildLink('forums', $questionForum);

                        $findRoute = explode('?', $forumLink);

                        if($findRoute[1])
                        {
                            $routeFilter->find_route = $findRoute[1];
                            $routeFilter->enabled = 1;
                            $routeFilter->save();
                        }
                    }
                }
                else
                {
                    $routeFilter->enabled = 0;
                    $routeFilter->save();
                }
            }
        }
    }
    
    
    protected function assertNavigationExists($id, $with = null, $phraseKey = null)
    {
            return $this->assertRecordExists('XF:Navigation', $id, $with, $phraseKey);
    }
    
    protected function assertRouteFilterExists($id, $with = null, $phraseKey = null)
    {
            return $this->assertRecordExists('XF:RouteFilter', $id, $with, $phraseKey);
    }
    
}
