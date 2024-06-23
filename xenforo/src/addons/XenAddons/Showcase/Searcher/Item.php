<?php

namespace XenAddons\Showcase\Searcher;

use XF\Mvc\Entity\Finder;
use XF\Searcher\AbstractSearcher;

use function is_array;

/**
 * @method \XenAddons\Showcase\Finder\Item getFinder()
 */
class Item extends AbstractSearcher
{
	protected $allowedRelations = ['Category'];

	protected $formats = [
		'title' => 'like',
		'description' => 'like',
		'username' => 'like',
		'create_date' => 'date',
	];

	protected $whitelistOrder = [
		'title' => true,
		'username' => true,
		'create_date' => true,
		'comment_count' => true,
		'rating_count' => true,
		'review_count' => true,
		'update_count' => true,
		'view_count' => true,
		'reaction_score' => true
	];

	protected $order = [['create_date', 'desc']];

	protected function getEntityType()
	{
		return 'XenAddons\Showcase:Item';
	}

	protected function getDefaultOrderOptions()
	{
		return [
			'create_date' => \XF::phrase('date'),
			'title' => \XF::phrase('title'),
			'comment_count' => \XF::phrase('comments'),
			'rating_count' => \XF::phrase('xa_sc_ratings'),
			'review_count' => \XF::phrase('xa_sc_reviews'),
			'update_count' => \XF::phrase('xa_sc_updates'),
			'view_count' => \XF::phrase('views'),
			'reaction_score' => \XF::phrase('reaction_score')
		];
	}

	protected function validateSpecialCriteriaValueAfter($key, &$value, $column, $format, $relation)
	{
		if ($key == 'created_in_last')
		{
			if (!is_array($value) || !isset($value['value']) || $value['value'] <= 0)
			{
				return false;
			}
		}
	
		if ($key == 'prefix_id' && $value == -1)
		{
			return false;
		}
	
		if ($key == 'category_id')
		{
			if (
				$value == 0
				|| (is_array($value) && isset($value[0]) && $value[0] == 0)
			)
			{
				return false;
			}
		}
	
		if (
			($key == 'author_user_group_id' || $key == 'author_not_user_group_id')
			&& !$value
		)
		{
			return false;
		}
	
		return null;
	}
	
