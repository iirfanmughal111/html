<?php

namespace FS\HideUsernames\XF\Repository;

class User extends XFCP_User
{
    /**
     * @param array $usernames
     * @param array $notFound
     * @param array $with
     * @param bool $validOnly
     * @param array $extraWhere
     *
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getUsersByNames(array $usernames, &$notFound = [], $with = [], $validOnly = false, $extraWhere = [])
    {
        $usernames = array_map('trim', $usernames);
        foreach ($usernames as $key => $username) {
            if ($username === '') {
                unset($usernames[$key]);
            }
        }

        $notFound = [];

        if (!$usernames) {
            return $this->em->getEmptyCollection();
        }

        $conditions = [
            ['username', $usernames],
            ['random_name', $usernames],
        ];

        $finder = $this->finder('XF:User')
            ->whereOr($conditions)
            // ->where('username', $usernames)
            ->with($with);
        if ($validOnly) {
            $finder->isValidUser();
        }
        if ($extraWhere) {
            $finder->where($extraWhere);
        }

        $users = $finder->fetch();
        if ($users->count() != count($usernames)) {
            $usernamesLower = array_map('strtolower', $usernames);
            $notFound = $usernames;

            foreach ($users as $user) {
                do {
                    $foundKey = array_search(strtolower($user['username']), $usernamesLower);
                    if ($foundKey !== false) {
                        unset($notFound[$foundKey]);
                        unset($usernamesLower[$foundKey]);
                    }
                } while ($foundKey !== false);
            }
        }

        //return $users;

        $orderedUsers = [];
        foreach ($usernames as $searchUsername) {
            $searchUsername = utf8_deaccent(utf8_strtolower($searchUsername));
            foreach ($users as $id => $user) {
                $testUsername = utf8_deaccent(utf8_strtolower($user->username));
                if ($searchUsername == $testUsername && !isset($orderedUsers[$id])) {
                    $orderedUsers[$id] = $user;
                }
            }
        }
        foreach ($users as $id => $user) {
            if (!isset($orderedUsers[$id])) {
                $orderedUsers[$id] = $user;
            }
        }

        return $this->em->getBasicCollection($orderedUsers);
    }
}
