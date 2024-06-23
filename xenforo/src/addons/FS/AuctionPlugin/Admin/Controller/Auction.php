<?php

namespace FS\AuctionPlugin\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Auction extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('fs_auction');
    }

    public function actionIndex()
    {
        return $this->view('FS\AuctionPlugin:Auction', 'fs_auction');
    }
}