	protected function applySpecialCriteriaValue(Finder $finder, $key, $value, $column, $format, $relation)
	{
		if ($key == 'prefix_id' && $value == -1)
		{
			// any prefix so skip condition
			return true;
		}

		if ($key == 'category_id')
		{
			if (!is_array($value))
			{
				$value = [$value];
			}
		
			if (isset($value['search_type']) && $value['search_type'] === 'exclude')
			{
				$matchInCategories = false;
			}
			else
			{
				$matchInCategories = true;
			}
			unset($value['search_type']);
		
			$finder->where('category_id', $matchInCategories ? '=' : '<>', $value);
		
			return true;
		}
		
		if ($key == 'created_in_last')
		{
			$cutOff = $this->convertRelativeTimeToCutoff(
				$value['value'],
				$value['unit']
			);
			if ($cutOff)
			{
				$column = 'create_date';
		
				$finder->where($column, '>=', $cutOff);
			}
			return true;
		}

		if ($key == 'item_field')
		{
			$exactMatchFields = !empty($value['exact']) ? $value['exact'] : [];
			$customFields = array_merge($value, $exactMatchFields);
			unset($customFields['exact']);

			$conditions = [];
			foreach ($customFields AS $fieldId => $value)
			{
				if ($value === '' || (is_array($value) && !$value))
				{
					continue;
				}

				$finder->with('CustomFields|' . $fieldId);
				$isExact = !empty($exactMatchFields[$fieldId]);

				foreach ((array)$value AS $possible)
				{
					$columnName = 'CustomFields|' . $fieldId . '.field_value';
					if ($isExact)
					{
						$conditions[] = [$columnName, '=', $possible];
					}
					else
					{
						$conditions[] = [$columnName, 'LIKE', $finder->escapeLike($possible, '%?%')];
					}
				}
			}
			if ($conditions)
			{
				$finder->whereOr($conditions);
			}
		}
		
		if ($key == 'tags')
		{
			/** @var \XF\Repository\Tag $tagRepo */
			$tagRepo = $this->em->getRepository('XF:Tag');
		
			$tags = $tagRepo->splitTagList($value);
			if ($tags)
			{
				$validTags = $tagRepo->getTags($tags, $notFound);
				if ($notFound)
				{
					// if they entered an unknown tag, we don't want to ignore it, so we need to force no results
					$finder->whereImpossible();
				}
				else
				{
					foreach (array_keys($validTags) AS $tagId)
					{
						$finder->with('Tags|' . $tagId, true);
					}
				}
		
				return true;
			}
		}		

		if ($key == 'author_user_group_id' || $key == 'author_not_user_group_id')
		{
			if (!is_array($value))
			{
				$value = [$value];
			}
		
			$finder->with('User');
		
			$userGroupIdColumn = $finder->columnSqlName('User.user_group_id');
			$secondaryGroupIdsColumn = $finder->columnSqlName('User.secondary_group_ids');
			$positiveMatch = ($key == 'author_user_group_id');
			$parts = [];
		
			// for negative matches, we default to allowing guests, but if they say "not the guest"
			// group, then we'll disable it
			$orIsGuest = $positiveMatch ? false : true;
		
			foreach ($value AS $userGroupId)
			{
				$quotedGroupId = $finder->quote($userGroupId);
				if ($positiveMatch)
				{
					$parts[] = "$userGroupIdColumn = $quotedGroupId "
						. "OR FIND_IN_SET($quotedGroupId, $secondaryGroupIdsColumn)";
				
					if ($userGroupId == \XF\Entity\User::GROUP_GUEST)
					{
						// if explicitly selecting the guest group, allow guest items
						// as they're hard to filter for otherwise
						$parts[] = $finder->columnSqlName('user_id') . ' = 0';
					}
				}
				else
				{
					$parts[] = "$userGroupIdColumn <> $quotedGroupId "
						. "AND FIND_IN_SET($quotedGroupId, $secondaryGroupIdsColumn) = 0";
			
					if ($userGroupId == \XF\Entity\User::GROUP_GUEST)
					{
						$orIsGuest = false;
					}
				}
			}
			if ($parts)
			{
				$joiner = $positiveMatch ? ' OR ' : ' AND ';
				$sql = implode($joiner, $parts);
				if ($orIsGuest)
					{
					$sql = "($sql) OR " . $finder->columnSqlName('user_id') . ' = 0';
				}
				$finder->whereSql($sql);
			}
			return true;
		}		
		
		return false;
	}

	public function getFormData()
	{
		/** @var \XenAddons\Showcase\Repository\ItemPrefix $prefixRepo */
		$prefixRepo = $this->em->getRepository('XenAddons\Showcase:ItemPrefix');
		$prefixes = $prefixRepo->getPrefixListData();

		/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
		$categoryRepo = $this->em->getRepository('XenAddons\Showcase:Category');
		$categories = $categoryRepo->getCategoryOptionsData(false);
		
		/** @var \XF\Repository\UserGroup $userGroupRepo */
		$userGroupRepo = $this->em->getRepository('XF:UserGroup');
		$userGroups = $userGroupRepo->findUserGroupsForList()->fetch();
		
		return [
			'prefixes' => $prefixes,
			'categories' => $categories,
			'userGroups' => $userGroups
		];
	}

	public function getFormDefaults()
	{
		return [
			'prefix_id' => -1,
			'category_id' => 0,

			'comment_count' => ['end' => -1],
			'rating_count' => ['end' => -1],
			'review_count' => ['end' => -1],
			'update_count' => ['end' => -1],
			'view_count' => ['end' => -1],

			'Discussion' => [
				'reply_count' => ['end' => -1],
			],
			
			'item_state' => ['visible', 'moderated', 'deleted'],  // TODO maybe add draft and awaiting
			'comments_open' => [0, 1],
			'ratings_open' => [0, 1],
		];
	}
}