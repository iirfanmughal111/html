<?php

namespace XenAddons\Showcase\Service\Item;

use XF\Entity\User;
use XF\Mvc\Entity\AbstractCollection;
use XF\Service\AbstractService;
use XF\Service\ValidateAndSavableTrait;
use XF\Util\Arr;
use XenAddons\Showcase\Entity\Item;

class ContributorsManager extends AbstractService
{
	use ValidateAndSavableTrait;

	/**
	 * @var Item
	 */
	protected $item;

	/**
	 * @var bool
	 */
	protected $autoSendNotifications = true;

	/**
	 * @var \XF\Entity\User[]|AbstractCollection
	 */
	protected $addCoOwners;
	
	/**
	 * @var \XF\Entity\User[]|AbstractCollection
	 */
	protected $setCoOwners;
	
	/**
	 * @var \XF\Entity\User[]|AbstractCollection
	 */
	protected $addContributors;

	/**
	 * @var \XF\Entity\User[]|AbstractCollection
	 */
	protected $removeContributors;

	/**
	 * @var \XF\Entity\User[]
	 */
	protected $addSelfJoinContributor;

	/**
	 * @var bool
	 */
	protected $sendJoinNotification = false;
		
	/**
	 * @var bool
	 */
	protected $sendLeaveNotification = false;

