<?php

namespace DBTech\eCommerce\Entity;

use XF\Entity\ContentVoteTrait;
use XF\Entity\LinkableInterface;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $product_rating_id
 * @property int $product_id
 * @property int $user_id
 * @property int $rating
 * @property int $rating_date
 * @property string $message
 * @property string $version_string
 * @property string $author_response
 * @property bool $is_review
 * @property bool $count_rating
 * @property string $rating_state
 * @property int $warning_id
 * @property bool $is_anonymous
 * @property array $custom_fields_
 * @property int $vote_score
 * @property int $vote_count
 *
 * GETTERS
 * @property string $product_title
 * @property \XF\CustomField\Set $custom_fields
 * @property mixed $vote_score_short
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \XF\Entity\User $User
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ContentVote[] $ContentVotes
 */
class ProductRating extends Entity implements LinkableInterface
{
	use ContentVoteTrait;

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canView(&$error = null): bool
	{
		$product = $this->Product;

		if (!$product || !$product->canView($error))
		{
			return false;
		}

		if ($this->rating_state == 'deleted')
		{
			if (!$product->hasPermission('viewDeleted'))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canEdit(&$error = null): bool
	{
		$product = $this->Product;

		if (!$product || !$product->canView($error))
		{
			return false;
		}

		// @TODO: NYI
		return false;
	}

	/**
	 * @param string $type
	 * @param null $error
	 *
	 * @return bool|mixed
	 */
	public function canDelete(string $type = 'soft', &$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;

		if (!$visitor->user_id || !$product)
		{
			return false;
		}

		if ($type != 'soft')
		{
			return (
				$product->hasPermission('hardDeleteAny')
				&& $product->hasPermission('deleteAnyReview')
			);
		}

		if ($this->user_id == $visitor->user_id && !$this->author_response)
		{
			return true;
		}

		return $product->hasPermission('deleteAnyReview');
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canUpdate(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;

		if (
			!$visitor->user_id
			|| $visitor->user_id != $this->user_id
			|| !$product
			|| !$product->hasPermission('rate')
		) {
			return false;
		}

		if ($this->rating_state != 'visible' || !$this->is_review)
		{
			return true;
		}

		if ($this->author_response)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_cannot_update_rating_once_author_response');
			return false;
		}

		return true;
	}

	/**
	 * @param null $error
	 *
	 * @return bool|mixed
	 */
	public function canUndelete(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;

		if (!$visitor->user_id || !$product)
		{
			return false;
		}

		return $product->hasPermission('undelete');
	}

	/**
	 * @param null $error
	 * @param \XF\Entity\User|null $asUser
	 *
	 * @return bool
	 */
	public function canReport(&$error = null, \XF\Entity\User $asUser = null): bool
	{
		$asUser = $asUser ?: \XF::visitor();
		return $asUser->canReport($error);
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canWarn(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;

		if (
			$this->warning_id
			|| !$product
			|| !$visitor->user_id
			|| $this->user_id == $visitor->user_id
			|| !$product->hasPermission('warn')
		) {
			return false;
		}

		$user = $this->User;
		return ($user && $user->isWarnable());
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canReply(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;

		return (
			$visitor->user_id
			&& $product
			&& $product->user_id == $visitor->user_id
			&& $this->is_review
			&& !$this->author_response
			&& $this->rating_state == 'visible'
			&& $product->hasPermission('reviewReply')
		);
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canDeleteAuthorResponse(&$error = null): bool
	{
		$visitor = \XF::visitor();
		$product = $this->Product;

		if (!$visitor->user_id || !$this->is_review || !$this->author_response || !$product)
		{
			return false;
		}

		return (
			$visitor->user_id == $this->Product->user_id
			|| $product->hasPermission('deleteAnyReview')
		);
	}

	/**
	 * @return bool
	 */
	public function canViewAnonymousAuthor(): bool
	{
		$visitor = \XF::visitor();

		return (
			$visitor->user_id
			&& (
				$visitor->user_id == $this->user_id
				|| $visitor->canBypassUserPrivacy()
			)
		);
	}

	/**
	 * @return bool
	 */
	public function isContentVotingSupported(): bool
	{
		return $this->app()->options()->dbtechEcommerceReviewVoting !== '';
	}

	/**
	 * @return bool
	 */
	public function isContentDownvoteSupported(): bool
	{
		return $this->app()->options()->dbtechEcommerceReviewVoting === 'yes';
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function canVoteOnContentInternal(&$error = null): bool
	{
		if (!$this->isVisible())
		{
			return false;
		}

		$product = $this->Product;

		if ($product->user_id == \XF::visitor()->user_id)
		{
			return false;
		}

		return $product->hasPermission('contentVote');
	}

	/**
	 * @return bool
	 */
	public function canSendModeratorActionAlert(): bool
	{
		$product = $this->Product;

		return (
			$product
			&& $product->canSendModeratorActionAlert()
			&& $this->rating_state == 'visible'
		);
	}

	/**
	 * @return bool
	 */
	public function isVisible(): bool
	{
		return (
			$this->rating_state == 'visible'
			&& $this->Product
			&& $this->Product->isVisible()
		);
	}

	/**
	 * @return bool
	 */
	public function isIgnored(): bool
	{
		if ($this->is_anonymous)
		{
			return false;
		}

		return \XF::visitor()->isIgnoring($this->user_id);
	}

	/**
	 * @return string
	 */
	public function getProductTitle(): string
	{
		return $this->Product ? $this->Product->title : '';
	}

	/**
	 * @return \XF\CustomField\Set
	 * @throws \Exception
	 */
	public function getCustomFields(): \XF\CustomField\Set
	{
		$class = 'XF\CustomField\Set';
		$class = $this->app()->extendClass($class);

		/** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
		$fieldDefinitions = $this->app()->container('customFields.dbtechEcommerceReviews');

		return new $class($fieldDefinitions, $this);
	}

	/**
	 * @param bool $canonical
	 * @param array $extraParams
	 * @param null $hash
	 *
	 * @return mixed|string
	 */
	public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null): string
	{
		$route = $canonical ? 'canonical:dbtech-ecommerce/review' : 'dbtech-ecommerce/review';
		return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
	}

	/**
	 * @return string|null
	 */
	public function getContentPublicRoute(): ?string
	{
		return 'dbtech-ecommerce/review';
	}

	/**
	 * @param string $context
	 *
	 * @return string|\XF\Phrase
	 */
	public function getContentTitle(string $context = '')
	{
		if ($this->Product)
		{
			return \XF::phrase('dbtech_ecommerce_review_for_x', ['title' => $this->Product->full_title]);
		}

		return \XF::phrase('dbtech_ecommerce_review_for_x', ['title' => 'N/A']);
	}

	/**
	 *
	 * @throws \LogicException
	 */
	protected function _preSave()
	{
		if ($this->isUpdate() && $this->isChanged(['message', 'rating', 'user_id']))
		{
			throw new \LogicException('Cannot change rating message, value or user');
		}

		if ($this->isChanged('message'))
		{
			$this->is_review = strlen($this->message) ? true : false;
		}

		if (!$this->user_id)
		{
			throw new \LogicException('Need user ID');
		}
	}

	/**
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 */
	protected function _postSave()
	{
		$visibilityChange = $this->isStateChanged('rating_state', 'visible');
		$deletionChange = $this->isStateChanged('rating_state', 'deleted');

		if ($this->isUpdate())
		{
			if ($visibilityChange == 'enter')
			{
				$this->ratingMadeVisible();
			}
			elseif ($visibilityChange == 'leave')
			{
				$this->ratingHidden();
			}

			if ($deletionChange == 'leave' && $this->DeletionLog)
			{
				$this->DeletionLog->delete();
			}
		}
		else
		{
			// insert
			if ($this->rating_state == 'visible')
			{
				$this->ratingMadeVisible();
			}
		}

		if ($deletionChange == 'enter' && !$this->DeletionLog)
		{
			$delLog = $this->getRelationOrDefault('DeletionLog', false);
			$delLog->setFromVisitor();
			$delLog->save();
		}

		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('dbtech_ecommerce_rating', $this);
		}
	}

	/**
	 *
	 * @throws \LogicException
	 */
	protected function ratingMadeVisible()
	{
		$product = $this->Product;

		if ($product)
		{
			if ($this->is_review)
			{
				$product->review_count++;
			}

			if ($this->rebuildRatingCounted())
			{
				$product->rebuildRating();
			}

			$product->saveIfChanged();
		}
	}

	/**
	 * @param bool $hardDelete
	 *
	 * @throws \LogicException
	 */
	protected function ratingHidden(bool $hardDelete = false)
	{
		$product = $this->Product;

		if ($product)
		{
			if ($this->is_review)
			{
				$product->review_count--;
			}

			if ($this->count_rating)
			{
				$product->rebuildRating();
			}

			$product->saveIfChanged();
		}

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsForContent('dbtech_ecommerce_rating', $this->product_rating_id);
	}

	/**
	 * @return bool
	 * @throws \LogicException
	 */
	protected function rebuildRatingCounted(): bool
	{
		/** @var \DBTech\eCommerce\Repository\ProductRating $ratingRepo */
		$ratingRepo = $this->repository('DBTech\eCommerce:ProductRating');

		$countable = $ratingRepo->getCountableRating($this->product_id, $this->user_id);
		if ($countable && $countable->count_rating)
		{
			// already counted, no action needed
			return false;
		}

		$rebuildRequired = false;

		$counted = $ratingRepo->getCountedRatings($this->product_id, $this->user_id);

		if ($countable)
		{
			$countable->fastUpdate('count_rating', true);
			$rebuildRequired = true;
		}

		foreach ($counted as $count)
		{
			if ($countable && $count->product_rating_id == $countable->product_rating_id)
			{
				// we've just set this to be counted, ignore it
				continue;
			}

			$count->fastUpdate('count_rating', false);
			$rebuildRequired = true;
		}

		return $rebuildRequired;
	}

	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function _postDelete()
	{
		if ($this->rating_state == 'visible')
		{
			$this->ratingHidden(true);
		}

		if ($this->rating_state == 'deleted' && $this->DeletionLog)
		{
			$this->DeletionLog->delete();
		}

		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('dbtech_ecommerce_rating', $this, 'delete_hard');
		}
	}

	/**
	 * @param string $reason
	 * @param \XF\Entity\User|null $byUser
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function softDelete(string $reason = '', \XF\Entity\User $byUser = null): bool
	{
		$byUser = $byUser ?: \XF::visitor();

		if ($this->rating_state == 'deleted')
		{
			return false;
		}

		$this->rating_state = 'deleted';

		/** @var \XF\Entity\DeletionLog $deletionLog */
		$deletionLog = $this->getRelationOrDefault('DeletionLog');
		$deletionLog->setFromUser($byUser);
		$deletionLog->delete_reason = $reason;

		$this->save();

		return true;
	}

	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param int $verbosity
	 * @param array $options
	 *
	 * @api-type ProductRating
	 *
	 * @api-out Product $product <cond> If the "with_product" option is passed to the API Result generation.
	 * @api-out bool $can_soft_delete
	 * @api-out bool $can_hard_delete
	 * @api-out bool $can_author_reply
	 * @api-out User $AnonymousUser <cond> If this review was anonymous, this is the exposed user record. Only available with permission.
	 * @api-out int $anonymous_user_id <cond> If this review was anonymous, this is the exposed user ID. Only available with permission.
	 * @api-out User $User <cond> If this review was not anonymous, this is the user record. Only available for public reviews.
	 * @api-out int $user_id <cond> If this review was not anonymous, this is the user ID. Only available for public reviews.
	 */
	protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result,
		$verbosity = self::VERBOSITY_NORMAL,
		array $options = []
	) {
		if (!empty($options['with_product']))
		{
			$result->includeRelation('Product');
		}

		$category = $this->Product->Category;
		if ($category)
		{
			$result->custom_fields = (object)$this->custom_fields->getNamedFieldValues($category->review_field_cache);
		}

		$result->can_soft_delete = $this->canDelete();
		$result->can_hard_delete = $this->canDelete('hard');
		$result->can_author_reply = $this->canReply();

		if ($this->is_anonymous)
		{
			if ($this->canViewAnonymousAuthor() || \XF::isApiBypassingPermissions())
			{
				$result->AnonymousUser = $this->User;
				$result->anonymous_user_id = $this->user_id;
			}
		}
		else
		{
			$result->includeColumn('user_id');
			$result->includeRelation('User');
		}
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_rating';
		$structure->shortName = 'DBTech\eCommerce:ProductRating';
		$structure->primaryKey = 'product_rating_id';
		$structure->contentType = 'dbtech_ecommerce_rating';
		$structure->columns = [
			'product_rating_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'product_id'        => ['type' => self::UINT, 'required' => true, 'api' => true],
			'user_id'           => ['type' => self::UINT, 'required' => true],
			'rating'            => ['type' => self::UINT, 'required' => true, 'min' => 1, 'max' => 5, 'api' => true],
			'rating_date'       => ['type' => self::UINT, 'default' => \XF::$time, 'api' => true],
			'message'           => ['type' => self::STR, 'default' => '', 'api' => true],
			'version_string'    => ['type' => self::STR, 'required' => true, 'api' => true],
			'author_response'   => ['type' => self::STR, 'default' => '', 'api' => true],
			'is_review'         => ['type' => self::BOOL, 'default' => false],
			'count_rating'      => ['type' => self::BOOL, 'default' => false],
			'rating_state'      => [
				'type'          => self::STR, 'default' => 'visible',
				'allowedValues' => ['visible', 'deleted'], 'api' => true
			],
			'warning_id'        => ['type' => self::UINT, 'default' => 0],
			'is_anonymous'      => ['type' => self::BOOL, 'default' => false, 'api' => true],
			'custom_fields'     => ['type' => self::JSON_ARRAY, 'default' => []]
		];
		$structure->getters = [
			'product_title' => true,
			'custom_fields' => true
		];
		$structure->behaviors = [
			'XF:ContentVotable' => ['stateField' => 'rating_state'],
			'XF:NewsFeedPublishable' => [
				'userIdField'   => function (ProductRating $rating): int
				{
					return $rating->is_anonymous ? 0 : $rating->user_id;
				},
				'usernameField' => function (ProductRating $rating): string
				{
					return $rating->is_anonymous ? '' : $rating->User->username;
				},
				'dateField'     => 'rating_date'
			],
			'XF:CustomFieldsHolder' => [
				'valueTable' => 'xf_dbtech_ecommerce_product_review_field_value'
			]
		];
		$structure->relations = [
			'Product'     => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
			'User'        => [
				'entity'     => 'XF:User',
				'type'       => self::TO_ONE,
				'conditions' => 'user_id',
				'primary'    => true
			],
			'DeletionLog' => [
				'entity'     => 'XF:DeletionLog',
				'type'       => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'dbtech_ecommerce_rating'],
					['content_id', '=', '$product_rating_id']
				],
				'primary'    => true
			]
		];

		$structure->withAliases = [
			'full' => [
				'Product.full|category',
				'User'
			],
			'api'  => [
				'Product.full|category',
				'Product.api|category',
				'User',
				'User.api'
			]
		];

		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = ['Product'];

		static::addVotableStructureElements($structure);

		return $structure;
	}
}