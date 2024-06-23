<?php

namespace ThemeHouse\PostComments\Import\Importer;

use XF\Import\Importer\AbstractImporter;
use XF\Import\StepState;


class TruonglvPostReply extends AbstractImporter
{
	/**
	 * @return array
	 */
	public static function getListInfo()
	{
		return [
			'target' => '[OzzModz] Post Comments',
			'source' => '[tl] Post Reply'
		];
	}

	/**
	 * @param array $vars
	 * @return bool
	 */
	public function renderBaseConfigOptions(array $vars)
	{
		return false;
	}

	/**
	 * @param array $baseConfig
	 * @param array $errors
	 * @return bool
	 */
	public function validateBaseConfig(array &$baseConfig, array &$errors)
	{
		return true;
	}

	/**
	 * @param array $vars
	 * @return bool
	 */
	public function renderStepConfigOptions(array $vars)
	{
		return false;
	}

	/**
	 * @param array $steps
	 * @param array $stepConfig
	 * @param array $errors
	 * @return bool
	 */
	public function validateStepConfig(array $steps, array &$stepConfig, array &$errors)
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function canRetainIds()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function resetDataForRetainIds()
	{
		return false;
	}

	/**
	 * @return array
	 */
	public function getSteps()
	{
		return [
			'posts' => ['title' => 'Posts']
		];
	}

	/**
	 * @return int
	 */
	public function getStepEndPosts()
	{
		return $this->db()->fetchOne('SELECT MAX(post_id) FROM xf_tl_post_reply_item') ?: 0;
	}

	/**
	 * @param StepState $state
	 * @param array $stepConfig
	 * @param $maxTime
	 * @return $this|StepState
	 * @throws \XF\PrintableException
	 */
	public function stepPosts(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;

		$items = $this->db()->fetchAll(
			"
            SELECT *
            FROM xf_tl_post_reply_item
            WHERE post_id > ? AND post_id <= ?
            ORDER BY post_id
            LIMIT {$limit}
        ",
			[
				$state->startAfter,
				$state->end
			]
		);

		foreach ($items as $item)
		{
			/** @var \ThemeHouse\PostComments\XF\Entity\Post $postEntity */
			$postEntity = \XF::finder('XF:Post')
				->where('post_id', '=', $item['post_id'])
				->fetchOne();

			if (!$postEntity)
			{
				continue;
			}

			$postEntity->thpostcomments_root_post_id = $item['post_id'];
			$postEntity->thpostcomments_parent_post_id = $item['parent_id'];

			$parentPost = $this->db()->fetchRow('
                 SELECT *
                 FROM xf_post
                 WHERE post_id = ?
            ', $item['parent_id']);

			if ($parentPost)
			{
				$postEntity->position = $parentPost['position'];
			}

			$postEntity->save();

			$state->imported++;
			$state->startAfter = $item['post_id'];
		}

		if ($state->startAfter === $state->end)
		{
			return $state->complete();
		}

		return $state;
	}

	/**
	 * @param array $stepsRun
	 * @return array
	 */
	public function getFinalizeJobs(array $stepsRun)
	{
		return [
			'ThemeHouse\PostComments:RebuildNesting',
		];
	}

	/**
	 * @return array
	 */
	protected function getBaseConfigDefault()
	{
		return [];
	}

	/**
	 * @return array
	 */
	protected function getStepConfigDefault()
	{
		return [];
	}

	/**
	 * @return bool
	 */
	protected function doInitializeSource()
	{
		return true;
	}
}
