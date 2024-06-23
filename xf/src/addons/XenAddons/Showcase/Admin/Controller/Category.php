<?php

namespace XenAddons\Showcase\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

use function in_array;

class Category extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('showcase');
	}

	/**
	 * @return \XenAddons\Showcase\ControllerPlugin\CategoryTree
	 */
	protected function getCategoryTreePlugin()
	{
		return $this->plugin('XenAddons\Showcase:CategoryTree');
	}

	public function actionIndex()
	{
		return $this->getCategoryTreePlugin()->actionList([
			'permissionContentType' => 'sc_category'
		]);
	}

	public function categoryAddEdit(\XenAddons\Showcase\Entity\Category $category)
	{
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->findCategoryList()->fetch();
		$categoryTree = $categoryRepo->createCategoryTree($categories);

		if ($category->thread_prefix_id && $category->ThreadForum)
		{
			$threadPrefixes = $category->ThreadForum->getPrefixesGrouped();
		}
		else
		{
			$threadPrefixes = [];
		}

		/** @var \XenAddons\Showcase\Repository\ItemPrefix $prefixRepo */
		$prefixRepo = $this->repository('XenAddons\Showcase:ItemPrefix');
		$availablePrefixes = $prefixRepo->findPrefixesForList()->fetch();
		$availablePrefixes = $availablePrefixes->pluckNamed('title', 'prefix_id');
		$prefixListData = $prefixRepo->getPrefixListData();
		
		/** @var \XenAddons\Showcase\Repository\ItemField $fieldRepo */
		$fieldRepo = $this->repository('XenAddons\Showcase:ItemField');
		$availableFields = $fieldRepo->findFieldsForList()->fetch();
		$availableFields = $availableFields->pluckNamed('title', 'field_id');
		
		/** @var \XenAddons\Showcase\Repository\ReviewField $reviewFieldRepo */
		$reviewFieldRepo = $this->repository('XenAddons\Showcase:ReviewField');
		$availableReviewFields = $reviewFieldRepo->findFieldsForList()->fetch();
		$availableReviewFields = $availableReviewFields->pluckNamed('title', 'field_id');
		
		/** @var \XenAddons\Showcase\Repository\UpdateField $updateFieldRepo */
		$updateFieldRepo = $this->repository('XenAddons\Showcase:UpdateField');
		$availableUpdateFields = $updateFieldRepo->findFieldsForList()->fetch();
		$availableUpdateFields = $availableUpdateFields->pluckNamed('title', 'field_id');
		
		/** @var \XF\Repository\Style $styleRepo */
		$styleRepo = $this->repository('XF:Style');
		$styleTree = $styleRepo->getStyleTree(false);
		
		$viewParams = [
			'forumOptions' => $this->repository('XF:Forum')->getForumOptionsData(false, 'discussion'),
			'threadPrefixes' => $threadPrefixes,
			'category' => $category,
			'categoryTree' => $categoryTree,

			'availableFields' => $availableFields,
			'availableReviewFields' => $availableReviewFields,
			'availableUpdateFields' => $availableUpdateFields,
			'availablePrefixes' => $availablePrefixes,
			
			'prefixGroups' => $prefixListData['prefixGroups'],
			'prefixesGrouped' => $prefixListData['prefixesGrouped'],
			
			'styleTree' => $styleTree,
		];
		return $this->view('XenAddons\Showcase:Category\Edit', 'xa_sc_category_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$category = $this->assertCategoryExists($params->category_id);
		
		return $this->categoryAddEdit($category);
	}

	public function actionAdd()
	{
		$category = $this->em()->create('XenAddons\Showcase:Category');
		$category->parent_category_id = $this->filter('parent_category_id', 'uint');

		$category->title_s1 =  \XF::phrase('xa_sc_default_section_title');
		
		if ($this->filter('clone_category_id', 'uint'))
		{
			if ($clonedCategory = $this->assertCategoryExists($this->filter('clone_category_id', 'uint')))
			{
				$category->display_order = $clonedCategory->display_order;
				$category->min_tags = $clonedCategory->min_tags;
				$category->default_tags = $clonedCategory->default_tags;
				$category->allow_items = $clonedCategory->allow_items;
				$category->require_item_image = $clonedCategory->require_item_image;
				$category->allow_contributors = $clonedCategory->allow_contributors;
				$category->allow_self_join_contributors = $clonedCategory->allow_self_join_contributors;
				$category->max_allowed_contributors = $clonedCategory->max_allowed_contributors;
				$category->allow_poll = $clonedCategory->allow_poll;
				$category->allow_location = $clonedCategory->allow_location;
				$category->require_location = $clonedCategory->require_location;
				$category->allow_business_hours = $clonedCategory->allow_business_hours;
				$category->allow_comments = $clonedCategory->allow_comments;
				$category->allow_ratings = $clonedCategory->allow_ratings;
				$category->require_review = $clonedCategory->require_review;
				$category->allow_pros_cons = $clonedCategory->allow_pros_cons;
				$category->allow_anon_reviews = $clonedCategory->allow_anon_reviews;
				$category->review_voting = $clonedCategory->review_voting;
				$category->allow_author_rating = $clonedCategory->allow_author_rating;
				$category->thread_node_id = $clonedCategory->thread_node_id;
				$category->thread_prefix_id = $clonedCategory->thread_prefix_id;
				$category->thread_set_item_tags = $clonedCategory->thread_set_item_tags;
				$category->autopost_review = $clonedCategory->autopost_review;
				$category->autopost_update = $clonedCategory->autopost_update;
				$category->title_s1 = $clonedCategory->title_s1;
				$category->description_s1 = $clonedCategory->description_s1;
				$category->editor_s1 = $clonedCategory->editor_s1;
				$category->min_message_length_s1 = $clonedCategory->min_message_length_s1;
				$category->title_s2 = $clonedCategory->title_s2;
				$category->description_s2 = $clonedCategory->description_s2;
				$category->editor_s2 = $clonedCategory->editor_s2;
				$category->min_message_length_s2 = $clonedCategory->min_message_length_s2;
				$category->title_s3 = $clonedCategory->title_s3;
				$category->description_s3 = $clonedCategory->description_s3;
				$category->editor_s3 = $clonedCategory->editor_s3;
				$category->min_message_length_s3 = $clonedCategory->min_message_length_s3;
				$category->title_s4 = $clonedCategory->title_s4;
				$category->description_s4 = $clonedCategory->description_s4;
				$category->editor_s4 = $clonedCategory->editor_s4;
				$category->min_message_length_s4 = $clonedCategory->min_message_length_s4;
				$category->title_s5 = $clonedCategory->title_s5;
				$category->description_s5 = $clonedCategory->description_s5;
				$category->editor_s5 = $clonedCategory->editor_s5;
				$category->min_message_length_s5 = $clonedCategory->min_message_length_s5;
				$category->title_s6 = $clonedCategory->title_s6;
				$category->description_s6 = $clonedCategory->description_s6;
				$category->editor_s6 = $clonedCategory->editor_s6;
				$category->min_message_length_s6 = $clonedCategory->min_message_length_s6;
				$category->item_list_order = $clonedCategory->item_list_order;
				$category->layout_type = $clonedCategory->layout_type;
				$category->map_options = $clonedCategory->map_options;
				$category->style_id = $clonedCategory->style_id;
				$category->field_cache = $clonedCategory->field_cache;
				$category->review_field_cache = $clonedCategory->review_field_cache;
				$category->update_field_cache = $clonedCategory->update_field_cache;
				$category->prefix_cache = $clonedCategory->prefix_cache;
				$category->default_prefix_id = $clonedCategory->default_prefix_id;
				$category->require_prefix = $clonedCategory->require_prefix;
				$category->display_items_on_index = $clonedCategory->display_items_on_index;
				$category->expand_category_nav = $clonedCategory->expand_category_nav;
				$category->display_location_on_list = $clonedCategory->display_location_on_list;
				$category->location_on_list_display_type = $clonedCategory->location_on_list_display_type;
				$category->allow_index = $clonedCategory->allow_index;
			}
		}
		
		return $this->categoryAddEdit($category);
	}

	protected function categorySaveProcess(\XenAddons\Showcase\Entity\Category $category)
	{
		$categoryInput = $this->filter([
			'title' => 'str',
			'og_title' => 'str',
			'meta_title' => 'str',
			'description' => 'str',
			'meta_description' => 'str',
			'parent_category_id' => 'uint',
			'display_order' => 'uint',
			'min_tags' => 'uint',
			'default_tags' => 'str',
			'thread_node_id' => 'uint',
			'thread_prefix_id' => 'uint',
			'thread_set_item_tags' => 'bool',
			'autopost_review' => 'bool',
			'autopost_update' => 'bool',
			'require_prefix' => 'bool',
			'default_prefix_id' => 'uint',
			'style_id' => 'uint',
			'content_image_url' => 'str',
			'content_title' => 'str',
			'content_message' => 'str',
			'content_term' => 'str',
			'title_s1' => 'str',
			'title_s2' => 'str',
			'title_s3' => 'str',
			'title_s4' => 'str',
			'title_s5' => 'str',
			'title_s6' => 'str',
			'description_s1' => 'str',
			'description_s2' => 'str',
			'description_s3' => 'str',
			'description_s4' => 'str',
			'description_s5' => 'str',
			'description_s6' => 'str',
			'editor_s1' => 'bool',
			'editor_s2' => 'bool',
			'editor_s3' => 'bool',
			'editor_s4' => 'bool',
			'editor_s5' => 'bool',
			'editor_s6' => 'bool',
			'min_message_length_s1' => 'int',
			'min_message_length_s2' => 'int',
			'min_message_length_s3' => 'int',
			'min_message_length_s4' => 'int',
			'min_message_length_s5' => 'int',
			'min_message_length_s6' => 'int',
			'allow_items' => 'bool',
			'allow_contributors' => 'bool',
			'allow_self_join_contributors' => 'bool',
			'max_allowed_contributors' => 'uint',
			'require_item_image' => 'bool',
			'allow_location' => 'bool',
			'require_location' => 'bool',
			'allow_business_hours' => 'bool',
			'allow_comments' => 'bool',
			'allow_poll' => 'bool',
			'allow_ratings' => 'bool',
			'review_voting' => 'str',
			'require_review' => 'bool',
			'allow_pros_cons' => 'bool',
			'allow_anon_reviews' => 'bool',
			'allow_author_rating' => 'bool',
			'layout_type' => 'str',
			'item_list_order' => 'str',
			'map_options' => 'array',
			'display_items_on_index' => 'bool',
			'expand_category_nav' => 'bool',
			'display_location_on_list' => 'bool',
			'location_on_list_display_type' => 'str',
			'allow_index' => 'str'
		]);
		
		$categoryInput['index_criteria'] = $this->filterIndexCriteria();

		$form = $this->formAction();
		$form->basicEntitySave($category, $categoryInput);

		$prefixIds = $this->filter('available_prefixes', 'array-uint');
		$form->complete(function() use ($category, $prefixIds)
		{
			/** @var \XenAddons\Showcase\Repository\CategoryPrefix $repo */
			$repo = $this->repository('XenAddons\Showcase:CategoryPrefix');
			$repo->updateContentAssociations($category->category_id, $prefixIds);
		});
		
		if (!in_array($category->default_prefix_id, $prefixIds))
		{
			$category->default_prefix_id = 0;
		}

		$fieldIds = $this->filter('available_fields', 'array-str');
		$form->complete(function() use ($category, $fieldIds)
		{
			/** @var \XenAddons\Showcase\Repository\CategoryField $repo */
			$repo = $this->repository('XenAddons\Showcase:CategoryField');
			$repo->updateContentAssociations($category->category_id, $fieldIds);
		});
		
		$reviewfieldIds = $this->filter('available_review_fields', 'array-str');
		$form->complete(function() use ($category, $reviewfieldIds)
		{
			/** @var \XenAddons\Showcase\Repository\CategoryReviewField $repo */
			$repo = $this->repository('XenAddons\Showcase:CategoryReviewField');
			$repo->updateContentAssociations($category->category_id, $reviewfieldIds);
		});
		
		$updatefieldIds = $this->filter('available_update_fields', 'array-str');
		$form->complete(function() use ($category, $updatefieldIds)
		{
			/** @var \XenAddons\Showcase\Repository\CategoryUpdateField $repo */
			$repo = $this->repository('XenAddons\Showcase:CategoryUpdateField');
			$repo->updateContentAssociations($category->category_id, $updatefieldIds);
		});

		return $form;
	}
	
	/**
	 * @return array
	 */
	protected function filterIndexCriteria()
	{
		$criteria = [];
	
		$input = $this->filterArray(
			$this->filter('index_criteria', 'array'),
			[
				'max_days_create' => [
					'enabled' => 'bool',
					'value' => 'posint'
				],
				'max_days_last_update' => [
					'enabled' => 'bool',
					'value' => 'posint'
				],
				'min_views' => [
					'enabled' => 'bool',
					'value' => 'posint'
				],
				'min_reviews' => [
					'enabled' => 'bool',
					'value' => 'posint'
				],
				'min_rating_avg' => [
					'enabled' => 'bool',
					'value' => 'int'
				],
				'min_reaction_score' => [
					'enabled' => 'bool',
					'value' => 'int'
				]
			]
		);
	
		foreach ($input AS $rule => $criterion)
		{
			if (!$criterion['enabled'])
			{
				continue;
			}
	
			$criteria[$rule] = $criterion['value'];
		}
	
		return $criteria;
	}
	
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->category_id)
		{
			$category = $this->assertCategoryExists($params->category_id);
		}
		else
		{
			$category = $this->em()->create('XenAddons\Showcase:Category');
		}

		$this->categorySaveProcess($category)->run();

		return $this->redirect(
			$this->buildLink('xa-sc/categories') . $this->buildLinkHash($category->getEntityId())
		);
	}

	public function actionDelete(ParameterBag $params)
	{
		return $this->getCategoryTreePlugin()->actionDelete($params);
	}

	public function actionSort()
	{
		return $this->getCategoryTreePlugin()->actionSort();
	}

	/**
	 * @return \XenAddons\Showcase\ControllerPlugin\CategoryPermission
	 */
	protected function getCategoryPermissionPlugin()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\CategoryPermission $plugin */
		$plugin = $this->plugin('XenAddons\Showcase:CategoryPermission');
		$plugin->setFormatters('XenAddons\Showcase:Category\Permission%s', 'xa_sc_category_permission_%s');
		$plugin->setRoutePrefix('xa-sc/categories/permissions');

		return $plugin;
	}

	public function actionPermissions(ParameterBag $params)
	{
		return $this->getCategoryPermissionPlugin()->actionList($params);
	}

	public function actionPermissionsEdit(ParameterBag $params)
	{
		return $this->getCategoryPermissionPlugin()->actionEdit($params);
	}

	public function actionPermissionsSave(ParameterBag $params)
	{
		return $this->getCategoryPermissionPlugin()->actionSave($params);
	}

	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \XenAddons\Showcase\Entity\Category
	 */
	protected function assertCategoryExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('XenAddons\Showcase:Category', $id, $with, $phraseKey);
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XenAddons\Showcase:Category');
	}
}