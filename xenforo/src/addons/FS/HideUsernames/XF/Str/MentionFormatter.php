<?php

namespace FS\HideUsernames\XF\Str;

class MentionFormatter extends XFCP_MentionFormatter
{

    protected function getMentionMatchUsers(array $matches)
    {
        
        
        $db = \XF::db();
        $matchKeys = array_keys($matches);
        $whereParts = [];
        $matchParts = [];
        $usersByMatch = [];

        foreach ($matches as $key => $match) {
            if (utf8_strlen($match[1][0]) > 50) {
                // longer than max username length
                continue;
            }

            $sql = 'user.username LIKE ' . $db->quote($db->escapeLike($match[1][0], '?%'));

            $whereParts[] = $sql;
            $matchParts[] = 'IF(' . $sql . ', 1, 0) AS match_' . $key;
        }

        if (!$whereParts) {
            return [];
        }

        $userResults = $db->query("
			SELECT user.user_id, user.username,
				" . implode(', ', $matchParts) . "
			FROM xf_user AS user
			WHERE (" . implode(' OR ', $whereParts) . ")
			ORDER BY LENGTH(user.username) DESC
		");
        while ($user = $userResults->fetch()) {
            $userInfo = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'lower' => utf8_strtolower($user['username'])
            ];

            foreach ($matchKeys as $key) {
                if (!empty($user["match_$key"])) {
                    $usersByMatch[$key][$user['user_id']] = $userInfo;
                }
            }
        }

        foreach ($matches as $key => $match) {
            if (utf8_strlen($match[1][0]) > 50) {
                // longer than max username length
                continue;
            }

            $sql = 'user.random_name LIKE ' . $db->quote($db->escapeLike($match[1][0], '?%'));

            $whereParts[] = $sql;
            $matchParts[] = 'IF(' . $sql . ', 1, 0) AS match_' . $key;
        }

        if (!$whereParts) {
            return [];
        }

        $userResults = $db->query("
			SELECT user.user_id, user.random_name,
				" . implode(', ', $matchParts) . "
			FROM xf_user AS user
			WHERE (" . implode(' OR ', $whereParts) . ")
			ORDER BY LENGTH(user.random_name) DESC
		");
        while ($user = $userResults->fetch()) {
            $userInfo = [
                'user_id' => $user['user_id'],
                'username' => $user['random_name'],
                'lower' => utf8_strtolower($user['random_name'])
            ];

            foreach ($matchKeys as $key) {
                if (!empty($user["match_$key"])) {
                    $usersByMatch[$key][$user['user_id']] = $userInfo;
                }
            }
        }

        return $usersByMatch;
    }
}
