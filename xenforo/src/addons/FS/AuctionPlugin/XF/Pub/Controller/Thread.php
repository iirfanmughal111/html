<?php

namespace FS\AuctionPlugin\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{

    public function actionIndex(ParameterBag $params)
    {
        $options = $this->app()->options();

        $this->assertNotEmbeddedImageRequest();

        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());

        if ($thread->node_id ==  $options->fs_auction_applicable_forum) {

            $auction = $this->Finder('FS\AuctionPlugin:AuctionListing')->where('thread_id', $thread->thread_id)->fetchOne();

            return $this->redirect(
                $this->buildLink('auction/' . $auction->category_id . '/' . $auction->auction_id . '/view-auction'),
                $auction
            );
        }

        return parent::actionIndex($params);
    }
}
