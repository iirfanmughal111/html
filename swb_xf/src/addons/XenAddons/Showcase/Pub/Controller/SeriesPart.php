<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class SeriesPart extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
	
		if (!$visitor->canViewShowcaseItems($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		if (!$visitor->canViewShowcaseSeries($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		if ($this->options()->xaScOverrideStyle)
		{
			$this->setViewOption('style_id', $this->options()->xaScOverrideStyle);
		}
	}
	
	public function actionIndex(ParameterBag $params)
	{
		if ($params->series_id)
		{
			return $this->redirect($this->buildLink('showcase/series', $params));
		}
		
		return $this->redirect($this->buildLink('showcase/series'));
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\SeriesPart $seriesPart
	 *
	 * @return \XenAddons\Showcase\Service\SeriesPart\Edit
	 */
	protected function setupSeriesPartEdit(\XenAddons\Showcase\Entity\SeriesPart $seriesPart)
	{
		/** @var \XenAddons\Showcase\Service\SeriesPart\Edit $editor */
		$editor = $this->service('XenAddons\Showcase:SeriesPart\Edit', $seriesPart);

		$seriesPart->edit_date = time();
		
		$basicFields = $this->filter([
			'display_order' => 'int'	
		]);
		$seriesPart->bulkSet($basicFields);
		
		return $editor;
	}	
	
	
	// TODO might remove this and replace with a function to just change the display order
	
	public function actionEdit(ParameterBag $params)
	{
		$seriesPart = $this->assertViewableSeriesPart($params->series_part_id);
		if (!$seriesPart->canEdit($error))
		{
			return $this->noPermission($error);
		}	

		if ($this->isPost())
		{
			$editor = $this->setupSeriesPartEdit($seriesPart);
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$seriesPart = $editor->save();

			return $this->redirect($this->buildLink('showcase/series', $seriesPart));
		}
		else
		{
			$viewParams = [
				'seriesPart' => $seriesPart,
				'series' => $seriesPart->Series,
				'item' => $seriesPart->Item,
			];
			return $this->view('XenAddons\Showcase:SeriesPart\Edit', 'xa_sc_series_part_edit', $viewParams);
		}	
	}
	
	public function actionRemove(ParameterBag $params)
	{
		$seriesPart = $this->assertViewableSeriesPart($params->series_part_id);
		if (!$seriesPart->canRemove($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			if (!$seriesPart->canRemove($error))
			{
				return $this->noPermission($error);
			}
			
			/** @var \XenAddons\Showcase\Service\SeriesPart\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:SeriesPart\Deleter', $seriesPart);
			
			$deleter->delete();
			
			return $this->redirect($this->buildLink('showcase/series', $seriesPart));
		}
		else
		{
			$viewParams = [
				'seriesPart' => $seriesPart,
				'series' => $seriesPart->Series,
				'item' => $seriesPart->Item,
			];
			return $this->view('XenAddons\Showcase:Series\Remove', 'xa_sc_series_part_remove', $viewParams);
		}
	}

	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('xa_sc_managing_series');
	}
}