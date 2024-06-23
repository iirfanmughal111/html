<?php

/*************************************************************************
 * Profile Views - XenConcept (c) 2017
 * All Rights Reserved.
 **************************************************************************
 * This file is subject to the terms and conditions defined in the Licence
 * Agreement available at Try it like it buy it :)
 *************************************************************************/

namespace XenConcept\ProfileViews\Widget;

use XF\Widget\AbstractWidget;

class RecentViewers extends AbstractWidget
{
    protected $defaultOptions = [
        'limit' => 10,
        'display_number' => true,
        'display_user' => 'avataruser'
    ];

    public function render()
    {
        $options = $this->options;
        /** @var \XenConcept\ProfileViews\XF\Entity\User $user */
        $user = $this->contextParams['user'];

        if (!$user->canViewUsersViewedProfile($error))
        {
            return false;
        }

        $profileViewsFinder = $this->getProfileViewsRepo()->findLogProfileViews($user);
        $userViewersTotal = $profileViewsFinder->total();

        $userViewers = $profileViewsFinder->fetch($options['limit']);

        $viewParams = [

            'user' => $user,
            'userViewers' => $userViewers,
            'showAll' => false, //($userViewersTotal - count($userViewers)),


            'displayUser' => $options['display_user'],
            'displayNumber' => $options['display_number']

        ];

        return $this->renderer('xc_profile_views_recent_viewers_widget', $viewParams);
    }

    public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
    {
        $options = $request->filter([
            'limit' => 'uint',
            'display_number' => 'bool',
            'display_user' => 'str'
        ]);

        return true;
    }

    /**
     * @return string|null
     */
    public function getOptionsTemplate()
    {
        return 'admin:xc_profile_views_widget_def_options_recent_viewers';
    }

    /**
     * @return \XenConcept\ProfileViews\Repository\ProfileViews
     */
    protected function getProfileViewsRepo()
    {
        return $this->repository('XenConcept\ProfileViews:ProfileViews');
    }
}