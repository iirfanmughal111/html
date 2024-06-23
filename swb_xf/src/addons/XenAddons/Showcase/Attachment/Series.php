<?php

namespace XenAddons\Showcase\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class Series extends AbstractHandler
{
	public function canView(Attachment $attachment, Entity $container, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $container */
		if (!$container->canView())
		{
			return false;
		}
		
		return $container->canViewSeriesAttachments();
	}

	public function canManageAttachments(array $context, &$error = null)
	{
		$series = $this->getSeriesFromContext($context);

		return $series && $series->canUploadAndManageSeriesAttachments();
	}

	public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

		/** @var \XenAddons\Showcase\Entity\SeriesItem $container */
		$container->attach_count--;
		$container->save();
	}

	public function getConstraints(array $context)
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = \XF::repository('XF:Attachment');
		
		$constraints = \XF::repository('XenAddons\Showcase:Series')->getSeriesAttachmentConstraints();
		
		$series = $this->getSeriesFromContext($context);
		
		$maxAllowedAttachmentsPerSeries = $series->getMaxAllowedAttachmentsPerSeries();
		
		if ($maxAllowedAttachmentsPerSeries == 0) // in this case, we want 0 to count as unlimited
		{
			$maxAllowedAttachmentsPerSeries = -1;
		}
		$constraints['count'] = $maxAllowedAttachmentsPerSeries;
		
		if ($series && $series->canUploadSeriesVideos())
		{
			$constraints = $attachRepo->applyVideoAttachmentConstraints($constraints);
		}
		
		return $constraints;
	}

	public function getContainerIdFromContext(array $context)
	{
		return isset($context['series_id']) ? intval($context['series_id']) : null;
	}

	public function getContainerLink(Entity $container, array $extraParams = [])
	{
		return \XF::app()->router('public')->buildLink('showcase/series', $container, $extraParams);
	}

	public function getContext(Entity $entity = null, array $extraContext = [])
	{
		if ($entity instanceof \XenAddons\Showcase\Entity\SeriesItem)
		{
			$extraContext['series_id'] = $entity->series_id;
		}
		else
		{
			throw new \InvalidArgumentException("Entity must be a series");
		}

		return $extraContext;
	}
	
	protected function getSeriesFromContext(array $context)
	{
		$em = \XF::em();
		
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!empty($context['series_id']))
		{
			/** @var \XenAddons\Showcase\Entity\SeriesItem $series */
			$series = $em->find('XenAddons\Showcase:SeriesItem', intval($context['series_id']));
			if (!$series || !$series->canView())
			{
				return null;
			}
		}
		else
		{
			$series = $em->create('XenAddons\Showcase:SeriesItem');
			
			if (!$series || (!$visitor->canCreateShowcaseSeries() || !$visitor->hasShowcaseSeriesPermission('uploadSeriesAttach')))
			{
				return null;
			}
		}
		
		return $series;
	}	
}