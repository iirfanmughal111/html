<?php

namespace FS\AuctionPlugin\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{

    public function actionEdit(ParameterBag $params)
    {

        $parent = parent::actionEdit($params);

        $post = $this->assertViewablePost($params->post_id, ['Thread.Prefix']);

        $forum = $post->Thread->Forum;

        $options = $this->app()->options();


        if ($forum->node_id == $options->fs_auction_applicable_forum && $this->isPost() && $post->isFirstPost()) {

            $input = $this->filter([
                'ends_on' => 'str',
                'ends_on_time' => 'str',
            ]);

            $tmpTime = explode(':', $input['ends_on_time']);
            $h = $tmpTime[0] * 3600;
            $m = $tmpTime[1] * 60;
            $tmpDate = strtotime($input['ends_on'] . ' 00:00 America/Los_Angeles');

            $end_date_time = ($tmpDate + $h + $m);

            $thread = $post->Thread;
            $thread->auction_end_date = $end_date_time;
            $thread->save();
        }

        return $parent;
    }
}
