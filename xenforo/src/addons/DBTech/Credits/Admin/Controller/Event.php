<?php

namespace DBTech\Credits\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class Event
 * @package DBTech\Credits\Admin\Controller
 */
class Event extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechCredits');
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$currencies = $this->getCurrencyRepo()->getCurrencyTitlePairs();
		if (!count($currencies))
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_credits_please_create_at_least_one_currency_before_continuing')));
		}
		
		$currencyId = $this->filter('currency_id', 'uint');
		if ($currencyId)
		{
			$currencyId = isset($currencies[$currencyId]) ? $currencyId : 0;
		}
		
		if (!$currencyId)
		{
			$currencyIds = array_keys($currencies);
			$currencyId = array_shift($currencyIds);
		}
		
		$events = $this->getEventTriggerRepo()
			->findEventsForList()
			->where('currency_id', $currencyId)
			->fetch()
		;
		
		$viewParams = [
			'events' => $events,
			'currency' => $currencyId,
			'currencies' => $currencies
		];
		return $this->view('DBTech\Credits:Event\Listing', 'dbtech_credits_event_list', $viewParams);
	}

	/**
	 * @param \DBTech\Credits\Entity\Event $event
	 * @return \XF\Mvc\Reply\View
	 */
	protected function eventAddEdit(\DBTech\Credits\Entity\Event $event): \XF\Mvc\Reply\AbstractReply
	{
		$nodeRepo = $this->getNodeRepo();
		$nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());
		
		$viewParams = [
			'event' => $event,
			'nodeTree' => $nodeTree
		];
		return $this->view('DBTech\Credits:Event\Edit', 'dbtech_credits_event_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\Credits\Entity\Event $event */
		$event = $this->assertEventExists($params->event_id);
		return $this->eventAddEdit($event);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \Exception
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		$eventTriggerId = $this->filter('event_trigger_id', 'str');
		
		if ($eventTriggerId)
		{
			$eventTrigger = $this->getEventTriggerRepo()->getHandler($eventTriggerId);
			if ($eventTrigger)
			{
				/** @var \DBTech\Credits\Entity\Event $event */
				$event = $this->em()->create('DBTech\Credits:Event');
				$event->event_trigger_id = $eventTriggerId;
				
				return $this->eventAddEdit($event);
			}
		}
		
		$viewParams = [
			'eventTriggers' => $this->getEventTriggerRepo()->getEventTriggerTitlePairs(true)
		];
		
		return $this->view('DBTech\Credits:Event\AddChooser', 'dbtech_credits_event_add_chooser', $viewParams);
	}
	
	/**
	 * @param \DBTech\Credits\Entity\Event $event
	 *
	 * @return FormAction
	 * @throws \Exception
	 */
	protected function eventSaveProcess(\DBTech\Credits\Entity\Event $event): FormAction
	{
		$form = $this->formAction();
		
		$input = $this->filter([
			'title' => 'str',
			'active' => 'bool',
			'currency_id' => 'uint',
			'event_trigger_id' => 'str',
			
			'charge' => 'bool',
			'moderate' => 'bool',
			'main_add' => 'float',
			'main_sub' => 'float',
			'mult_add' => 'float',
			'mult_sub' => 'float',
			'frequency' => 'uint',
			'maxtime' => 'uint',
			'applymax' => 'uint',
			'applymax_peruser' => 'bool',
			'upperrand' => 'float',
			'multmin' => 'float',
			'multmax' => 'float',
			'minaction' => 'uint',
			'owner' => 'uint',
			'curtarget' => 'uint',
			'alert' => 'bool',
			'display' => 'bool',
			
			'settings' => 'array'
		]);
		
		$usableUserGroups = $this->filter('usable_user_group', 'str');
		if ($usableUserGroups == 'all')
		{
			$input['user_group_ids'] = [-1];
		}
		else
		{
			$input['user_group_ids'] = $this->filter('usable_user_group_ids', 'array-uint');
		}
		
		$usableForums = $this->filter('node_ids', 'array-int');
		if (in_array(-1, $usableForums) || empty($usableForums))
		{
			$input['node_ids'] = [-1];
		}
		else
		{
			$input['node_ids'] = $usableForums;
		}
		
		$eventTrigger = $this->getEventTriggerRepo()->getHandler($input['event_trigger_id']);
		$input['settings'] = $eventTrigger->filterOptions($input['settings']);
		
		$form->basicEntitySave($event, $input);
		
		return $form;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function actionSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		$this->assertPostOnly();
		
		if ($params->event_id)
		{
			/** @var \DBTech\Credits\Entity\Event $event */
			$event = $this->assertEventExists($params->event_id);
		}
		else
		{
			/** @var \DBTech\Credits\Entity\Event $event */
			$event = $this->em()->create('DBTech\Credits:Event');
		}
		
		$this->eventSaveProcess($event)->run();

		return $this->redirect($this->buildLink('dbtech-credits/events') . $this->buildLinkHash($event->event_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$event = $this->assertEventExists($params->event_id);
		
		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$event,
			$this->buildLink('dbtech-credits/events/delete', $event),
			$this->buildLink('dbtech-credits/events/edit', $event),
			$this->buildLink('dbtech-credits/events'),
			$event->title
		);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Message
	 */
	public function actionToggle(): \XF\Mvc\Reply\Message
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('DBTech\Credits:Event', 'active');
	}
	
	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\Credits\Entity\Event|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertEventExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('DBTech\Credits:Event', $id, $with, $phraseKey);
	}
	
	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\Credits\Entity\Currency|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCurrencyExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('DBTech\Credits:Currency', $id, $with, $phraseKey);
	}
	
	/**
	 * @return \DBTech\Credits\Repository\EventTrigger|\XF\Mvc\Entity\Repository
	 */
	protected function getEventTriggerRepo()
	{
		return $this->repository('DBTech\Credits:EventTrigger');
	}
	
	/**
	 * @return \DBTech\Credits\Repository\Currency|\XF\Mvc\Entity\Repository
	 */
	protected function getCurrencyRepo()
	{
		return $this->repository('DBTech\Credits:Currency');
	}
	
	/**
	 * @return \XF\Repository\Node|\XF\Mvc\Entity\Repository
	 */
	protected function getNodeRepo()
	{
		return $this->repository('XF:Node');
	}
}