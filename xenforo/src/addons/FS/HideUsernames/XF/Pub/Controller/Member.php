<?php

namespace FS\HideUsernames\XF\Pub\Controller;


class Member extends XFCP_Member
{

    public function actionFind()
    {
        $q = ltrim($this->filter('q', 'str', ['no-trim']));

        if ($q !== '' && utf8_strlen($q) >= 2) {
            /** @var \XF\Finder\User $userFinder */
            $userFinder = $this->finder('XF:User');

            $conditions = [
                ['random_name', 'like', $userFinder->escapeLike($q, '?%')],
                ['username', 'like', $userFinder->escapeLike($q, '?%')],
              
            ];

            $users = $userFinder
                ->whereOr($conditions)
                // ->where('username', 'like', $userFinder->escapeLike($q, '?%'))
                ->isValidUser(true)
                ->fetch(10);
        } else {
            $users = [];
            $q = '';
        }

        $viewParams = [
            'q' => $q,
            'users' => $users
        ];
        return $this->view('XF:Member\Find', '', $viewParams);
    }
}
