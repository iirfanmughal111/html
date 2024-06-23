<?php

namespace DBTech\eCommerce\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

/**
 * Class DownloadVersion
 * @package DBTech\eCommerce\Attachment
 */
class DownloadVersion extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getContainerWith(): array
	{
		$visitor = \XF::visitor();
		
		return [
			'Download',
			'Download.Product',
			'Download.Product.Permissions|' . $visitor->permission_combination_id,
			'Download.Product.Category',
			'Download.Product.Category.Permissions|' . $visitor->permission_combination_id
		];
	}
	
	/**
	 * @param Attachment $attachment
	 * @param Entity $container
	 * @param null $error
	 * @return bool
	 */
	public function canView(Attachment $attachment, Entity $container, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $container */
		return $container->Download->Product->canEdit();
	}

	/**
	 * @param array $context
	 * @param null $error
	 * @return bool
	 */
	public function canManageAttachments(array $context, &$error = null): bool
	{
		return true;
	}

	/**
	 * @param Attachment $attachment
	 * @param Entity|null $container
	 * @throws \Exception
	 */
	public function onAttachmentDelete(Attachment $attachment, ?Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

		/** @var \DBTech\eCommerce\Entity\DownloadVersion $container */
		$container->attach_count--;
		$container->save();
	}

	/**
	 * @param array $context
	 * @return mixed
	 */
	public function getConstraints(array $context)
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = \XF::repository('XF:Attachment');

		return $attachRepo->getDefaultAttachmentConstraints();
	}

	/**
	 * @param array $context
	 * @return int|null
	 */
	public function getContainerIdFromContext(array $context): ?int
	{
		return isset($context['download_version_id']) ? (int)$context['download_version_id'] : null;
	}

	/**
	 * @param Entity $container
	 * @param array $extraParams
	 * @return mixed|string
	 */
	public function getContainerLink(Entity $container, array $extraParams = []): string
	{
		return '';
	}

	/**
	 * @param Entity|null $entity
	 * @param array $extraContext
	 * @return array
	 */
	public function getContext(Entity $entity = null, array $extraContext = []): array
	{
		if ($entity instanceof \DBTech\eCommerce\Entity\DownloadVersion)
		{
			$extraContext['download_version_id'] = $entity->download_version_id;
		}

		return $extraContext;
	}
}