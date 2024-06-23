<?php

namespace FS\QuestionAnswers\XF\Entity;

use XF\Entity\Thread;

class Forum extends XFCP_Forum
{    
    
    public function threadAdded(Thread $thread)
    {
        $parent = parent::threadAdded($thread);
        
        $questionForumId = intval(\XF::options()->fs_questionAnswerForum);
        
        if ($questionForumId && $thread->node_id == $questionForumId && $thread->discussion_type == 'question')
        {
            $user = $thread->User;
            $user->fastUpdate('question_count',($user->question_count+1));
            
            if($thread->reply_count)
            {
                $this->updateUsersAnswersCount($thread, 'added');
            }
        }
        
        return $parent;
    }
    
    
    public function threadRemoved(Thread $thread)
    {
        $parent = parent::threadRemoved($thread);
        
        $questionForumId = intval(\XF::options()->fs_questionAnswerForum);
        
        if ($questionForumId && $thread->node_id == $questionForumId && $thread->discussion_type == 'question')
        {
            $user = $thread->User;
            $user->fastUpdate('question_count',($user->question_count-1));
            
            if($thread->reply_count)
            {
                $this->updateUsersAnswersCount($thread, 'removed');
            }
        }
        
        return $parent;
        
    }
    
    
    
    public function updateUsersAnswersCount(Thread $thread, $action)
    {
        $threadUserPosts = $this->finder('XF:ThreadUserPost')->with('User')->where('thread_id', $thread->thread_id)->fetch();

        if($threadUserPosts)
        {                       
            foreach ($threadUserPosts as $threadUserPost)
            {
                $answerCount = $threadUserPost->post_count;

                if($threadUserPost->user_id == $thread->user_id)
                {
                    // subtract 1 from post_count because thread's first post count is also included in post_count so ...
                    $answerCount = $answerCount-1;
                }

                if($answerCount)
                {
                    $user = $threadUserPost->User;
                    
                    if($action == 'added')
                    {
                        $newAnswerCount = $user->answer_count + $answerCount;
                    }
                    else
                    {
                        $newAnswerCount = $user->answer_count - $answerCount;
                    }
                    
                    $user->fastUpdate('answer_count', $newAnswerCount);
                }
            }
        }
    }
    
}