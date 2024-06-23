<?php

namespace XenAddons\Showcase;

use function count, in_array, is_array;

class Listener
{
	public static function appSetup(\XF\App $app)
	{
		$container = $app->container();

		$container['prefixes.sc_item'] = $app->fromRegistry('xa_scPrefixes',
			function(\XF\Container $c) { return $c['em']->getRepository('XenAddons\Showcase:ItemPrefix')->rebuildPrefixCache(); }
		);

		$container['customFields.sc_items'] = $app->fromRegistry('xa_scItemFields',
			function(\XF\Container $c) { return $c['em']->getRepository('XenAddons\Showcase:ItemField')->rebuildFieldCache(); },
			function(array $scItemFieldsInfo)
			{
				$definitionSet = new \XF\CustomField\DefinitionSet($scItemFieldsInfo);
				$definitionSet->addFilter('display_on_list', function(array $field)
				{
					return (bool)$field['display_on_list'];
				});
				return $definitionSet;
			}
		);
		
		$container['customFields.sc_reviews'] = $app->fromRegistry('xa_scReviewFields',
			function(\XF\Container $c) { return $c['em']->getRepository('XenAddons\Showcase:ReviewField')->rebuildFieldCache(); },
			function(array $fields)
			{
				return new \XF\CustomField\DefinitionSet($fields);
			}
		);
		
		$container['customFields.sc_updates'] = $app->fromRegistry('xa_scUpdateFields',
			function(\XF\Container $c) { return $c['em']->getRepository('XenAddons\Showcase:UpdateField')->rebuildFieldCache(); },
			function(array $fields)
			{
				return new \XF\CustomField\DefinitionSet($fields);
			}
		);
	}
	
	public static function navigationSetup(\XF\Pub\App $app, array &$navigationFlat, array &$navigationTree)
	{
		$visitor = self::visitor();
		
		if (isset($navigationFlat['xa_showcase']) 
			&& $visitor->canViewShowcaseItems() 
			&& \XF::options()->xaScUnreadCounter)
		{
			$session = $app->session();
	
			$itemsUnread = $session->get('scUnreadItems');
			
			if ($itemsUnread)
			{
				$navigationFlat['xa_showcase']['counter'] = count($itemsUnread['unread']);
			}
		}
	}	

	public static function appPubStartEnd(\XF\Pub\App $app)
	{
		$visitor = self::visitor();
	
		if ($visitor->user_id && $visitor->canViewShowcaseItems() && \XF::options()->xaScUnreadCounter)
		{
			$session = $app->session();
	
			$itemsUnread = array_replace([
				'unread' => [],
				'lastUpdateDate' => 0
			], $session->get('scUnreadItems') ?: []);
	
			if ($itemsUnread['lastUpdateDate']  < (\XF::$time - 5 * 60)) // 5 minutes
			{
				$categoryRepo = \XF::repository('XenAddons\Showcase:Category');
				$categoryList = $categoryRepo->getViewableCategories();
				$categoryIds = $categoryList->keys();
	
				$itemRepo = \XF::repository('XenAddons\Showcase:Item');
				$showcaseItems = $itemRepo->findItemsForItemList($categoryIds)
					->unreadOnly($visitor->user_id)
					->orderByDate()
					->fetch();
	
				if ($showcaseItems->count())
				{
					$itemsUnread['unread'] = array_fill_keys($showcaseItems->keys(), true);
				}
			}
	
			$itemsUnread['lastUpdateDate'] = \XF::$time;
			$session->set('scUnreadItems', $itemsUnread);
		}
	}
	
