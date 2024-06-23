<?php

namespace ThemeHouse\PostComments\XF\Service\Post;


class Notifier extends XFCP_Notifier
{
	/**
	 * @var \ThemeHouse\PostComments\XF\Entity\Post
	 */
	protected $post;

	protected function getNotifiers()
	{
		$notifiers = parent::getNotifiers();

		if ($this->post->ParentPost)
		{
			$notifiers['thPostCommentsThreadWatch'] = $this->app->notifier(
				'ThemeHouse\PostComments:Post\ThreadWatch',
				$this->post,
				$this->actionType
			);
		}

		return $notifiers;
	}
}