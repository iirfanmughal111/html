<?php

namespace Siropu\Chat\Option;

class WhosTyping extends \XF\Option\AbstractOption
{
     public static function verifyOption(&$value, \XF\Entity\Option $option)
	{
          $prevValue = json_decode($option->getPreviousValue('option_value'), true);

		if (!empty($value['room']) && empty($prevValue['room']))
		{
               foreach (\XF::finder('Siropu\Chat:Room')->fetch() as $room)
               {
                    $room->writeWhosTypingJsonFile();
               }
		}

          if (!empty($value['conv']) && empty($prevValue['conv']))
		{
               \XF::app()->jobManager()->enqueueUnique('siropuChat:CreateConvWhosTypingJson', 'Siropu\Chat:ConvWhosTyping', [], false);
		}

		return true;
	}
}
