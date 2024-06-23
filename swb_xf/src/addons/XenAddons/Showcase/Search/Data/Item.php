<?php

namespace XenAddons\Showcase\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;
use XF\Search\Query\MetadataConstraint;

class Item extends AbstractData
{
	public function getEntityWith($forView = false)
	{
		$get = ['Category'];
		if ($forView)
		{
			$get[] = 'CoverImage';
			$get[] = 'User';

			$visitor = \XF::visitor();
			$get[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		}

		return $get;
	}

	public function getIndexData(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */

		if (!$entity->Category)
		{
			return null;
		}

		$index = IndexRecord::create('sc_item', $entity->item_id, [
			'title' => $entity->title_,
			'message' => $entity->description_ . ' ' . $entity->message_ . ' ' . $entity->message_s2 . ' ' . $entity->message_s3 . ' ' . $entity->message_s4 . ' ' . $entity->message_s5 . ' ' . $entity->message_s6,
			'date' => $entity->create_date,
			'user_id' => $entity->user_id,
			'discussion_id' => $entity->item_id,
			'metadata' => $this->getMetaData($entity)
		]);

		if (!$entity->isVisible())
		{
			$index->setHidden();
		}

		if ($entity->tags)
		{
			$index->indexTags($entity->tags);
		}

		return $index;
	}

	protected function getMetaData(\XenAddons\Showcase\Entity\Item $entity)
	{
		$metadata = [
			'itemcat' => $entity->category_id,
			'item' => $entity->item_id
		];
		if ($entity->prefix_id)
		{
			$metadata['itemprefix'] = $entity->prefix_id;
		}
		if ($entity->series_part_id && $entity->SeriesPart)
		{
			$metadata['series'] = $entity->SeriesPart->series_id;
		}

		return $metadata;
	}

	public function setupMetadataStructure(MetadataStructure $structure)
	{
		$structure->addField('itemcat', MetadataStructure::INT);
		$structure->addField('item', MetadataStructure::INT);
		$structure->addField('itemprefix', MetadataStructure::INT);
		$structure->addField('series', MetadataStructure::INT);
	}

	public function getResultDate(Entity $entity)
	{
		return $entity->create_date;
	}

	public function getTemplateData(Entity $entity, array $options = [])
	{
		return [
			'item' => $entity,  
			'options' => $options
		];
	}

	public function canUseInlineModeration(Entity $entity, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */
		return $entity->canUseInlineModeration($error);
	}

	public function getSearchableContentTypes()
	{
		return [
			'sc_item', 
			'sc_comment',
			'sc_page',
			'sc_rating', 
			'sc_rating_reply',
			'sc_series',
			'sc_update', 
			'sc_update_reply'
		]; 
	}

	public function getSearchFormTab()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!method_exists($visitor, 'canViewShowcaseItems') || !$visitor->canViewShowcaseItems())
		{
			return null;
		}

		return [
			'title' => \XF::phrase('xa_sc_search_showcase'),
			'order' => \XF::options()->xaScShowcaseSearchFormTabOrder //134 
		];
	}

	public function getSectionContext()
	{
		return 'xa_showcase'; 
	}

	public function getSearchFormData()
	{
		$prefixListData = $this->getPrefixListData();

		return [
			'prefixGroups' => $prefixListData['prefixGroups'],
			'prefixesGrouped' => $prefixListData['prefixesGrouped'],

			'categoryTree' => $this->getSearchableCategoryTree()
		];
	}

	/**
	 * @return \XF\Tree
	 */
	protected function getSearchableCategoryTree()
	{
		/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
		$categoryRepo = \XF::repository('XenAddons\Showcase:Category');
		return $categoryRepo->createCategoryTree($categoryRepo->getViewableCategories());
	}

	protected function getPrefixListData()
	{
		/** @var \XenAddons\Showcase\Repository\ItemPrefix $prefixRepo */
		$prefixRepo = \XF::repository('XenAddons\Showcase:ItemPrefix');
		return $prefixRepo->getVisiblePrefixListData();
	}

	public function applyTypeConstraintsFromInput(\XF\Search\Query\Query $query, \XF\Http\Request $request, array &$urlConstraints)
	{
		$series = $request->filter('c.series', 'array-uint');
		$series = array_unique($series);
		if ($series && reset($series))
		{
			$query->withMetadata('series', $series);
		}
		else
		{
			unset($urlConstraints['series']);
		}
		
		$prefixes = $request->filter('c.prefixes', 'array-uint');
		$prefixes = array_unique($prefixes);
		if ($prefixes && reset($prefixes))
		{
			$query->withMetadata('itemprefix', $prefixes);
		}
		else
		{
			unset($urlConstraints['prefixes']);
		}

		$categoryIds = $request->filter('c.categories', 'array-uint');
		$categoryIds = array_unique($categoryIds);
		if ($categoryIds && reset($categoryIds))
		{
			if ($request->filter('c.child_categories', 'bool'))
			{
				$categoryTree = $this->getSearchableCategoryTree();

				$searchCategoryIds = array_fill_keys($categoryIds, true);
				$categoryTree->traverse(function($id, $category) use (&$searchCategoryIds)
				{
					if (isset($searchCategoryIds[$id]) || isset($searchCategoryIds[$category->parent_category_id]))
					{
						$searchCategoryIds[$id] = true;
					}
				});

				$categoryIds = array_unique(array_keys($searchCategoryIds));
			}
			else
			{
				unset($urlConstraints['child_categories']);
			}

			$query->withMetadata('itemcat', $categoryIds);
		}
		else
		{
			unset($urlConstraints['categories']);
			unset($urlConstraints['child_categories']);
		}

		$includeComments = $request->filter('c.include_comments', 'bool');
		$includeReviews = $request->filter('c.include_reviews', 'bool');
		$includeUpdates = $request->filter('c.include_updates', 'bool');
		
		$inTypes = ['sc_item', 'sc_series'];
		
		if (!$includeComments && !$includeReviews && !$includeUpdates)
		{
			unset($urlConstraints['include_comments']);
			unset($urlConstraints['include_reviews']);
			unset($urlConstraints['include_updates']);
		}
		else
		{
			if ($includeComments)
			{
				array_push($inTypes, 'sc_comment');
			}
			else
			{
				unset($urlConstraints['include_comments']);
			}
				
			if ($includeReviews)
			{
				array_push($inTypes, 'sc_rating', 'sc_rating_reply');
			}
			else
			{
				unset($urlConstraints['include_reviews']);
			}
				
			if ($includeUpdates)
			{
				array_push($inTypes, 'sc_update', 'sc_update_reply');
			}
			else
			{
				unset($urlConstraints['include_updates']);
			}
		}
		
		$query->inTypes($inTypes);
	}

	public function getTypePermissionConstraints(\XF\Search\Query\Query $query, $isOnlyType)
	{
		/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
		$categoryRepo = \XF::repository('XenAddons\Showcase:Category');

		$with = ['Permissions|' . \XF::visitor()->permission_combination_id];
		$categories = $categoryRepo->findCategoryList(null, $with)->fetch();

		$skip = [];
		foreach ($categories AS $category)
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			if (!$category->canView())
			{
				$skip[] = $category->category_id;
			}
		}

		if ($skip)
		{
			return [
				new MetadataConstraint('itemcat', $skip, MetadataConstraint::MATCH_NONE)
			];
		}
		else
		{
			return [];
		}
	}

	public function getGroupByType()
	{
		return 'sc_item';
	}
}