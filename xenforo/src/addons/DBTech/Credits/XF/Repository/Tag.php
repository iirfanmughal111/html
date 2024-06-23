<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Repository;

class Tag extends XFCP_Tag
{
	/**
	 * @param array $tagIds
	 * @param $contentType
	 * @param $contentId
	 * @param $contentDate
	 * @param $contentVisible
	 * @param $addUserId
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function addTagIdsToContent(array $tagIds, $contentType, $contentId, $contentDate, $contentVisible, $addUserId)
	{
		$insertedIds = parent::addTagIdsToContent($tagIds, $contentType, $contentId, $contentDate, $contentVisible, $addUserId);

		$handler = $this->getTagHandler($contentType, true);
		if (!$handler)
		{
			return $insertedIds;
		}

		$content = $handler->getContent($contentId);
		if (!$content)
		{
			return $insertedIds;
		}

		$nodeId = 0;
		switch ($contentType)
		{
			case 'tl_group':
				return $insertedIds;

			case 'thread':
				$nodeId = $content->node_id;
				break;
		}
		
		/** @var \DBTech\Credits\XF\Entity\User $addUser */
		$addUser = $this->em->find('XF:User', $addUserId);
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		$tagHandler = $eventTriggerRepo->getHandler('tag');
		
		foreach ($insertedIds AS $addId)
		{
			$tagHandler
				->apply($addId, [
					'node_id' => $nodeId,
					'owner_id' => $content->user_id,
					'content_type' => $contentType,
					'content_id' => $contentId
				], $addUser)
			;
		}

		return $insertedIds;
	}
	
	/**
	 * @param array $tagIds
	 * @param $contentType
	 * @param $contentId
	 *
	 * @throws \Exception
	 */
	protected function removeTagIdsFromContent(array $tagIds, $contentType, $contentId)
	{
		if ($tagIds)
		{
			$handler = $this->getTagHandler($contentType, true);
			if (!$handler)
			{
				return parent::removeTagIdsFromContent($tagIds, $contentType, $contentId);
			}

			$content = $handler->getContent($contentId);
			if (!$content)
			{
				return parent::removeTagIdsFromContent($tagIds, $contentType, $contentId);
			}

			$db = $this->db();
			$deletedTags = $db->fetchAll("
				SELECT *
				FROM xf_tag_content
				WHERE tag_id IN (" . $db->quote($tagIds) . ")
					AND content_type = ?
					AND content_id = ?
			", [$contentType, $contentId]);

			$nodeId = 0;
			switch ($contentType)
			{
				case 'thread':
					$nodeId = $content->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$tagHandler = $eventTriggerRepo->getHandler('tag');
			
			foreach ($deletedTags AS $tag)
			{
				/** @var \DBTech\Credits\XF\Entity\User $addUser */
				$addUser = $this->em->find('XF:User', $tag['add_user_id']);
				
				$tagHandler
					->undo($tag['tag_id'], [
						'node_id' => $nodeId,
						'owner_id' => $content->user_id,
						'content_type' => $contentType,
						'content_id' => $contentId
					], $addUser)
				;
			}
		}

		return parent::removeTagIdsFromContent($tagIds, $contentType, $contentId);
	}
}