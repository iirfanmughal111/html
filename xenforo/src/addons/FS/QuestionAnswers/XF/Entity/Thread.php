<?php

namespace FS\QuestionAnswers\XF\Entity;

use XF\Entity\Post;

class Thread extends XFCP_Thread
{   
    public function postAdded(Post $post)
    {
        $parent = parent::postAdded($post);
        
         $questionForumId = intval(\XF::options()->fs_questionAnswerForum);

        if ($this->first_post_id && $questionForumId && $this->node_id == $questionForumId && $this->discussion_type == 'question')
        {
            $post->User->fastUpdate('answer_count',($post->User->answer_count+1));
        }

        return $parent;
    }

    public function postRemoved(Post $post)
    {
        $parent = parent::postRemoved($post);
        
        $questionForumId = intval(\XF::options()->fs_questionAnswerForum);

        if ($questionForumId && $this->node_id == $questionForumId && $this->discussion_type == 'question')
        {
            $post->User->fastUpdate('answer_count',($post->User->answer_count-1));
        }

        return $parent;
    }
    
        
}