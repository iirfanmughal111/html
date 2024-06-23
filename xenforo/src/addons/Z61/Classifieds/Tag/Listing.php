<?php

namespace Z61\Classifieds\Tag;

use XF\Mvc\Entity\Entity;
use XF\Tag\AbstractHandler;

class Listing extends AbstractHandler
{
    public function getPermissionsFromContext(Entity $entity)
    {
        if ($entity instanceof \Z61\Classifieds\Entity\Listing)
        {
            $listing = $entity;
            $category = $listing->Category;
        }
        else if ($entity instanceof \Z61\Classifieds\Entity\Category)
        {
            $listing = null;
            $category = $entity;
        }
        else
        {
            throw new \InvalidArgumentException("Entity must be a listing or category");
        }

        $visitor = \XF::visitor();

        if ($listing)
        {
            if ($listing->user_id == $visitor->user_id && $listing->hasPermission('manageOtherTagsOwnListing'))
            {
                $removeOthers = true;
            }
            else
            {
                $removeOthers = $listing->hasPermission('manageAnyTag');
            }

            $edit = $listing->canEditTags();
        }
        else
        {
            $removeOthers = false;
            $edit = $category->canEditTags();
        }

        return [
            'edit' => $edit,
            'removeOthers' => $removeOthers,
        ];

    }

    public function getContentDate(Entity $entity)
    {
        return $entity->listing_date;
    }

    public function getContentVisibility(Entity $entity)
    {
        return $entity->listing_state == 'visible';
    }

    public function getTemplateData(Entity $entity, array $options = [])
    {
        return [
            'listing' => $entity,
            'options' => $options
        ];
    }

}