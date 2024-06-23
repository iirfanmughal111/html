<?php

namespace Siropu\Chat\Service;

use Siropu\Chat\Util\Action;

abstract class AbstractActionLogger extends \XF\Service\AbstractService
{
     protected $action;
     protected $actions;

	public function __construct(\XF\App $app, $action = '')
	{
		parent::__construct($app);

          $this->action  = $action;
          $this->actions = Action::getData();
	}
     public function writeData()
     {
          foreach ($this->actions as $type => $typeActions)
          {
               foreach ($typeActions as $itemId => $actions)
               {
                    foreach ($actions as $messageId => $action)
                    {
                         foreach ($action['action'] as $actionType => $date)
                         {
                              if ($date <= \XF::$time - 60)
                              {
                                   unset($this->actions[$type][$itemId][$messageId]['action'][$actionType]);

                                   if (empty($this->actions[$type][$itemId][$messageId]['action']))
                                   {
                                        unset($this->actions[$type][$itemId][$messageId]);

                                        if (empty($this->actions[$type][$itemId]))
                                        {
                                             unset($this->actions[$type][$itemId]);
                                        }
                                   }
                              }
                         }
                    }
               }
          }

          Action::writeData($this->actions);
     }
}
