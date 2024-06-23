<?php

namespace Siropu\Chat\Criteria;

class Time
{
     public static function isTime(array $criteria)
     {
          $dateTime = new \DateTime('now', new \DateTimeZone(\XF::options()->guestTimeZone));
          $day      = $dateTime->format('w');
          $hour     = $dateTime->format('G');

          if (empty($criteria['days'][$day]))
          {
               return false;
          }

          if (!empty($criteria['hours']) && !in_array($hour, $criteria['hours']))
          {
               return false;
          }

          return true;
     }
}
