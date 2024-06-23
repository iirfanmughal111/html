<?php

namespace Truonglv\Groups\Option;

use XF\Entity\Option;
use Truonglv\Groups\App;
use XF\Option\AbstractOption;

class DefaultTab extends AbstractOption
{
    public static function renderOption(Option $option, array $htmlParams): string
    {
        $navigationData = App::navigationData();

        return static::getRadioRow($option, $htmlParams, $navigationData->getNavigationTabOptions());
    }
}