	public static function criteriaUser($rule, array $data, \XF\Entity\User $user, &$returnValue)
	{
		switch ($rule)
		{
			case 'xa_sc_item_count':
				if (isset($user->xa_sc_item_count) && $user->xa_sc_item_count >= $data['items'])
				{
					$returnValue = true;
				}
				break;
				
			case 'xa_sc_item_count_nmt': 
				if (isset($user->xa_sc_item_count) && $user->xa_sc_item_count <= $data['items'])
				{
					$returnValue = true;
				}
				break;
				
			case 'xa_sc_item_prefix':
				if (isset($user->xa_sc_item_count) && $user->xa_sc_item_count > 0 && $data['prefix_id'] > 0)
				{
					$itemRepo = \XF::repository('XenAddons\Showcase:Item');
					$itemFinder = $itemRepo->findItemsForUserByPrefix($user, $data['prefix_id']);
					$itemCount = $itemFinder->fetch()->count();
					if ($itemCount)
					{
						$returnValue = true;
					}					
				}
				break;				
				
			case 'xa_sc_featured_item_count':
				$itemRepo = \XF::repository('XenAddons\Showcase:Item');
				$itemFinder = $itemRepo->findFeaturedItemsForUser($user);
				$itemCount = $itemFinder->fetch()->count();
				if ($itemCount >= $data['items'])
				{
					$returnValue = true;
				}
				break;
					
			case 'xa_sc_featured_item_count_nmt':
				$itemRepo = \XF::repository('XenAddons\Showcase:Item');
				$itemFinder = $itemRepo->findFeaturedItemsForUser($user);
				$itemCount = $itemFinder->fetch()->count();
				if ($itemCount <= $data['items'])
				{
					$returnValue = true;
				}
				break;	
				
			case 'xa_sc_comment_count':
				if (isset($user->xa_sc_comment_count) && $user->xa_sc_comment_count >= $data['comments'])
				{
					$returnValue = true;
				}
				break;
			
			case 'xa_sc_comment_count_nmt':
				if (isset($user->xa_sc_comment_count) && $user->xa_sc_comment_count <= $data['comments'])
				{
					$returnValue = true;
				}
				break;
				
			case 'xa_sc_review_count':
				if (isset($user->xa_sc_review_count) && $user->xa_sc_review_count >= $data['reviews'])
				{
					$returnValue = true;
				}
				break;
			
			case 'xa_sc_review_count_nmt':
				if (isset($user->xa_sc_review_count) && $user->xa_sc_review_count <= $data['reviews'])
				{
					$returnValue = true;
				}
				break;				

			case 'xa_sc_series_count':
				if (isset($user->xa_sc_series_count) && $user->xa_sc_series_count >= $data['series'])
				{
					$returnValue = true;
				}
				break;

			case 'xa_sc_series_count_nmt':
				if (isset($user->xa_sc_series_count) && $user->xa_sc_series_count <= $data['series'])
				{
					$returnValue = true;
				}
				break;
		}
	}
	
	public static function criteriaPage($rule, array $data, \XF\Entity\User $user, array $params, &$returnValue)
	{
		if ($rule === 'sc_categories')
		{
			$returnValue = false;
			
			if (!empty($data['sc_category_ids']))
			{
				$selectedCategoryIds = $data['sc_category_ids'];
				
				if (isset($params['breadcrumbs']) && is_array($params['breadcrumbs']) && empty($data['sc_category_only']) && isset($params['scCategory']))
				{
					foreach ($params['breadcrumbs'] AS $i => $navItem)
					{
						if (
							isset($navItem['attributes']['category_id']) 
							&& in_array($navItem['attributes']['category_id'], $selectedCategoryIds)
						)
						{
							$returnValue = true;
						}
					}
				}
				
				if (!empty($params['containerKey']))
				{
					list ($type, $id) = explode('-', $params['containerKey'], 2);
		
					if ($type == 'scCategory' && $id && in_array($id, $selectedCategoryIds))
					{
						$returnValue = true;
					}
				}
			}	
		}	
	}
	
	public static function criteriaTemplateData(array &$templateData)
	{
		$categoryRepo = \XF::repository('XenAddons\Showcase:Category');
		$templateData['scCategories'] = $categoryRepo->getCategoryOptionsData(false);
	}

	public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
	{
		/** @var \XenAddons\Showcase\Template\TemplaterSetup $templaterSetup */
		$class = \XF::extendClass('XenAddons\Showcase\Template\TemplaterSetup');
		$templaterSetup = new $class();
		
		$templater->addFunction('sc_item_thumbnail', [$templaterSetup, 'fnScItemThumbnail']);
		$templater->addFunction('sc_item_page_thumbnail', [$templaterSetup, 'fnScItemPageThumbnail']);
		$templater->addFunction('sc_category_icon', [$templaterSetup, 'fnScCategoryIcon']);
		$templater->addFunction('sc_series_icon', [$templaterSetup, 'fnScSeriesIcon']);
	}

	public static function templaterTemplatePreRenderPublicEditor(\XF\Template\Templater $templater, &$type, &$template, array &$params)
	{
		if (!self::visitor()->canViewShowcaseItems())
		{
			$params['removeButtons'][] = 'xfCustom_showcase';
		}
	}
	
