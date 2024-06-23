<?php

namespace Truonglv\Groups\Pub\View\Event;

use XF\Mvc\View;

class Calendar extends View
{
    public function renderJson(): array
    {
        return $this->params['events'];
    }
}
