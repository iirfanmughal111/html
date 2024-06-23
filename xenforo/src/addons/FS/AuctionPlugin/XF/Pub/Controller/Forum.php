<?php

namespace FS\AuctionPlugin\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    public function actionIndex(ParameterBag $params)
    {
        if ($params->node_id ==  $this->app()->options()->fs_auction_applicable_forum) {

            return $this->redirect($this->buildLink('auction/'), '');
        }

        return parent::actionIndex($params);
    }
    protected function setupThreadCreate(\XF\Entity\Forum $forum)
    {
        $parent = parent::setupThreadCreate($forum);

        $options = $this->app()->options();

        if ($forum->node_id ==  $options->fs_auction_applicable_forum) {

            $input = $this->filter([
                'ends_on' => 'str',
                'ends_on_time' => 'str',
            ]);

            $tmpTime = explode(':', $input['ends_on_time']);
            $h = $tmpTime[0] * 3600;
            $m = $tmpTime[1] * 60;
            $tmpDate = strtotime($input['ends_on'] . ' 00:00 America/Los_Angeles');

            $end_date_time = ($tmpDate + $h + $m);

            $parent->setauctionDateTime($end_date_time);
        }

        return $parent;
    }

    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator)
    {
        $parent = parent::finalizeThreadCreate($creator);

        $thread = $creator->getThread();

        $options = $this->app()->options();

        if ($thread->node_id ==  $options->fs_auction_applicable_forum) {
            $cat_id = $this->filter('category_id', 'int');

            $insertInAuction = $this->em()->create('FS\AuctionPlugin:AuctionListing');

            $insertInAuction->category_id = $cat_id;
            $insertInAuction->thread_id = $thread['thread_id'];


            $insertInAuction->save();

            $insertInAuction->save();

            if ($insertInAuction) {

                \XF::db()->query('update fs_auction_category set auctions_count = auctions_count + 1 where category_id =' . $cat_id);
            }
        }

        return $parent;
    }

    public function actionPostThread(ParameterBag $params)
    {
        $parent =  parent::actionPostThread($params);

        $options = $this->app()->options();

        if ($params->node_id ==  $options->fs_auction_applicable_forum && !$this->isPost()) {
            $cat_id = $this->filter("category_id", 'int');

            $parent->setParam('category_id', $cat_id);
        }
        return $parent;
    }
}
