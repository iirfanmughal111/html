<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Alert;

use XF;
use XF\Entity\UserAlert;
use XF\Mvc\Entity\Entity;
use XF\Alert\AbstractHandler;
use Truonglv\Groups\Service\Event\Notifier;

class Event extends AbstractHandler
{
    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['Group', 'Group.Category'];
    }

    /**
     * @param mixed $action
     * @return string
     */
    public function getTemplateName($action)
    {
        return 'public:tlg_alert_item_event_' . $action;
    }

    /**
     * @param mixed $action
     * @param UserAlert $alert
     * @param Entity|null $content
     * @return array
     */
    public function getTemplateData($action, UserAlert $alert, Entity $content = null)
    {
        $data = parent::getTemplateData($action, $alert, $content);

        if ($action === Notifier::ACTION_REMINDER
            && $content !== null
        ) {
            /** @var \Truonglv\Groups\Entity\Event $event */
            $event = $content;
            if ($event->begin_date > XF::$time) {
                $diff = $event->begin_date - XF::$time;
                $remaining = $diff > 3600
                    ? floor($diff / 3600) :
                     floor($diff / 60);
                $data['remainingUnit'] = $diff > 3600 ? 'hour' : 'minute';
            } else {
                $remaining = 0;
            }

            $data['remaining'] = $remaining;
        }

        return $data;
    }
}
