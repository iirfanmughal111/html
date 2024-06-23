<?php

namespace Siropu\Chat\Command;

class Giphy
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          $apiKey = $command->getOption('giphy_api_key', \XF::options()->giphy['api_key']);

          $endpoint = $input ? 'search' : 'trending';

          $giphyUrl = "https://api.giphy.com/v1/gifs/{$endpoint}?api_key={$apiKey}&limit=50";

          if ($input)
          {
               $giphyUrl .= "&q=" . urlencode($input);
          }

          $rating = $command->getOption('rating');

          if ($rating)
          {
               $giphyUrl .= "&rating={$rating}";
          }

          $arrContextOptions = [
     		'ssl' => [
     			'verify_peer'      => FALSE,
     			'verify_peer_name' => TRUE,
     		]
     	];

          $gifs = @json_decode(@file_get_contents($giphyUrl, false, stream_context_create($arrContextOptions)), true);

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
}
