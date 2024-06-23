<?php

namespace Siropu\Chat\Util;

class Arr
{
     public static function filterByKey(array $arr, $key)
     {
          return array_filter($arr, function($k) use ($key) { return $k != $key; }, ARRAY_FILTER_USE_KEY);
     }
     public static function filterEmpty(array $arr)
     {
          return array_filter($arr, function($val) { return empty($val); });
     }
     public static function filterNotEmpty(array $arr)
     {
          return array_filter($arr, function($val) { return !empty($val); });
     }
     public static function mapPregQuote(array $arr, $delimiter = '/')
     {
          return array_map(function($val) use ($delimiter) { return preg_quote($val, $delimiter); }, $arr);
     }
     public static function getItemArray($items, $delimiter = ',')
     {
          if (empty($items))
          {
               return [];
          }

          return array_filter(array_map('trim', explode($delimiter, utf8_strtolower($items))));
     }
     public static function getItemsForRegex($items, $delimiter = "\n")
     {
          return implode('|', self::mapPregQuote(self::getItemArray($items, $delimiter)));
     }
}
