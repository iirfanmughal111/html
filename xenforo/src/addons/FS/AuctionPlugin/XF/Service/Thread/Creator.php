<?php

namespace FS\AuctionPlugin\XF\Service\Thread;

class Creator extends XFCP_Creator
{
    protected $auction_end_date;

    public function setauctionDateTime($end_date_time)
    {
        $this->thread->auction_end_date = $end_date_time;
    }
}