	public static function editorDialog(array &$data, \XF\Pub\Controller\AbstractController $controller)
	{
		$controller->assertRegistrationRequired();
	
		$data['template'] = 'xa_sc_editor_dialog_showcase';
	}
	
	public static function userContentChangeInit(\XF\Service\User\ContentChange $changeService, array &$updates)
	{
		$updates['xf_xa_sc_category_watch'] = ['user_id', 'emptyable' => false];
		
		$updates['xf_xa_sc_comment'] = [
			['user_id', 'username'],
			['last_edit_user_id', 'emptyable' => false]
		];
		$updates['xf_xa_sc_comment_read'] = ['user_id', 'emptyable' => false];
		
		$updates['xf_xa_sc_feed'] = ['user_id', 'emptyable' => false];
		
		$updates['xf_xa_sc_item'] = [
			['user_id', 'username'],
			['last_comment_user_id', 'last_comment_username'],
			['last_edit_user_id', 'emptyable' => false]
		];
		$updates['xf_xa_sc_item_contributor'] = ['user_id', 'emptyable' => false];
		$updates['xf_xa_sc_item_page'] = [
			['user_id', 'username'],
			['last_edit_user_id', 'emptyable' => false]
		];
		$updates['xf_xa_sc_item_rating'] = [
			['user_id', 'username'],
			['last_edit_user_id', 'emptyable' => false]
		];
		$updates['xf_xa_sc_item_rating_reply'] = ['user_id', 'username'];
		$updates['xf_xa_sc_item_read'] = ['user_id', 'emptyable' => false];
		$updates['xf_xa_sc_item_reply_ban'] = ['user_id', 'emptyable' => false];
		$updates['xf_xa_sc_item_update'] = [
			['user_id', 'username'],
			['last_edit_user_id', 'emptyable' => false]
		];
		$updates['xf_xa_sc_item_update_reply'] = ['user_id', 'username'];
		$updates['xf_xa_sc_item_watch'] = ['user_id', 'emptyable' => false];
		
		$updates['xf_xa_sc_series'] = [
			['user_id', 'username'],
			['last_edit_user_id', 'emptyable' => false]
		];
		$updates['xf_xa_sc_series_part'] = ['user_id', 'emptyable' => false];
		$updates['xf_xa_sc_series_watch'] = ['user_id', 'emptyable' => false];
	}

	public static function userDeleteCleanInit(\XF\Service\User\DeleteCleanUp $deleteService, array &$deletes)
	{
		$deletes['xf_xa_sc_item_contributor'] = 'user_id = ?';
		$deletes['xf_xa_sc_category_watch'] = 'user_id = ?';
		$deletes['xf_xa_sc_comment_read'] = 'user_id = ?';
		$deletes['xf_xa_sc_item_watch'] = 'user_id = ?';
		$deletes['xf_xa_sc_item_read'] = 'user_id = ?';
		$deletes['xf_xa_sc_series_watch'] = 'user_id = ?';
	}

	public static function userMergeCombine(
		\XF\Entity\User $target, \XF\Entity\User $source, \XF\Service\User\Merge $mergeService
	)
	{
		$target->xa_sc_item_count += $source->xa_sc_item_count;
		$target->xa_sc_comment_count += $source->xa_sc_comment_count;
		$target->xa_sc_review_count += $source->xa_sc_review_count;
		$target->xa_sc_series_count += $source->xa_sc_series_count;
	}

	public static function userSearcherOrders(\XF\Searcher\User $userSearcher, array &$sortOrders)
	{
		$sortOrders['xa_sc_item_count'] = \XF::phrase('xa_sc_showcase_item_count');
		$sortOrders['xa_sc_comment_count'] = \XF::phrase('xa_sc_showcase_comment_count');
		$sortOrders['xa_sc_review_count'] = \XF::phrase('xa_sc_showcase_review_count');
		$sortOrders['xa_sc_series_count'] = \XF::phrase('xa_sc_showcase_series_count');
	}
	
	public static function memberStatResultPrepare($order, array &$cacheResults)
	{
		switch ($order)
		{
			case 'xa_sc_item_count':
			case 'xa_sc_comment_count':
			case 'xa_sc_review_count':
			case 'xa_sc_series_count':
				$cacheResults = array_map(function($value)
				{
					return \XF::language()->numberFormat($value);
				}, $cacheResults);
				break;
		}
	}
	
	/**
	 * @return \XenAddons\Showcase\XF\Entity\User
	 */
	public static function visitor()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor;
	}	
}