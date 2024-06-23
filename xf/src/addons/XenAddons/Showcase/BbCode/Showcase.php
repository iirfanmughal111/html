<?php

namespace XenAddons\Showcase\BbCode;

class Showcase
{
	public static function renderTagShowcase($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
	{
		if (!$tag['option'])
		{
			return $renderer->renderUnparsedTag($tag, $options);
		}

		$parts = explode(',', $tag['option']);
		foreach ($parts AS &$part)
		{
			$part = trim($part);
			$part = str_replace(' ', '', $part);
		}

		$type = $renderer->filterString(array_shift($parts),
			array_merge($options, [
				'stopSmilies' => true,
				'stopLineBreakConversion' => true
			])
		);
		$type = strtolower($type);
		$id = array_shift($parts);

		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();

		if (!$visitor->canViewShowcaseItems()
			|| $renderer instanceof \XF\BbCode\Renderer\SimpleHtml
			|| $renderer instanceof \XF\BbCode\Renderer\EmailHtml
		)
		{
			return self::renderTagSimple($type, $id);
		}

		$viewParams = [
			'type' => $type,
			'id' => intval($id),
			'text' => isset($tag['children']) ? $tag['children'] : ''
		];

		if ($type == 'item')
		{
			if (isset($options['showcaseItems'][$id]))
			{
				$item = $options['showcaseItems'][$id];
			}
			else
			{
				$item = \XF::em()->find('XenAddons\Showcase:Item', $id, [
					'Category.Permissions|' . $visitor->permission_combination_id
				]);
			}
			if (!$item || !$item->canView())
			{
				return self::renderTagSimple($type, $id);
			}
			else if ($visitor->isIgnoring($item->user_id))
			{
				return '';
			}
			$viewParams['item'] = $item;

			return $renderer->getTemplater()->renderTemplate('public:xa_sc_showcase_bb_code_item', $viewParams);
		}
		
		if ($type == 'page')
		{
			if (isset($options['showcasePages'][$id]))
			{
				$itemPage = $options['showcasePages'][$id];
			}
			else
			{
				$itemPage = \XF::em()->find('XenAddons\Showcase:ItemPage', $id, [
					'Item',
					'Item.Category.Permissions|' . $visitor->permission_combination_id
				]);
			}
			if (!$itemPage || !$itemPage->canView())
			{
				return self::renderTagSimple($type, $id);
			}
			else if ($visitor->isIgnoring($itemPage->user_id))
			{
				return '';
			}
			$viewParams['itemPage'] = $itemPage;
		
			return $renderer->getTemplater()->renderTemplate('public:xa_sc_showcase_bb_code_item_page', $viewParams);
		}
		
		if ($type == 'series')
		{
			if (isset($options['showcaseSeries'][$id]))
			{
				$series = $options['showcaseSeries'][$id];
			}
			else
			{
				$series = \XF::em()->find('XenAddons\Showcase:SeriesItem', $id, ['LastItem']);
			}
			if (!$series || !$series->canView())
			{
				return self::renderTagSimple($type, $id);
			}
			else if ($visitor->isIgnoring($series->user_id))
			{
				return '';
			}
			$viewParams['series'] = $series;
		
			return $renderer->getTemplater()->renderTemplate('public:xa_sc_showcase_bb_code_series', $viewParams);
		}

		return self::renderTagSimple($type, $id);
	}

	protected static function renderTagSimple($type, $id)
	{
		$router = \XF::app()->router('public');

		switch ($type)
		{
			case 'item':

				$link = $router->buildLink('full:showcase', ['item_id' => $id]);
				$phrase = \XF::phrase('xa_sc_view_item_x', ['id' => $id]);

				return '<a href="' . htmlspecialchars($link) .'">' . $phrase .'</a>';
			
			case 'page':
			
				$link = $router->buildLink('full:showcase/page', ['page_id' => $id]);
				$phrase = \XF::phrase('xa_sc_view_item_page_x', ['id' => $id]);
			
				return '<a href="' . htmlspecialchars($link) .'">' . $phrase .'</a>';
					
			case 'series':
					
				$link = $router->buildLink('full:showcase/series', ['series_id' => $id]);
				$phrase = \XF::phrase('xa_sc_view_series_x', ['id' => $id]);
					
				return '<a href="' . htmlspecialchars($link) .'">' . $phrase .'</a>';

			default:

				return '[SHOWCASE]';
		}
	}
}