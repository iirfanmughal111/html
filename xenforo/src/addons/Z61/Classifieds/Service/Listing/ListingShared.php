<?php
namespace Z61\Classifieds\Service\Listing;

use Z61\Classifieds\Entity\Category;
use Z61\Classifieds\Entity\Condition;
use Z61\Classifieds\Entity\Listing;
use Z61\Classifieds\Entity\ListingType;

trait ListingShared
{
    /** @var Listing */
    protected $listing;
    /** @var Category */
    protected $category;
    /** @var Preparer */
    protected $listingPreparer;

    protected $performValidations = true;

    public function setContactOptions($enableConversation, $enableEmail)
    {
        $this->listing->bulkSet([
            'contact_conversation_enable' => $enableConversation,
            'contact_email_enable' => $enableEmail
        ]);
    }

    public function setContactInfo($email = '', $custom = '')
    {
        $this->listing->bulkSet([
            'contact_email' => $email,
            'contact_custom' => $custom
        ]);
    }

    public function setCondition(Condition $condition)
    {
        if (!empty($condition))
        {
            $this->listing->condition_id = $condition->condition_id;
        }
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setListingAttachmentHash($hash)
    {
        $this->listingPreparer->setAttachmentHash($hash);
    }

    public function setPerformValidations($perform)
    {
        $this->performValidations = (bool)$perform;
    }

    public function getPerformValidations()
    {
        return $this->performValidations;
    }

    public function setTitle($title)
    {
        $this->listing->title = $title;
    }

    public function setType(ListingType $listingType)
    {
        $this->listing->listing_type_id = $listingType->listing_type_id;
    }

    public function setLocation($location)
    {
        $this->listing->listing_location = $location;
    }

    public function setPrefix($prefixId)
    {
        $this->listing->prefix_id = $prefixId;
    }

    public function setPrice($price, $currency)
    {
        $this->listing->price = $price;
        $this->listing->currency = $currency;
    }

    public function checkForSpam()
    {
        $this->listingPreparer->checkForSpam();
    }

    /**
     * @param bool $format
     *
     * @return \XF\Service\Message\Preparer
     */
    protected function getMessagePreparer($format = true)
    {
        /** @var \XF\Service\Message\Preparer $preparer */
        $preparer = $this->service('XF:Message\Preparer', 'classifieds_listing', $this->listing);
        if (!$format)
        {
            $preparer->disableAllFilters();
        }

        return $preparer;
    }

    public function finalSetup()
    {
        
    }
}