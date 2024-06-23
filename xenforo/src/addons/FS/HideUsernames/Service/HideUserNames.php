<?php

namespace FS\HideUsernames\Service;

use XF\Mvc\FormAction;

class HideUserNames extends \XF\Service\AbstractService
{
    public function genrateRandomNames()
    {
        $allUsers = \XF::finder('XF:User')->fetch();

        foreach ($allUsers as $user) {

            $length = rand(4, 6); // Generate a random length between 4 and 6

            $randomName = ucwords(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, $length));

            $user->fastUpdate('random_name', $randomName);
        }
    }

    public function replaceUserNames($message)
    {
        $pattern = '/\[USER=(\d+)\]@([^[]+)\[\/USER\]/';

        $matches = [];
        preg_match_all($pattern, $message, $matches, PREG_SET_ORDER);

        if (!$matches) {
            return $message;
        }

        $userData = [];
        $Ids = [];
        $Names = [];

        foreach ($matches as $match) {
            $Ids[] = $match[1];
            $Names[] = $match[2];
            $userData[$match[1]] = $match[2];
        }

        $users =  \XF::app()->em()->findByIds("XF:User", $Ids);

        $replacementNames = [];

        foreach ($matches as $match) {

            if ($users[$match[1]]['username'] != $match[2]) {
                $replacementNames[$match[1]] = $users[$match[1]]['username'];
            }
        }

        // Replace user IDs with new names
        $newMessage = preg_replace_callback($pattern, function ($match) use ($replacementNames) {
            $userId = $match[1];
            $userName = $replacementNames[$userId] ?? $match[2]; // Use the new name or keep the original if not found
            return "[USER=" . $userId . "]@" . $userName . "[/USER]";
        }, $message);

        return $newMessage;
    }
}
