<?php

namespace FS\QuestionAnswers;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;
        
        public function installStep1()
	{      
            $sm = $this->schemaManager();

            $sm->alterTable('xf_user', function(Alter $table)
            {
                    $table->addColumn('question_count', 'int',10)->setDefault(0);
                    $table->addColumn('answer_count', 'int',10)->setDefault(0);
            });
	}
        
        public function uninstallStep1()
	{
            
            $sm = $this->schemaManager();

            $sm->alterTable('xf_user', function(Alter $table)
            {
                    $table->dropColumns(['question_count','answer_count']);
            });
        }
        
        
        public function postInstall(array &$stateChanges)
	{       
            $this->updateUsersQuestionAnswerCount();
	}
        
        public function postUpgrade($previousVersion, array &$stateChanges)
	{
             $this->updateUsersQuestionAnswerCount();
	}
        
        
        public function updateUsersQuestionAnswerCount()
        {
            $app = \XF::app();
            $db = \XF::db();          
            
            
            $questionForumId = intval(\XF::options()->fs_questionAnswerForum);
            
            if(!$questionForumId)
            {
                // if questionForumId is not set installation-time then for the clint's site questionForumId is 39 so I put it hardCode for now.
                $questionForumId = 39;
            }
            

            $allQuestionThreadIds = $app->finder('XF:Thread')
                                        ->where('node_id', $questionForumId)
                                        ->where('discussion_type', 'question')
                                        ->pluckFrom('thread_id')->fetch()->toArray();

            $allQuestionThreadIds = implode(',', $allQuestionThreadIds);
            
            
            if($allQuestionThreadIds)
            {
                $users = $app->finder('XF:User')->where('message_count', '>', 0)->fetch();

                foreach($users as $user)
                {
                   $userQuestionThreads = $app->finder('XF:Thread')
                                       ->where('user_id', $user->user_id)
                                       ->where('node_id', $questionForumId)
                                       ->where('discussion_type', 'question')
                                       ->where('discussion_state', 'visible');


                   $questionCount = $userQuestionThreads->total();
                   
                   $sql = 'select sum(post_count) as answerCount from xf_thread_user_post where thread_id IN ('. $allQuestionThreadIds .') AND user_id = '. $user->user_id;

                   $postCount = $db->query($sql)->fetch()['answerCount'];

                   $answerCount = ($postCount? $postCount - $questionCount: 0);


                   $user->fastUpdate([
                               'question_count' => $questionCount,
                               'answer_count' => $answerCount
                           ]);

                }
            }
        }
}