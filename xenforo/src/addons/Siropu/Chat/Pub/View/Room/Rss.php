<?php

namespace Siropu\Chat\Pub\View\Room;

class Rss extends \XF\Mvc\View
{
	public function renderRss()
	{
          $app     = \XF::app();
		$router  = $app->router('public');
		$options = $app->options();
          $room    = $this->params['room'];

          $feed = new \Zend\Feed\Writer\Feed();

		$feed->setEncoding('utf-8')
			->setTitle($room->room_name)
			->setDescription($room->room_description)
			->setLink($router->buildLink('canonical:chat/room', $room))
			->setFeedLink($router->buildLink('canonical:chat/room/rss', $room), 'rss')
			->setDateModified(\XF::$time)
			->setLastBuildDate(\XF::$time)
			->setGenerator(\XF::phrase('siropu_chat')->render());

          foreach ($this->params['messages'] as $message)
          {
               $entry = $feed->createEntry();

               $messageText = $app->stringFormatter()->stripBbCode($message->message_text);

               if ($messageText)
               {
                    $entry->setContent($messageText);

                    $tilte = "{$message->message_username}: {$messageText}";

                    $entry->setTitle($tilte)
     				->setLink($router->buildLink('canonical:chat/message/link', $message))
     				->setDateCreated($message->message_date);

                    $entry->addAuthor([
     				'name' => $message->message_username,
     				'uri'  => $router->buildLink('canonical:members', $message->User)
     			]);

                    $feed->addEntry($entry);
               }
          }

          return $feed->export('rss', true);
	}
}
