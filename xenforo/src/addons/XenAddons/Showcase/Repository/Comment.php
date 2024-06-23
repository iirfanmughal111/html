<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Repository;
use XF\Util\Arr;

class Comment extends Repository
{
	public function findCommentsForContent(\XF\Mvc\Entity\Entity $content, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\Comment $finder */
		$finder = $this->finder('XenAddons\Showcase:Comment');
		$finder
			->forContent($content, $limits)
			->orderByDate()
			->with('full');

		return $finder;
	}

	public function findLatestCommentsForContent(\XF\Mvc\Entity\Entity $content, $newerThan, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\Comment $finder */
		$finder = $this->finder('XenAddons\Showcase:Comment');
		$finder
			->forContent($content, $limits)
			->orderByDate('DESC')
			->newerThan($newerThan)
			->with('full');

		return $finder;
	}

	public function findNextCommentsInContent(\XF\Mvc\Entity\Entity $content, $newerThan, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\Comment $finder */
		$finder = $this->finder('XenAddons\Showcase:Comment');
		$finder
			->forContent($content, $limits)
			->orderByDate()
			->newerThan($newerThan);

		return $finder;
	}

	public function findLatestCommentsForWidget(array $viewableCategoryIds = null)
	{
		$finder = $this->finder('XenAddons\Showcase:Comment');

		if (is_array($viewableCategoryIds))
		{
			$finder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$finder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$finder
			->where('comment_state', 'visible')
			->orderByDate('DESC')
			->with([
				'Item.Category',
			]);

		return $finder;
	}
	
	public function findCommentsForUser(\XF\Entity\User $user)
	{
		/** @var \XenAddons\Showcase\Finder\Comment $finder */
		$finder = $this->finder('XenAddons\Showcase:Comment');
		$finder->where('user_id', $user->user_id);
	
		$finder->where([
			'user_id' => $user->user_id,
			'comment_state' => 'visible'
		]);
	
		return $finder;
	}	
	
	public function getCommentAttachmentConstraints()
	{
		$options = $this->options();
	
		return [
			'extensions' => Arr::stringToArray($options->xaScCommentAllowedFileExtensions),
			'size' => $options->xaScCommentAttachmentMaxFileSize * 1024,
			'width' => $options->attachmentMaxDimensions['width'],
			'height' => $options->attachmentMaxDimensions['height']
		];
	}
	
	public function getUserCommentCount($userId)
	{
		return $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_comment
			WHERE user_id = ?
				AND comment_state = 'visible'
		", $userId);
	}

	public function sendModeratorActionAlert(\XenAddons\Showcase\Entity\Comment $comment, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null)
	{
		if (!$forceUser)
		{
			if (!$comment->user_id || !$comment->User)
			{
				return false;
			}
		
			$forceUser = $comment->User;
		}

		$extra = array_merge([
			'title' => $comment->Content->title,
			'prefix_id' => $comment->Content->prefix_id,
			'link' => $this->app()->router('public')->buildLink('nopath:showcase/comments', $comment),
			'itemLink' => $this->app()->router('public')->buildLink('nopath:showcase', $comment->Content),
			'reason' => $reason,
			'content_type' => 'sc_comment'
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_comment_{$action}", $extra,
			['dependsOnAddOnId' => 'XenAddons/Showcase']
		);

		return true;
	}
	
	
	
	
	// NOTE: this is an experiment (BETA) to include image attachments from comments in the item gallery
	// UPDATE: so far this is working as expected, so probably remove the BETA status in the XF 2.3 version!
	
	public function getCommentsImagesForItemGallery(\XenAddons\Showcase\Entity\Item $item, $fetchType = 'owners')
	{
		$db = $this->db();
	
		$ids = null;
	
		if ($fetchType == 'owners')
		{
			// only fetch from comments that the item owner or co-owners posted
	
			$ownerIds = [];
			$ownerIds[] = $item->user_id;
	
			if ($item->Contributors)
			{
				foreach ($item->Contributors AS $contributorID => $contributor)
				{
					if ($contributor->is_co_owner)
					{
						$ownerIds[] = $contributorID;
					}
				}
			}
				
			$ids = $db->fetchAllColumn("
					SELECT comment_id
					FROM xf_xa_sc_comment
					WHERE item_id = ?
					AND user_id IN (" . $db->quote($ownerIds) . ")
					AND comment_state = 'visible'
					AND attach_count > 0
					ORDER BY comment_id
				", $item->item_id
			);
		}
		else if ($fetchType == 'contributors')
		{
			// only fetch from comments that any contributors (owner, co-owners, contributors) posted
	
			$contributorIds = $item->contributor_user_ids;
			array_push($contributorIds, $item->user_id); // this adds the item owner user_id
				
			$ids = $db->fetchAllColumn("
					SELECT comment_id
					FROM xf_xa_sc_comment
					WHERE item_id = ?
					AND user_id IN (" . $db->quote($contributorIds) . ")
					AND comment_state = 'visible'
					AND attach_count > 0
					ORDER BY comment_id
				", $item->item_id
			);
		}
		else if ($fetchType == 'all')
		{
			// fetch image attachments from all comments
				
			$ids = $db->fetchAllColumn("
					SELECT comment_id
					FROM xf_xa_sc_comment
					WHERE item_id = ?
					AND comment_state = 'visible'
					AND attach_count > 0
					ORDER BY comment_id
				", $item->item_id
			);
		}
	
		if ($ids)
		{
			$attachments = $this->finder('XF:Attachment')
				->where([
					'content_type' => 'sc_comment',
					'content_id' => $ids
				])
				->order('attach_date')
				->fetch();
		}
		else
		{
			$attachments = $this->em->getEmptyCollection();
		}
	
		return $attachments;
	}
}