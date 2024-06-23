<?php

namespace FS\ScheduleBanUser\Cron;

class ScheduleBanUserCron
{

    public static function scheduleBanningUsers()
    {
        $finder = \XF::finder('FS\ScheduleBanUser:ScheduleBanUser')->where('ban_date', '<=', time())->fetch();
        foreach ($finder as $value) {

            $user = self::assertViewableUser($value->user_id, [], true);
            $banBy = self::assertViewableUser($value->user_banBy_id, [], true);

            /** @var \XF\Repository\Banning $banningRepo */
            $banningRepo = \XF::repository('XF:Banning');
            if (!$banningRepo->banUser($user, 0, $value['ban_reason'], $error, $banBy)) {
                throw new \XF\PrintableException($error);
            }
            $value->delete();
        }
    }

    /**
     * @param int $userId
     * @param array $extraWith
     * @param bool $basicProfileOnly
     *
     * @return \XF\Entity\User
     *
     * @throws \XF\Mvc\Reply\Exception
     */
    public static function assertViewableUser($userId, array $extraWith = [], $basicProfileOnly = false)
    {
        $extraWith[] = 'Option';
        $extraWith[] = 'Privacy';
        $extraWith[] = 'Profile';
        $extraWith = array_unique($extraWith);

        /** @var \XF\Entity\User $user */
        $user = \XF::em()->find('XF:User', $userId, $extraWith);
        if (!$user) {
            throw \XF::exception(\XF::notFound(\XF::phrase('requested_user_not_found')));
        }

        $canView = $basicProfileOnly ? $user->canViewBasicProfile($error) : $user->canViewFullProfile($error);
        if (!$canView) {
            throw \XF::exception(\XF::noPermission($error));
        }

        return $user;
    }
}