	/**
	 * @var array
	 */
	protected $errors = [];

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);

		$this->item = $item;
		$this->addCoOwners = $this->em()->getEmptyCollection();
		$this->setCoOwners = $this->em()->getEmptyCollection();
		$this->addContributors = $this->em()->getEmptyCollection();
		$this->removeContributors = $this->em()->getEmptyCollection();
		$this->addSelfJoinContributor = [];
	}

	public function getItem(): Item
	{
		return $this->item;
	}

	public function getCurrentContributorCount(): int
	{
		return $this->item->Contributors->count();
	}

	public function getNewContributorCount(): int
	{
		$currentCount = $this->getCurrentContributorCount();
		
		$addingNewContributorsCount = $this->addContributors ? $this->addContributors->count() : 0;
		$addingNewCoOwnersCount = $this->addCoOwners ? $this->addCoOwners->count() : 0;
		$addingNewSelfJoinContributorCount = $this->addSelfJoinContributor ? 1 : 0;
		
		$addingCount = $addingNewContributorsCount + $addingNewCoOwnersCount + $addingNewSelfJoinContributorCount;
		
		$removingCount = $this->removeContributors ? $this->removeContributors->count() : 0;
		
		return $currentCount + $addingCount - $removingCount;
	}

	public function setAutoSendNotifications(bool $send)
	{
		$this->autoSendNotifications = $send;
	}
	
	/**
	 * @param AbstractCollection|string $coOwners Collection of users or comma-separated usernames (Co Owners are also and count as, "contributors"). 
	 */
	public function addCoOwners($coOwners)
	{
		if (is_string($coOwners))
		{
			/** @var \XF\Repository\User $userRepo */
			$userRepo = $this->repository('XF:User');
			$usernames = Arr::stringToArray($coOwners, '/\s*,\s*/');
			$users = $userRepo->getUsersByNames($usernames, $notFound);
	
			if ($notFound)
			{
				$this->errors[] = \XF::phrase(
					'following_members_not_found_x',
					['members' => implode(', ', $notFound)]
				);
			}
		}
		else if ($coOwners instanceof AbstractCollection)
		{
			$users = $coOwners->filter(function($member)
			{
				return $member instanceof User;
			});
		}
		else
		{
			throw new \InvalidArgumentException(
				'CoOwners must be a collection of users or a string of comma-separated usernames'
			);
		}
	
		$addCoOwners = [];
		$setCoOwners = [];
		$invalidCoOwners = [];
		foreach ($users AS $userId => $user)
		{
			if (
				$userId == $this->item->user_id 
				|| $this->item->Contributors[$userId]  // Member is already a contributor 
			)
			{
				if ($this->item->user_id == $user->user_id)
				{
					$invalidCoOwners[$userId] = $user->username;
					continue;
				}
				
				$setCoOwners[$userId] = $user;
				continue;
			}
	
			if (!$this->item->canContributorBeAdded($user))  // Note, this method is used for both co owners and contributors!
			{
				$invalidCoOwners[$userId] = $user->username;
				continue;
			}
	
			$addCoOwners[$userId] = $user;
		}
	
		if ($invalidCoOwners)
		{
			$this->errors[] = \XF::phrase(
				'xa_sc_you_may_not_add_following_co_owners_to_this_item_x',
				['names' => implode(', ', $invalidCoOwners)]
			);
		}
		
		$this->setCoOwners = $this->em()->getBasicCollection($setCoOwners);
		$this->addCoOwners = $this->em()->getBasicCollection($addCoOwners);
	}
	
	/**
	 * @var \XF\Entity\User[]|AbstractCollection
	 */
	public function getAddCoOwners(): AbstractCollection
	{
		return $this->addCoOwners;
	}

	/**
	 * @param AbstractCollection|string $contributors Collection of users or comma-separated usernames
	 */
	public function addContributors($contributors)
	{
		if (is_string($contributors))
		{
			/** @var \XF\Repository\User $userRepo */
			$userRepo = $this->repository('XF:User');
			$usernames = Arr::stringToArray($contributors, '/\s*,\s*/');
			$users = $userRepo->getUsersByNames($usernames, $notFound);

			if ($notFound)
			{
				$this->errors[] = \XF::phrase(
					'following_members_not_found_x',
					['members' => implode(', ', $notFound)]
				);
			}
		}
		else if ($contributors instanceof AbstractCollection)
		{
			$users = $contributors->filter(function($member)
			{
				return $member instanceof User;
			});
		}
		else
		{
			throw new \InvalidArgumentException(
				'Contributors must be a collection of users or a string of comma-separated usernames'
			);
		}

		$addContributors = [];
		$invalidContributors = [];
		foreach ($users AS $userId => $user)
		{
			if (
				$userId == $this->item->user_id ||
				$this->item->Contributors[$userId]
			)
			{
				continue;
			}

			if (!$this->item->canContributorBeAdded($user))
			{
				$invalidContributors[$userId] = $user->username;
				continue;
			}

			$addContributors[$userId] = $user;
		}

		if ($invalidContributors)
		{
			$this->errors[] = \XF::phrase(
				'xa_sc_you_may_not_add_following_contributors_to_this_item_x',
				['names' => implode(', ', $invalidContributors)]
			);
		}

		$this->addContributors = $this->em()->getBasicCollection($addContributors);
	}

	/**
	 * @var \XF\Entity\User[]|AbstractCollection
	 */
	public function getAddContributors(): AbstractCollection
	{
		return $this->addContributors;
	}
	

	//////////////// BETA Join Contributor Team feature /////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @param \XF\Entity\User[] $user
	 */
	public function addSelfJoinContributor(\XF\Entity\User $user, bool $sendJoinNotification = true)
	{
		$this->addSelfJoinContributor = $user;
	
		$this->sendJoinNotification = $sendJoinNotification;
	}
	
	/**
	 * @var \XF\Entity\User[]
	 */
	public function getAddSelfJoinContributor()
	{
		return $this->addSelfJoinContributor;
	}
	
	public function setSendJoinNotification(bool $send)
	{
		$this->sendJoinNotification = $send;
	}
	
	public function getSendJoinNotification(): bool
	{
		return $this->sendJoinNotification;
	}
	
	//////////////////// END - BETA Join Contributor Team feature //////////////////////////////////////////////////////////////////////////////////////////////////////
		
	/**
	 * @param AbstractCollection|int[] $contributors Collection of users or array of user IDs
	 */
	public function removeContributors($contributors)
	{
		if (is_array($contributors))
		{
			$userIds = array_intersect(
				$contributors,
				$this->item->Contributors->keys()
			);
			$users = $this->em()->findByIds('XF:User', $userIds);
		}
		else if ($contributors instanceof AbstractCollection)
		{
			$users = $contributors->filter(function($member)
			{
				return $member instanceof User;
			});
		}
		else
		{
			throw new \InvalidArgumentException(
				'Contributors must be a collection of users or an array of user IDs'
			);
		}

		$this->removeContributors = $users;
	}

	public function leaveContributorsTeam(\XF\Entity\User $user, bool $sendLeaveNotification = true)
	{
		if ($this->removeContributors->count())
		{
			throw new \LogicException("Only one of leaveContributorsTeam and removeContributors should be called");
		}

		$this->removeContributors = $this->em()->getBasicCollection([$user]);

		$this->sendLeaveNotification = $sendLeaveNotification;
	}

	/**
	 * @var \XF\Entity\User[]|AbstractCollection
	 */
	public function getRemoveContributors(): AbstractCollection
	{
		return $this->removeContributors;
	}

	public function setSendLeaveNotification(bool $send)
	{
		$this->sendLeaveNotification = $send;
	}

	public function getSendLeaveNotification(): bool
	{
		return $this->sendLeaveNotification;
	}

	protected function _validate(): array
	{
		if ($this->addContributors && $this->addContributors->count())
		{
			$maxAllowed = $this->item->getMaxContributorCount();
			$newCount = $this->getNewContributorCount();
			if ($newCount > $maxAllowed)
			{
				$this->errors[] = \XF::phrase(
					'xa_sc_you_may_only_add_x_contributors_co_owners_to_this_item',
					['count' => $maxAllowed]
				);
			}
		}
		
		if ($this->addCoOwners && $this->addCoOwners->count())
		{
			$maxAllowed = $this->item->getMaxContributorCount();
			$newCount = $this->getNewContributorCount();
			if ($newCount > $maxAllowed)
			{
				$this->errors[] = \XF::phrase(
					'xa_sc_you_may_only_add_x_contributors_co_owners_to_this_item',
					['count' => $maxAllowed]
				);
			}
		}

		return $this->errors;
	}

	protected function _save(): bool
	{
		$this->db()->beginTransaction();

		foreach ($this->addCoOwners AS $addCoOwner)
		{
			$coOwner = $this->em()->create('XenAddons\Showcase:ItemContributor');
			$coOwner->item_id = $this->item->item_id;
			$coOwner->user_id = $addCoOwner->user_id;
			$coOwner->is_co_owner = true;
			$coOwner->save(true, false);
		}
		
		foreach ($this->setCoOwners AS $setCoOwner)
		{
			$coOwner = $this->item->Contributors[$setCoOwner->user_id];
			if (!$coOwner)
			{
				continue;
			}
			
			$coOwner->is_co_owner = true;
			$coOwner->save(true, false);
		}
		
		foreach ($this->addContributors AS $addContributor)
		{
			$contributor = $this->em()->create('XenAddons\Showcase:ItemContributor');
			$contributor->item_id = $this->item->item_id;
			$contributor->user_id = $addContributor->user_id;
			$contributor->save(true, false);
		}
		
		if ($addSelfJoinContributor = $this->addSelfJoinContributor)
		{
			$contributor = $this->em()->create('XenAddons\Showcase:ItemContributor');
			$contributor->item_id = $this->item->item_id;
			$contributor->user_id = $addSelfJoinContributor->user_id;
			$contributor->save(true, false);
		}

		foreach ($this->removeContributors AS $removeContributor)
		{
			$contributor = $this->item->Contributors[$removeContributor->user_id];
			if (!$contributor)
			{
				continue;
			}

			$contributor->delete(true, false);
		}

		$this->db()->commit();

		if ($this->autoSendNotifications)
		{
			$this->sendNotifications();
		}

		return true;
	}

	public function sendNotifications()
	{
		foreach ($this->addCoOwners AS $addCoOwner)
		{
			/** @var \XF\Repository\UserAlert $alertRepo */
			$alertRepo = $this->repository('XF:UserAlert');
			$alertRepo->alert(
				$addCoOwner,
				$this->item->user_id,
				$this->item->username,
				'sc_item',
				$this->item->item_id,
				'co_owner_add'
			);
		}
		
		foreach ($this->setCoOwners AS $setCoOwner)
		{
			/** @var \XF\Repository\UserAlert $alertRepo */
			$alertRepo = $this->repository('XF:UserAlert');
			$alertRepo->alert(
				$setCoOwner,
				$this->item->user_id,
				$this->item->username,
				'sc_item',
				$this->item->item_id,
				'co_owner_add'
			);
		}		
		
		foreach ($this->addContributors AS $addContributor)
		{
			/** @var \XF\Repository\UserAlert $alertRepo */
			$alertRepo = $this->repository('XF:UserAlert');
			$alertRepo->alert(
				$addContributor,
				$this->item->user_id,
				$this->item->username,
				'sc_item',
				$this->item->item_id,
				'contributor_add'
			);
		}

		if ($this->sendJoinNotification && $this->item->User && $this->addSelfJoinContributor)
		{
			$addSelfJoinContributor = $this->addSelfJoinContributor;
		
			/** @var \XF\Repository\UserAlert $alertRepo */
			$alertRepo = $this->repository('XF:UserAlert');
			$alertRepo->alert(
				$this->item->User,
				$addSelfJoinContributor->user_id,
				$addSelfJoinContributor->username,
				'sc_item',
				$this->item->item_id,
				'contributor_join'
			);
		}
		
		if ($this->sendLeaveNotification && $this->item->User)
		{
			foreach ($this->removeContributors AS $removeContributor)
			{
				/** @var \XF\Repository\UserAlert $alertRepo */
				$alertRepo = $this->repository('XF:UserAlert');
				$alertRepo->alert(
					$this->item->User,
					$removeContributor->user_id,
					$removeContributor->username,
					'sc_item',
					$this->item->item_id,
					'contributor_leave'
				);
			}
		}
	}
}