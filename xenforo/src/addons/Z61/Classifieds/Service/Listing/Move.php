<?php


namespace Z61\Classifieds\Service\Listing;


use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Listing;

class Move extends AbstractService
{
    /**
     * @var Listing
     */
    protected $listing;

    protected $alert = false;
    protected $alertReason = '';

    protected $notifyWatchers = false;

    protected $prefixId = null;

    protected $extraSetup = [];

    public function __construct(\XF\App $app, Listing $listing)
    {
        parent::__construct($app);
        $this->listing = $listing;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setSendAlert($alert, $reason = null)
    {
        $this->alert = (bool)$alert;
        if ($reason !== null)
        {
            $this->alertReason = $reason;
        }
    }

    public function setPrefix($prefixId)
    {
        $this->prefixId = ($prefixId === null ? $prefixId : intval($prefixId));
    }

    public function setNotifyWatchers($value = true)
    {
        $this->notifyWatchers = (bool)$value;
    }

    public function addExtraSetup(callable $extra)
    {
        $this->extraSetup[] = $extra;
    }

    public function move(\Z61\Classifieds\Entity\Category $category)
    {
        $user = \XF::visitor();

        $listing = $this->listing;
        $oldCategory = $listing->Category;

        $moved = ($listing->category_id != $category->category_id);

        foreach ($this->extraSetup AS $extra)
        {
            call_user_func($extra, $listing, $category);
        }

        $listing->category_id = $category->category_id;
        if ($this->prefixId !== null)
        {
            $listing->prefix_id = $this->prefixId;
        }

        if (!$listing->preSave())
        {
            throw new \XF\PrintableException($listing->getErrors());
        }

        $db = $this->db();
        $db->beginTransaction();

        $listing->save(true, false);

        $db->commit();

        if ($moved && $listing->isVisible() && $this->alert && $listing->user_id != $user->user_id)
        {
            /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
            $listingRepo = $this->repository('Z61\Classifieds:Listing');
            $listingRepo->sendModeratorActionAlert($this->listing, 'move', $this->alertReason);
        }

        if ($moved && $this->notifyWatchers)
        {
            /** @var \Z61\Classifieds\Service\Listing\Notify $notifier */
            $notifier = $this->service('Z61\Classifieds:Listing\Notify', $listing);
            if ($oldCategory)
            {
                $notifier->skipUsersWatchingCategory($oldCategory);
            }
            $notifier->notifyAndEnqueue(3);
        }

        return $moved;
    }
}