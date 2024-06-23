<?php

namespace XenAddons\Showcase\Pub\View\Category;

class Rss extends \XF\Mvc\View
{
	public function renderRss()
	{
		$app = \XF::app();
		$router = $app->router('public');
		$options = $app->options();
		$category = $this->params['category'];

		$indexUrl = $router->buildLink('canonical:index');
		if ($category)
		{
			$feedLink = $router->buildLink('canonical:showcase/categories/index.rss', $category);
		}
		else
		{
			$feedLink = $router->buildLink('canonical:showcase/index.rss', '-');
		}

		if ($category)
		{
			$title = $category->title;
			$description = $category->description;
		}
		else
		{
			$title = 'Showcase Items Feed';
			$description = 'Showcase Items feed from ' . $options->boardTitle . ' - ' . $options->boardDescription;
		}

		$title = $title ?: $indexUrl;
		$description = $description ?: $title; // required in RSS 2.0 spec

		if (\XF::$versionId >= 2020000) // XF 2.2.0 or greater
		{
			$feed = new \Laminas\Feed\Writer\Feed();
		}
		else 
		{
			$feed = new \Zend\Feed\Writer\Feed();
		}

		$feed->setEncoding('utf-8')
			->setTitle($title)
			->setDescription($description)
			->setLink($indexUrl)
			->setFeedLink($feedLink, 'rss')
			->setDateModified(\XF::$time)
			->setLastBuildDate(\XF::$time)
			->setGenerator($options->boardTitle);

		$parser = $app->bbCode()->parser();
		$rules = $app->bbCode()->rules('post:rss');

		$bbCodeCleaner = $app->bbCode()->renderer('bbCodeClean');
		$bbCodeRenderer = $app->bbCode()->renderer('html');

		$formatter = $app->stringFormatter();
		$maxLength = $options->discussionRssContentLength;

		/** @var \XenAddons\Showcase\Entity\Item $item */
		foreach ($this->params['items'] AS $item)
		{
			$itemCategory = $item->Category;
			$entry = $feed->createEntry();

			$title = (empty($item->title) ? \XF::phrase('title:') . ' ' . $item->title : $item->title);
			$entry->setTitle($title)
				->setLink($router->buildLink('canonical:showcase', $item))
				->setDateCreated($item->create_date)
				->setDateModified($item->last_update);

			if ($itemCategory && !$category)
			{
				$entry->addCategory([
					'term' => $itemCategory->title,
					'scheme' => $router->buildLink('canonical:category', $itemCategory)
				]);
			}

			if ($maxLength && $item && $item->message)
			{
				$snippet = $bbCodeCleaner->render($formatter->wholeWordTrim($item->message, $maxLength), $parser, $rules);

				if ($snippet != $item->message)
				{
					$snippet .= "\n\n[URL='" . $router->buildLink('canonical:showcase', $item) . "']$item->title[/URL]";
				}

				$renderOptions = $item->getBbCodeRenderOptions('post:rss', 'html');
				$renderOptions['noProxy'] = true;

				$content = trim($bbCodeRenderer->render($snippet, $parser, $rules, $renderOptions));
				if (strlen($content))
				{
					$entry->setContent($content);
				}
			}

			$entry->addAuthor([
				'name' => $item->username ?: strval(\XF::phrase('guest')),
				'email' => 'invalid@example.com',
				'uri' => $router->buildLink('canonical:members', $item)
			]);
			if ($item->comment_count)
			{
				$entry->setCommentCount($item->comment_count);
			}

			$feed->addEntry($entry);
		}

		return $feed->export('rss', true);
	}
}