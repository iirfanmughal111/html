<?php


namespace Z61\Classifieds\Service\Listing;
use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Listing;

class Approve extends AbstractService
{
    /**
     * @var Listing
     */
    protected $listing;

    protected $notifyRunTime = 3;

    public function __construct(\XF\App $app, Listing $listing)
    {
        parent::__construct($app);
        $this->listing = $listing;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setNotifyRunTime($time)
    {
        $this->notifyRunTime = $time;
    }

    public function approve()
    {
        if ($this->listing->listing_state == 'moderated')
        {
            $this->listing->listing_state = 'visible';
            $this->listing->save();

            /** @var \Z61\Classifieds\Service\Listing\Notify $notifier */
            $notifier = $this->service('Z61\Classifieds:Listing\Notify', $this->listing);
            $notifier->notifyAndEnqueue($this->notifyRunTime);
            return true;
        }
        else
        {
            return false;
        }
    }
}