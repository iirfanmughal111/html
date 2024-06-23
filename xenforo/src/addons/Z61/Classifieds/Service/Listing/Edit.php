<?php

namespace Z61\Classifieds\Service\Listing;

use XF\Service\AbstractService;
use XF\Service\Tag\Changer;
use Z61\Classifieds\Entity\Listing;

class Edit extends AbstractService
{
    use \XF\Service\ValidateAndSavableTrait;
    use ListingShared;

	protected $alert = false;
    protected $alertReason = '';

    /** @var Changer */
    protected $tagChanger;

    public function __construct(\XF\App $app, Listing $listing)
    {
        parent::__construct($app);
        $this->setListing($listing);

        $this->tagChanger = $this->service('XF:Tag\Changer', 'classifieds_listing', $this->listing);

        $user = \XF::visitor();

        $this->listing->last_edit_user_id = $user->user_id;
        $this->listing->last_edit_date = \XF::$time;

    }

    public function setListing(Listing $listing)
    {
        $this->listing = $listing;
        $this->listingPreparer = $this->service('Z61\Classifieds:Listing\Preparer', $listing);
    }

    public function setCustomFields(array $customFields)
    {
        $listing = $this->listing;

        $editMode = $listing->getFieldEditMode();

        /** @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $listing->custom_fields;
        $fieldDefinition = $fieldSet->getDefinitionSet()
            ->filterEditable($fieldSet, $editMode)
            ->filterOnly($listing->Category->field_cache);

        $customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

        if ($customFieldsShown)
        {
            $fieldSet->bulkSet($customFields, $customFieldsShown, $editMode);
        }
    }

    public function setSendAlert($alert, $reason = null)
    {
        $this->alert = (bool)$alert;
        if ($reason !== null)
        {
            $this->alertReason = $reason;
        }
    }

    public function setTags($tags)
    {
        if ($this->tagChanger->canEdit())
        {
            $this->tagChanger->setEditableTags($tags);
        }
    }

    public function setListingContent($title, $content, $format = true)
    {
        $this->setTitle($title);

        return $this->listingPreparer->setContent($content, $format, $this->performValidations);
    }

    protected function _validate()
    {
        $this->finalSetup();

        $listing = $this->listing;

        $listing->preSave();
        $errors = $listing->getErrors();


        return $errors;
    }

    protected function _save()
    {
        $listing = $this->listing;

        $listing->save(true, false);

        $this->listingPreparer->postUpdate();

        if ($listing->isVisible() && $this->alert && $listing->user_id != \XF::visitor()->user_id)
        {
            /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
            $listingRepo = $this->repository('Z61\Classifieds:Listing');
            $listingRepo->sendModeratorActionAlert($this->listing, 'edit', $this->alertReason);
        }

        // TODO: need to update thread if it exists

        return $listing;
    }
}