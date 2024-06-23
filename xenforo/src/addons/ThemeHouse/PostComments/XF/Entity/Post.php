<?php

namespace ThemeHouse\PostComments\XF\Entity;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Structure;

/**
 * Class Post
 * @package ThemeHouse\Comments\XF\Entity
 *
 * @property integer $thpostcomments_parent_post_id
 * @property integer $thpostcomments_root_post_id
 * @property integer $thpostcomments_lft
 * @property integer $thpostcomments_rgt
 * @property integer $thpostcomments_depth
 *
 * @property Post $ParentPost
 * @property Post $RootPost
 * @property ArrayCollection $Comments
 *
 * @property Thread $Thread
 */
class Post extends XFCP_Post
{
	/**
	 * @param $key
	 * @param null $value
	 */
	public function fastUpdate($key, $value = null)
	{
		if (is_array($key))
		{
			$fields = $key;
		}
		else
		{
			$fields = [$key => $value];
		}

		if (isset($fields['position']))
		{
			if ($fields['position'] < 0)
			{
				$fields['position'] = 0;
			}
		}

		parent::fastUpdate($fields);
	}

	/**
	 *
	 */
	public function buildThPostCommentsRootPost()
	{
		$parentPost = $this->ParentPost;
		if (!$parentPost->thpostcomments_root_post_id)
		{
			$this->thpostcomments_root_post_id = $parentPost->post_id;
		}
		else
		{
			$this->thpostcomments_root_post_id = $parentPost->thpostcomments_root_post_id;
		}
	}

	/**
	 * @return bool
	 */
	public function hasNestedComments()
	{
		if (!empty($this->thpostcomments_lft) && !empty($this->thpostcomments_rgt))
		{
			return true;
		}

		return false;
	}

	/**
	 * @param null $error
	 * @return bool
	 */
	public function canComment(&$error = null)
	{
		if ($this->thpostcomments_depth >= \XF::options()->thpostcomments_max_comment_depth)
		{
			$error = \XF::phrase('thpostcomments_max_comment_depth_reached');
			return false;
		}

		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->message_state != 'visible')
		{
			$error = \XF::phrase('requested_message_not_found');
			return false;
		}

		if (!$this->Thread)
		{
			$error = \XF::phrase('requested_message_not_found');
			return false;
		}

		return $visitor->hasNodePermission($this->Thread->node_id, 'thpostcomments_comment');
	}
}
