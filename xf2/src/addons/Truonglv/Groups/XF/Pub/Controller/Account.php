<?php

namespace Truonglv\Groups\XF\Pub\Controller;

use Truonglv\Groups\XF\Entity\UserOption;

class Account extends XFCP_Account
{
    /**
     * @param \XF\Entity\User $visitor
     * @return \XF\Mvc\FormAction
     */
    protected function savePrivacyProcess(\XF\Entity\User $visitor)
    {
        $form = parent::savePrivacyProcess($visitor);

        $input = $this->filter([
            'privacy' => [
                'tlg_allow_view_groups' => 'str',
            ],
        ]);
        $userPrivacy = $visitor->getRelationOrDefault('Privacy');
        $form->setupEntityInput($userPrivacy, $input['privacy']);

        return $form;
    }

    /**
     * @param \XF\Entity\User $visitor
     * @return \XF\Mvc\FormAction
     */
    protected function preferencesSaveProcess(\XF\Entity\User $visitor)
    {
        $form = parent::preferencesSaveProcess($visitor);

        if ($this->app()->options()->tl_groups_enableBadge > 0) {
            /** @var UserOption $option */
            $option = $visitor->Option;
            $form->setupEntityInput($option, [
                'tlg_show_badge' => $this->filter('tlg_show_badge', 'bool'),
            ]);
        }

        return $form;
    }
}
