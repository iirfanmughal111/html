<?php

namespace FS\AuctionPlugin\Notifier\Listing;

use XF\Notifier\AbstractNotifier;
use FS\AuctionPlugin\Entity\AuctionListing;

class Auction extends AbstractNotifier
{
    /** @var Auction $auction */
    private $auc;

    public function __construct(\XF\App $app, AuctionListing $auc)
    {
        parent::__construct($app);

        $this->auc = $auc;
    }

    public function canNotify(\XF\Entity\User $user)
    {
        return true;
    }

    public function sendAlert(\XF\Entity\User $user)
    {

        $visitor = \XF::visitor();

        $auc = $this->auc;

        return $this->basicAlert(
            $auc->User,
            $visitor->user_id,
            $visitor->username,
            'fs_auction',
            $auc->auction_id,
            'auction_bid',
            [
                'category_id' => $auc->category_id,
                'title' => $auc->title
            ]
        );
    }
}
