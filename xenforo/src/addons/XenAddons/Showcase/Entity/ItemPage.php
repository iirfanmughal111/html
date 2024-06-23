<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\Arr;

use function gmdate;

/**
 * COLUMNS
 * @property int|null $page_id
 * @property int $item_id
 * @property int $user_id
 * @property string $username
 * @property string $page_state 
 * @property int $create_date
 * @property int $edit_date
 * @property string $message
 * @property int $display_order
 * @property string $title
 * @property string $og_title
 * @property string $meta_title
 * @property int $display_byline
 * @property int $depth
 * @property string $description
 * @property string $meta_description
 * @property int $attach_count
 * @property int $cover_image_id
 * @property string $cover_image_caption
 * @property int $cover_image_above_page
 * @property int $has_poll
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 * @property int $warning_id
 * @property string $warning_message
 * @property int $last_edit_date
 * @property int $last_edit_user_id
 * @property int $edit_count
 * @property int $ip_id
 * @property array|null $embed_metadata

 * GETTERS
 * @property Item $Content
 * @property string $item_title
 * @property mixed $reactions
 * @property mixed $reaction_users
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Entity\Attachment $CoverImage
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Entity\User $User
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 */
class ItemPage extends Entity implements \XF\BbCode\RenderableContentInterface
{
	use ReactionTrait;
	
	public function canView(&$error = null)
	{
		$visitor = \XF::visitor();
		
		$item = $this->Item;
		
		if ($this->page_state == 'moderated')
		{
			if (
				!$item->hasPermission('viewModerated')
				&& (!$visitor->user_id || !$item->isContributor())
			)
			{
				return false;
			}
		}
		else if ($this->page_state == 'deleted')
		{
			if (!$item->hasPermission('viewDeleted'))
			{
				return false;
			}
		}

		return ($item ? ($item->canView($error) && $item->canViewFullItem()) : false);
	}
	
	public function canViewAttachments()
	{
		$item = $this->Item;
		
		return $item->canViewPageAttachments();
	}

