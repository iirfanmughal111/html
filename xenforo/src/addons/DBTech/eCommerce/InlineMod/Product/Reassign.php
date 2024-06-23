<?php

namespace DBTech\eCommerce\InlineMod\Product;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

/**
 * Class Reassign
 *
 * @package DBTech\eCommerce\InlineMod\Product
 */
class Reassign extends AbstractAction
{
	/**
	 * @var
	 */
	protected $targetUser;
	/**
	 * @var
	 */
	protected $targetUserId;
	
	/**
	 * @return \XF\Phrase
	 */
	public function getTitle(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_reassign_products...');
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param array $options
	 * @param $error
	 *
	 * @return bool
	 */
	protected function canApplyInternal(AbstractCollection $entities, array $options, &$error): bool
	{
		$result = parent::canApplyInternal($entities, $options, $error);
		
		if ($result && $options['confirmed'] && !$options['target_user_id'])
		{
			$error = \XF::phrase('requested_user_not_found');
			return false;
		}
		
		return $result;
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function canApplyToEntity(Entity $entity, array $options, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\Entity\Product $entity */
		return $entity->canReassign($error);
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 *
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function applyToEntity(Entity $entity, array $options)
	{
		$user = $this->getTargetUser($options['target_user_id']);
		if (!$user)
		{
			throw new \InvalidArgumentException('No target specified');
		}

		/** @var \DBTech\eCommerce\Service\Product\Reassign $reassigner */
		$reassigner = $this->app()->service('DBTech\eCommerce:Product\Reassign', $entity);

		if ($options['alert'])
		{
			$reassigner->setSendAlert(true, $options['alert_reason']);
		}

		$reassigner->reassignTo($user);
	}
	
	/**
	 * @return array
	 */
	public function getBaseOptions(): array
	{
		return [
			'target_user_id' => 0,
			'confirmed' => false,
			'alert' => false,
			'alert_reason' => ''
		];
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param \XF\Mvc\Controller $controller
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 */
	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'products' => $entities,
			'total' => count($entities)
		];
		return $controller->view('DBTech\eCommerce:Public:InlineMod\Product\Reassign', 'inline_mod_dbtech_ecommerce_product_reassign', $viewParams);
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param Request $request
	 *
	 * @return array
	 */
	public function getFormOptions(AbstractCollection $entities, Request $request): array
	{
		$username = $request->filter('username', 'str');
		$user = $this->app()->em()->findOne('XF:User', ['username' => $username]);

		return [
			'target_user_id' => $user ? $user->user_id : 0,
			'confirmed' => true,
			'alert' => $request->filter('alert', 'bool'),
			'alert_reason' => $request->filter('alert_reason', 'str')
		];
	}
	
	/**
	 * @param int $userId
	 *
	 * @return null|\XF\Entity\User
	 * @throws \InvalidArgumentException
	 */
	protected function getTargetUser(int $userId): ?\XF\Entity\User
	{
		if ($this->targetUserId && $this->targetUserId == $userId)
		{
			return $this->targetUser;
		}
		if (!$userId)
		{
			return null;
		}

		/** @var \XF\Entity\User $user */
		$user = $this->app()->em()->find('XF:User', $userId);
		if (!$user)
		{
			throw new \InvalidArgumentException("Invalid target user ($userId)");
		}

		$this->targetUserId = $userId;
		$this->targetUser = $user;

		return $this->targetUser;
	}
}