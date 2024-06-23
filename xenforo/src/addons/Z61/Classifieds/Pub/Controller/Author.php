<?php


namespace Z61\Classifieds\Pub\Controller;


use XF\Mvc\ParameterBag;

class Author extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();

        if (!$visitor->canViewClassifieds($error))
        {
            throw $this->exception($this->noPermission($error));
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        if ($params->user_id)
        {
            return $this->rerouteController('Z61\Classifieds:Author', 'Author', $params);
        }

        /** @var \XF\Entity\MemberStat $memberStat */
        $memberStat = $this->em()->findOne('XF:MemberStat', ['member_stat_key' => 'classifieds_most_listings']);

        if ($memberStat && $memberStat->canView())
        {
            return $this->redirectPermanently(
                $this->buildLink('members', null, ['key' => $memberStat->member_stat_key])
            );
        }
        else
        {
            return $this->redirect($this->buildLink('classifieds'));
        }
    }

    public function actionAuthor(ParameterBag $params)
    {
        /** @var \XF\Entity\User $user */
        $user = $this->assertRecordExists('XF:User', $params->user_id);

        $viewableCategoryIds = $this->repository('Z61\Classifieds:Category')->getViewableCategoryIds();

        /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
        $listingRepo = $this->repository('Z61\Classifieds:Listing');
        $finder = $listingRepo->findListingsByUser($user->user_id, $viewableCategoryIds);

        $total = $finder->total();

        $page = $this->filterPage();
        $perPage = $this->options()->z61ClassifiedsListingsPerPageList;

        $this->assertValidPage($page, $perPage, $total, 'classifieds/authors', $user);
        $this->assertCanonicalUrl($this->buildLink('classifieds/authors', $user, ['page' => $page]));

        $listings = $finder->limitByPage($page, $perPage)->fetch();
        $listings = $listings->filterViewable();

        $canInlineMod = false;
        foreach ($listings AS $listing)
        {
            /** @var \Z61\Classifieds\Entity\Listing $listing */
            if ($listing->canUseInlineModeration())
            {
                $canInlineMod = true;
                break;
            }
        }

        $viewParams = [
            'user' => $user,
            'listings' => $listings,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'canInlineMod' => $canInlineMod
        ];
        return $this->view('Z61\Classifieds:Author\View', 'z61_classifieds_author_view', $viewParams);
    }

    public static function getActivityDetails(array $activities)
    {
        return \XF::phrase('z61_classifieds_viewing_listings');
    }
}