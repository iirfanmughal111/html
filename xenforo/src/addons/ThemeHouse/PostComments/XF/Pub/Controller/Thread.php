<?php

namespace ThemeHouse\PostComments\XF\Pub\Controller;

use ThemeHouse\PostComments\XF\Entity\Post as ExtendedPostEntity;
use ThemeHouse\PostComments\XF\Entity\Thread as ExtendedThreadEntity;
use XF\Entity\Post as PostEntity;
use XF\Entity\Thread as ThreadEntity;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View as ViewReply;

/**
 * @version 1.0.3
 */
class Thread extends XFCP_Thread
{
	/**
	 * @param ParameterBag $params
	 * @return ViewReply
	 */
	public function actionIndex(ParameterBag $params)
	{
		$view = parent::actionIndex($params);

		if ($view instanceof ViewReply && $view->getParam('posts'))
		{
			$thread = $view->getParam('thread');
			$view->setParam('total', $thread->thpostcomments_root_reply_count + 1);

			$posts = $view->getParam('posts');
			/** @var \ThemeHouse\PostComments\Repository\Post $postRepo */
			$postRepo = \XF::repository('ThemeHouse\PostComments:Post');
			$nestedPosts = $postRepo->createPostTree($posts);
			$view->setParam('nestedPosts', $nestedPosts);
		}

		return $view;
	}

	/**
	 * @param ParameterBag $params
	 * @return mixed
	 */
	public function actionThreadVotes(ParameterBag $params)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$view = parent::actionThreadVotes($params);

		if ($view instanceof ViewReply && $view->getParam('posts'))
		{
			$thread = $view->getParam('thread');
			$view->setParam('total', $thread->thpostcomments_root_reply_count + 1);

			$posts = $view->getParam('posts');
			/** @var \ThemeHouse\PostComments\Repository\Post $postRepo */
			$postRepo = \XF::repository('ThemeHouse\PostComments:Post');
			$nestedPosts = $postRepo->createPostTree($posts);
			$view->setParam('nestedPosts', $nestedPosts);
		}

		return $view;
	}

	/**
	 * This is for XenForo 2.1+ until it was latter dropped for
	 * @param \XF\Entity\Thread $thread
	 * @param $lastDate
	 * @return ViewReply
	 * @see getNewPostsReplyInternal()
	 *
	 */
	protected function getNewPostsReply(\XF\Entity\Thread $thread, $lastDate)
	{
		$view = parent::getNewPostsReply($thread, $lastDate);

		if ($view instanceof ViewReply && $view->getParam('posts'))
		{
			/** @var \XF\Mvc\Entity\ArrayCollection $posts */
			$posts = $view->getParam('posts');

			$view->setParam('posts', [$thread->LastPost]);
			$view->setParam('firstUnshownPost', $posts->pop()->last());
		}

		return $view;
	}

	/**
	 * @param ThreadEntity|ExtendedThreadEntity $thread
	 * @param AbstractCollection $posts
	 * @param PostEntity|ExtendedPostEntity|null $firstUnshownPost
	 *
	 * @return ViewReply
	 * @since 1.0.3
	 *
	 * This was introduced in XenForo 2.2+
	 *
	 */
	protected function getNewPostsReplyInternal(
		ThreadEntity $thread,
		AbstractCollection $posts,
		PostEntity $firstUnshownPost = null
	)
	{
		$reply = parent::getNewPostsReplyInternal($thread, $posts, $firstUnshownPost);

		if ($reply instanceof ViewReply)
		{
			$reply->setParam('posts', [$thread->LastPost]);
			$reply->setParam('firstUnshownPost', $posts->pop()->last());
		}

		return $reply;
	}
}