	public function canEdit(&$error = null)
	{
		$item = $this->Item;
		
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		if ($item->hasPermission('editAny'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id && $item->hasPermission('editOwn'))
		{
			$editLimit = $item->hasPermission('editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit) && $item->item_state != 'draft')
			{
				$error = \XF::phrase('xa_sc_time_limit_to_edit_this_item_page_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return $item->canEdit();
	}

	public function canEditSilently(&$error = null)
	{
		$item = $this->Item;
		
		$visitor = \XF::visitor();
		if (!$visitor->user_id || !$item)
		{
			return false;
		}
	
		if ($item->hasPermission('editAny'))  // TODO create page permissions instead of piggybacking off item permissions!
		{
			return true;
		}
	
		return false;
	}
	
	public function canViewHistory(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		$item = $this->Item;
	
		if (!$this->app()->options()->editHistory['enabled'])
		{
			return false;
		}
	
		if ($item->hasPermission('editAny'))
		{
			return true;
		}
	
		return false;
	}
	
	public function canReassign(&$error = null)
	{
		$visitor = \XF::visitor();
	
		$item = $this->Item;
	
		return (
			$visitor->user_id
			&& $item->hasPermission('editAny')
			&& $item->hasPermission('reassign')
		);
	}

	public function canDelete($type = 'soft', &$error = null)
	{
		$item = $this->Item;
		
		$visitor = \XF::visitor();
		
		if ($type != 'soft')
		{
			return $item->hasPermission('hardDeleteAny');
		}
		
		if ($item->hasPermission('deleteAny'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id && $item->hasPermission('deleteOwn'))
		{
			$editLimit = $item->hasPermission('editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->create_date < \XF::$time - 60 * $editLimit) && $item->item_state != 'draft')
			{
				$error = \XF::phrase('xa_sc_time_limit_to_delete_this_item_page_x_minutes_has_expired', ['editLimit' => $editLimit]);
				return false;
			}
		
			return true;
		}
		
		return $item->canDelete($type);
	}
	
	public function canUndelete(&$error = null)
	{
		$item = $this->Item;
		
		$visitor = \XF::visitor();
		return $visitor->user_id && $item->hasPermission('undelete');
	}
	
	public function canSetCoverImage(&$error = null)
	{
		if (!$this->hasImageAttachments())
		{
			return false;
		}
	
		return $this->canEdit($error);
	}

	public function canReport(&$error = null, \XF\Entity\User $asUser = null)
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}
	
	public function canWarn(&$error = null)
	{
		$visitor = \XF::visitor();
		$item = $this->Item;
	
		if ($this->warning_id
			|| !$item
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$item->hasPermission('warn')
		)
		{
			return false;
		}
	
		$user = $this->User;
		return ($user && $user->isWarnable());
	}
	
	public function canReact(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($this->page_state != 'visible')
		{
			return false;
		}
	
		if ($this->user_id == $visitor->user_id)
		{
			$error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
			return false;
		}
	
		return $this->Item->hasPermission('react');  // TODO maybe add page permissions instead of piggybacking off of Item permissions? 
	}
	
	public function canCleanSpam()
	{
		return (\XF::visitor()->canCleanSpam() && $this->User && $this->User->isPossibleSpammer());
	}
	
	public function canCreatePoll(&$error = null)
	{
		if ($this->has_poll)
		{
			return false;
		}
	
		if (!$this->Item->Category->canCreatePoll())
		{
			return false;
		}
		
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		
		$categoryId = $this->Item->category_id;
	
		if ($visitor->hasShowcaseItemCategoryPermission($categoryId, 'editAny'))
		{
			return true;
		}
		
		if ($this->user_id == $visitor->user_id && $visitor->hasShowcaseItemCategoryPermission($categoryId, 'editOwn'))
		{
			$editLimit = $visitor->hasShowcaseItemCategoryPermission($categoryId, 'editOwnItemTimeLimit');
			if ($editLimit != -1 && (!$editLimit || $this->Item->create_date < \XF::$time - 60 * $editLimit) && $this->Item->item_state != 'draft')
			{
				$error = \XF::phraseDeferred('message_edit_time_limit_expired', ['minutes' => $editLimit]);
				return false;
			}
	
			return true;
		}
		
		return false;
	}	
	
	public function canUseInlineModeration(&$error = null)
	{
		// TODO implement inline moderation for Pages at some point?  
		
		return false;
	}
	
	public function canSendModeratorActionAlert()
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id
				&& $visitor->user_id != $this->user_id
				&& $this->page_state == 'visible'
		);
	}
	
	public function isVisible()
	{
		if ($this->page_state != 'visible')
		{
			return false;
		}
	
		$item = $this->Item;
		if (!$item)
		{
			return false;
		}
		else if ($item instanceof Item)
		{
			return ($item->item_state == 'visible');
		}
		else
		{
			return true;
		}
	}
	
	public function isIgnored()
	{
		return \XF::visitor()->isIgnoring($this->user_id);
	}	

	public function isAttachmentEmbedded($attachmentId)
	{
		if (!$this->embed_metadata)
		{
			return false;
		}
	
		if ($attachmentId instanceof \XF\Entity\Attachment)
		{
			$attachmentId = $attachmentId->attachment_id;
		}
	
		return isset($this->embed_metadata['attachments'][$attachmentId]);
	}
	
	public function hasImageAttachments($page = false)
	{
		if (!$this->attach_count)
		{
			return false;
		}
	
		if ($page && $page['Attachments'])
		{
			$attachments = $page['Attachments'];
		}
		else
		{
			$attachments = $this->Attachments;
		}
	
		foreach ($attachments AS $attachment)
		{
			if ($attachment['thumbnail_url'])
			{
				return true;
			}
		}
	
		return false;
	}
	
	public function getBreadcrumbs()
	{
		$item = $this->Item;
	
		return $item->getBreadcrumbs(true);
	}
	
	protected function getLdSnippet(string $message, int $length = null): string
	{
		if ($length === null)
		{
			$length = 250;
		}
	
		return \XF::app()->stringFormatter()->snippetString($message, $length, ['stripBbCode' => true]);
	}
	
	protected function getLdThumbnailUrl()
	{
		$thumbnailUrl = null;
	
		if ($this->CoverImage)
		{
			$thumbnailUrl = $this->CoverImage->getThumbnailUrlFull();
		}
		else if ($this->Item->CoverImage)
		{
			$thumbnailUrl = $this->Item->CoverImage->getThumbnailUrlFull();
		}
		else if ($this->Item->Category->content_image_url)
		{
			$thumbnailUrl = $this->Item->Category->getCategoryContentImageThumbnailUrlFull();
		}
	
		return $thumbnailUrl;
	}
	
	public function getLdStructuredData(int $page = 1, array $extraData = []): array
	{
		$router = $this->app()->router('public');
		$templater = \XF::app()->templater();
		$item = $this->Item;
	
		$output = [
			'@context'            => 'https://schema.org',
			'@type'               => 'CreativeWorkSeries',
			'@id'                 => $router->buildLink('canonical:showcase/page', $this),
			'name'                => $item->title . ' | ' . $this->title,
			'headline'            => ($item->meta_title ?: $item->title) . ' | ' . ($this->meta_title ?: $this->title),
			'alternativeHeadline' => ($item->og_title ?: $item->title) . ' | ' . ($this->og_title ?: $this->title),
			'description'         => $this->getLdSnippet($this->meta_description ?: $this->description ?: $this->message),
			'dateCreated'         => gmdate('c', $this->create_date),
			'dateModified'        => gmdate('c', $this->edit_date),
			'author'              => [
				'@type' => 'Person',
				'name'  => $this->User->username ?: $this->username,
			]
		];
	
		if ($thumbnailUrl = $this->getLdThumbnailUrl())
		{
			$output['thumbnailUrl'] = $thumbnailUrl;
		}
	
		$output['interactionStatistic'] = [
			'@type' => 'InteractionCounter',
			'interactionType' => 'https://schema.org/LikeAction',
			'userInteractionCount' => strval($this->reaction_score)
		];
	
		if ($item->hasViewableDiscussion())
		{
			$output['discussionUrl'] = $router->buildLink('canonical:threads', $item->Discussion);
		}
	
		return Arr::filterNull($output, true);
	}	
	
	/**
	 * @return string
	 */
	public function getItemTitle()
	{
		return $this->Item ? $this->Item->title : '';
	}
	
	/**
	 * @return Item
	 */
	public function getContent()
	{
		return $this->Item;
	}
	
	public function updateCoverImageIfNeeded()
	{
		$attachments = $this->Attachments;
	
		$imageAttachments = [];
		$fileAttachments = [];
	
		foreach ($attachments AS $key => $attachment)
		{
			if ($attachment['thumbnail_url'])
			{
				$imageAttachments[$key] = $attachment;
			}
			else
			{
				$fileAttachments[$key] = $attachment;
			}
		}
	
		if (!$this->cover_image_id)
		{
			if ($imageAttachments)
			{
				foreach ($imageAttachments AS $imageAttachment)
				{
					$coverImageId = $imageAttachment['attachment_id'];
					break;
				}
	
				if ($coverImageId)
				{
					$this->db()->query("
						UPDATE xf_xa_sc_item_page
						SET cover_image_id = ?
						WHERE page_id = ?
					", [$coverImageId, $this->page_id]);
				}
			}
		}
		elseif ($this->cover_image_id)
		{
			if (!$imageAttachments || !$imageAttachments[$this->cover_image_id])
			{
				$this->db()->query("
					UPDATE xf_xa_sc_item_page
					SET cover_image_id = 0
					WHERE page_id = ?
				", $this->page_id);
			}
		}
	}	
	
	public function getBbCodeRenderOptions($context, $type)
	{
		return [
			'entity' => $this,
			'user' => $this->Item->User,
			'attachments' => $this->attach_count ? $this->Attachments : [],
			'viewAttachments' => $this->canViewAttachments()
		];
	}

	protected function _preSave()
	{
		if (!$this->item_id)
		{
			throw new \LogicException("Need item ID");
		}
	}

	protected function _postSave()
	{
		$this->updateItemRecord();
		
		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('sc_page', $this);
		}
	}
	
	protected function updateItemRecord()
	{
		if (!$this->Item)
		{
			return;
		}
		
		$item = $this->Item;
		
		if ($this->isInsert())
		{
			$item->pageAdded($this);
			$item->save();
		}
	}	

	protected function _postDelete()
	{
		$db = $this->db();
		
		$this->Item->pageRemoved($this);
		$this->Item->save();
		
		if ($this->has_poll && $this->Poll)
		{
			$this->Poll->delete();
		}
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_page', $this, 'delete_hard');
		}

		$db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->page_id, 'sc_page']);
		$db->delete('xf_edit_history', 'content_id = ? AND content_type = ?', [$this->page_id, 'sc_page']);
		
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->fastDeleteContentAttachments('sc_page', $this->page_id);
		
		/** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('sc_page', $this->page_id);
	}
	
	public function softDelete($reason = '', \XF\Entity\User $byUser = null)
	{
		$byUser = $byUser ?: \XF::visitor();
	
		if ($this->page_state == 'deleted')
		{
			return false;
		}
	
		$this->page_state = 'deleted';
	
		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;
	
		$this->save();
	
		return true;
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_page';
		$structure->shortName = 'XenAddons\Showcase:ItemPage';
		$structure->primaryKey = 'page_id';
		$structure->contentType = 'sc_page';
		$structure->columns = [
			'page_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'username' => ['type' => self::STR, 'maxLength' => 50,
				'required' => 'please_enter_valid_name'
			],
			'page_state' => ['type' => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'deleted', 'draft']
			],			
			'create_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'edit_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'message' => ['type' => self::STR,
				'required' => 'please_enter_valid_message'
			],			
			'display_order' => ['type' => self::UINT, 'default' => 1],			
			'title' => ['type' => self::STR, 'maxLength' => 150,
				'required' => 'please_enter_valid_title',
				'censor' => true
			],
			'og_title' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],
			'meta_title' => ['type' => self::STR, 'maxLength' => 100,
				'default' => '',
				'censor' => true
			],			
			'display_byline' => ['type' => self::BOOL, 'default' => false],
			'depth' => ['type' => self::UINT, 'default' => 0],			
			'description' => ['type' => self::STR, 'maxLength' => 256,
				'default' => '',
				'censor' => true
			],			
			'meta_description' => ['type' => self::STR, 'maxLength' => 320,
				'default' => '',
				'censor' => true
			],
			'attach_count' => ['type' => self::UINT, 'default' => 0],
			'cover_image_id' => ['type' => self::UINT, 'default' => 0],
			'cover_image_caption' => ['type' => self::STR, 'maxLength' => 500,
				'default' => '',
				'censor' => true
			],
			'cover_image_above_page' => ['type' => self::BOOL, 'default' => false],
			'has_poll' => ['type' => self::BOOL, 'default' => false],
			'warning_id' => ['type' => self::UINT, 'default' => 0],
			'warning_message' => ['type' => self::STR, 'default' => '', 'maxLength' => 255],
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
			'edit_count' => ['type' => self::UINT, 'default' => 0],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'embed_metadata' => ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null]
		];
		$structure->getters = [
			'Content' => true,
			'item_title' => true
		];
		$structure->behaviors = [
			'XF:Reactable' => ['stateField' => 'page_state'],
			'XF:NewsFeedPublishable' => [
				'usernameField' => 'username',
				'dateField' => 'create_date'
			],
			'XF:Indexable' => [
				'checkForUpdates' => ['title', 'og_title', 'meta_title', 'description', 'meta_description', 'message', 'user_id', 'create_date', 'page_state']
			]
		];
		$structure->relations = [
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Poll' => [
				'entity' => 'XF:Poll',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_page'],
					['content_id', '=', '$page_id']
				]
			],
			'CoverImage' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_page'],
					['content_id', '=', '$page_id'],
					['attachment_id', '=', '$cover_image_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],
			'Attachments' => [
				'entity' => 'XF:Attachment',
				'type' => self::TO_MANY,
				'conditions' => [
					['content_type', '=', 'sc_page'],
					['content_id', '=', '$page_id']
				],
				'with' => 'Data',
				'order' => 'attach_date'
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_page'],
					['content_id', '=', '$page_id']
				],
				'primary' => true
			]
		];
		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = [
			'Item', 'User'
		];
		$structure->withAliases = [
			'full' => [
				'User',
				'CoverImage',
				function()
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return ['Reactions|' . $userId];
					}
				}
			]
		];
		
		static::addReactableStructureElements($structure);
		
		return $structure;
	}
}