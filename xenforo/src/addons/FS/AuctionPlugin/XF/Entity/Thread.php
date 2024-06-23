<?php

namespace FS\AuctionPlugin\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['auction_end_date'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }

    public function getFormatedTime()
    {
        $tempDate = new \DateTime('@' . $this->auction_end_date);
        $date =  date_timezone_set($tempDate, timezone_open('America/Los_Angeles'));
        return $date->format("H:i");
    }

    public function getFormatedTime12()
    {
        $tempDate = new \DateTime('@' . $this->auction_end_date);
        $date =  date_timezone_set($tempDate, timezone_open('America/Los_Angeles'));
        return $date->format("F j Y, h:i A");
    }

    public function getMaxBidOfAuction($auction_id)
    {
        $maxBid = $this->finder('FS\AuctionPlugin:Bidding')->where('auction_id', $auction_id)->order('bidding_amount', 'desc')->fetchOne();
        if ($maxBid) {
            return $maxBid->bidding_amount;
        } else {
            return false;
        }
    }

    public function getFormatedDate()
    {
        $tempDate = new \DateTime('@' . $this->auction_end_date);
        $date =  date_timezone_set($tempDate, timezone_open('America/Los_Angeles'));
        return $date->format("y-m-d");
    }
}
