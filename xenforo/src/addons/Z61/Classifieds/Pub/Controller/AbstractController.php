<?php


namespace Z61\Classifieds\Pub\Controller;


class AbstractController extends \XF\Pub\Controller\AbstractController
{
    protected function assertViewableCategory($categoryId, array $extraWith = [])
    {
       
        $visitor = \XF::visitor();

        $extraWith[] = 'Permissions|' . $visitor->permission_combination_id;

        /** @var \Z61\Classifieds\Entity\Category $category */
        $category = $this->em()->find('Z61\Classifieds:Category', $categoryId, $extraWith);

        if (!$category)
        {
            throw $this->exception($this->notFound(\XF::phrase('z61_classifieds_requested_category_not_found')));
        }
        if (!$category->canView($error))
        {
            throw $this->exception($this->noPermission($error));
        }

        return $category;
    }

    protected function assertViewableListing($listingId, array $extraWith = [])
    {
        

        $visitor = \XF::visitor();

        $extraWith[] = 'User';
        $extraWith[] = 'Category';
        $extraWith[] = 'Category.Permissions|' . $visitor->permission_combination_id;
        $extraWith[] = 'Discussion';
        $extraWith[] = 'Discussion.Forum';
        $extraWith[] = 'Discussion.Forum.Node';
        $extraWith[] = 'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id;

        /** @var \Z61\Classifieds\Entity\Listing $listing */
        $listing = $this->em()->find('Z61\Classifieds:Listing', $listingId, $extraWith);
        if (!$listing)
        {
            throw $this->exception($this->notFound(\XF::phrase('z61_classifieds_requested_listing_not_found')));
        }

        if (!$listing->canView($error))
        {
            throw $this->exception($this->noPermission($error));
        }

        return $listing;
    }
}