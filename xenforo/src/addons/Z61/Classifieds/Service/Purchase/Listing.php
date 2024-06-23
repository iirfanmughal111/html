<?php

namespace Z61\Classifieds\Service\Purchase;

class Listing extends \XF\Service\AbstractService
{
    /**
     * @var \Z61\Classifieds\Entity\Listing
     */
    protected $listing;

    public function __construct(\XF\App $app, \Z61\Classifieds\Entity\Listing $listing)
    {
        parent::__construct($app);

        $this->listing = $listing;
    }

    /**
     * @return \Z61\Classifieds\Entity\Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    public function completePurchase()
    {
        $listing = $this->listing;
        $package = $listing->Package;

        $listing->listing_status = 'active';

        if ($package->length_amount)
        {
            $listing->expiration_date = strtotime(
                '+' . $package->length_amount . ' ' . $package->length_unit
            );
        }
        else
        {
            $listing->expiration_date = 0;
        }

        $listing->save();

        return $listing;
    }
}