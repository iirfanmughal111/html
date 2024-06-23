<?php

namespace Truonglv\Groups\Service\Group;

use XF;
use LogicException;
use XF\Entity\User;
use Truonglv\Groups\Entity\Log;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;

class Logger extends AbstractService
{
    /**
     * @var Group
     */
    protected $group;
    /**
     * @var User
     */
    protected $user;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        $this->group = $group;
        $this->setUser(XF::visitor());
    }

    /**
     * @param User $user
     * @return void
     */
    protected function setUser(User $user)
    {
        if ($user->user_id <= 0) {
            throw new LogicException('User must be exists!');
        }

        $this->user = $user;
    }

    /**
     * @param string $contentType
     * @param int $contentId
     * @param string $action
     * @param array $extraData
     * @return Log
     * @throws \XF\PrintableException
     */
    public function log(string $contentType, int $contentId, string $action, array $extraData = [])
    {
        /** @var Log $log */
        $log = $this->em()->create('Truonglv\Groups:Log');

        $log->group_id = $this->group->group_id;
        $log->hydrateRelation('Group', $this->group);

        $user = $this->user;
        $log->user_id = $user->user_id;
        $log->hydrateRelation('User', $user);

        $log->content_type = $contentType;
        $log->content_id = $contentId;
        $log->action = $action;
        $log->extra_data = $extraData;

        $log->save();

        return $log;
    }
}
