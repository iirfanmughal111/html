<?php

namespace Z61\Classifieds\Pub\Controller;

use XF\Mvc\ParameterBag;

class Featured extends \XF\Pub\Controller\AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        // TODO: Implement paid featuring
        $user = \XF::visitor();
        $valid = $this->finder('Z61\Classifieds:ListingFeature')->where('user_id', $user->user_id)->where('expiration_date', '>', \XF::$time)->fetch();
        $expired = $this->finder('Z61\Classifieds:ListingFeature')->where('user_id', $user->user_id)->where('expiration_date', '<', \XF::$time)->fetch();
        $viewParams = [
            'featured' => $valid,
            'expired' => $expired,
            'available' => []
        ];
        return $this->view('Z61\Classifieds:Listing\Featured', 'z61_classifieds_feature_listings', $viewParams);
    }
}