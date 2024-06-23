<?php

namespace Siropu\Chat\Command;

class Gif
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          $giphyApiKey = $command->getOption('giphy_api_key', \XF::options()->giphy['api_key']);
          $tenorApiKey = $command->getOption('tenor_api_key');

          $endpoint = $input ? 'search' : 'trending';

          if ($giphyApiKey)
          {
               $giphyUrl = "https://api.giphy.com/v1/gifs/{$endpoint}?api_key={$giphyApiKey}&limit=50";

               if ($input)
               {
                    $giphyUrl .= "&q=" . urlencode($input);
               }

               $rating = $command->getOption('rating');

               if ($rating)
               {
                    $giphyUrl .= "&rating={$rating}";
               }

               $gifs = self::getGifs($giphyUrl);

               if (empty($gifs['data']))
               {
                    return $controller->message(\XF::phrase('siropu_chat_no_data_retuned_from_source'));
               }
               else
               {
                    shuffle($gifs['data']);
                    $messageEntity->message_text = '[IMG]' . $gifs['data'][0]['images']['original']['url'] . '[/IMG]';
               }
          }
          else if ($tenorApiKey)
          {
               $language = \XF::em()->find('XF:Language', \XF::options()->defaultLanguageId);

               $locale = str_replace('-', '_', $language->language_code);

               $tenorUrl = "https://g.tenor.com/v1/{$endpoint}?key={$tenorApiKey}&locale={$locale}&ar_range=standard&media_filter=minimal";

               if ($input)
               {
                    $tenorUrl .= "&q=" . urlencode($input) . "&contentfilter=off";
               }

               $gifs = self::getGifs($tenorUrl);

               if (empty($gifs['results']))
               {
                    return $controller->message(\XF::phrase('siropu_chat_no_data_retuned_from_source'));
               }
               else
               {
                    shuffle($gifs['results']);
                    $messageEntity->message_text = '[IMG]' . $gifs['results'][0]['media'][0]['gif']['url'] . '[/IMG]';
               }
          }
          else
          {
               return $controller->message(\XF::phrase('siropu_chat_command_not_set_up'));
          }
     }
     public static function getGifs($url)
     {
          $arrContextOptions = [
               'ssl' => [
                    'verify_peer'      => FALSE,
                    'verify_peer_name' => TRUE,
               ]
          ];

          return @json_decode(@file_get_contents($url, false, stream_context_create($arrContextOptions)), true);
     }
}
