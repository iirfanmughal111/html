<?php

namespace Siropu\Chat\Util;

class Cookie
{
     public static function setChannel($channel)
     {
          self::setCookie('siropu_chat_channel', $channel);
     }
     public static function setRoomId($roomId)
     {
          self::setCookie('siropu_chat_room_id', $roomId);
     }
     public static function setConvId($convId)
     {
          self::setCookie('siropu_chat_conv_id', $convId);
     }
     public static function setCookie($name, $value)
     {
          \XF::app()->response()->setCookie($name, $value, 0, null, false);
     }
}
