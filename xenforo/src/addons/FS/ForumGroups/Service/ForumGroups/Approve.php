<?php

namespace FS\ForumGroups\Service\ForumGroups;


use XF\Service\AbstractService;
use XF\Entity\Node;

class Approve extends AbstractService
{

    protected $request;

    protected $notifyRunTime = 3;

    public function __construct(\XF\App $app, Node $request)
    {
        parent::__construct($app);
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setNotifyRunTime($time)
    {
        $this->notifyRunTime = $time;
    }

    public function approve()
    {
        if ($this->request->node_state == 'moderated') {
            $this->request->node_state = 'visible';
            $this->request->save();
            return true;
        } else {
            return false;
        }
    }
}
