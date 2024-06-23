<?php

namespace FS\HideUsernames\XF\Template;

use XF\Mvc\Entity\Entity;

class Templater extends XFCP_Templater
{


	
	public function fnReactions($templater, &$escape, $content, $link, array $linkParams = [])
	{
		if (!($content instanceof Entity)) {
			trigger_error("Content for reactions is not an entity", E_USER_WARNING);
			return '';
		}

		$escape = false;

		$counts = $content->reactions;
		$users = $content->reaction_users;


		$reactionContent = null;

		$userId = \XF::visitor()->user_id;
		if ($userId) {
			$reactionContent = $content->getReactionContent();
		}

		$reacted = $reactionContent ? true : false;

		$reactionDefault = $this->app->container('reactionDefault');

		if (is_array($counts)) {
			if (!$counts) {
				return '';
			}
		} else {
			// legacy format, likes only, change format pointing at default reaction
			$count = intval($counts);
			if ($count <= 0) {
				return '';
			}
			$counts = [
				$reactionDefault['reaction_id'] => $count
			];
		}

		if (is_array($link)) {
			$tempLink = $link;
			$link = $tempLink[0];
			if (!$linkParams) {
				$linkParams = $tempLink[1];
			}
		}

		$total = array_sum($counts);
		$reactionIds = array_slice(array_keys($counts), 0, 3); // TODO: Make top x configurable?

		if (!$users || !is_array($users)) {
			$phrase = ($total > 1 ? 'reactions.x_people' : 'reactions.1_person');
			return $this->renderTemplate('public:reaction_list_row', [
				'content' => $content,
				'link' => $link,
				'linkParams' => $linkParams,
				'reactionIds' => $reactionIds,
				'reactions' => \XF::phrase($phrase, ['reactions' => $this->language->numberFormat($total)])
			]);
		}

		$userCount = count($users);

		if ($userCount < 5 && $total > $userCount) // indicates some users are deleted
		{
			for ($i = 0; $i < $total; $i++) {
				if (empty($users[$i])) {
					$users[$i] = [
						'user_id' => 0,
						'username' => \XF::phrase('reactions.deleted_user')
					];
				}
			}
		}

		if ($reacted) {
			$visitorId = \XF::visitor()->user_id;
			foreach ($users as $key => $user) {
				if ($user['user_id'] == $visitorId) {
					unset($users[$key]);
					break;
				}
			}

			$users = array_values($users);

			if (count($users) == 3) {
				unset($users[2]);
			}
		}

		$user1 = $user2 = $user3 = '';

		if (isset($users[0])) {

			$user1 = $this->replaceUserName($users[0]);

			if (isset($users[1])) {

				$user2 = $this->replaceUserName($users[1]);

				if (isset($users[2])) {
					$user3 = $this->replaceUserName($users[2]);
				}
			}
		}

		switch ($total) {
			case 1:
				$phrase = ($reacted ? 'reactions.you' : 'reactions.user1');
				break;
			case 2:
				$phrase = ($reacted ? 'reactions.you_and_user1' : 'reactions.user1_and_user2');
				break;
			case 3:
				$phrase = ($reacted ? 'reactions.you_user1_and_user2' : 'reactions.user1_user2_and_user3');
				break;
			case 4:
				$phrase = ($reacted ? 'reactions.you_user1_user2_and_1_other' : 'reactions.user1_user2_user3_and_1_other');
				break;
			default:
				$phrase = ($reacted ? 'reactions.you_user1_user2_and_x_others' : 'reactions.user1_user2_user3_and_x_others');
				break;
		}

		$params = [
			'user1' => $user1,
			'user2' => $user2,
			'user3' => $user3,
			'others' => $this->language->numberFormat($total - 3)
		];

		return $this->renderTemplate('public:reaction_list_row', [
			'content' => $content,
			'link' => $link,
			'linkParams' => $linkParams,
			'reactionIds' => $reactionIds,
			'reactions' => \XF::phrase($phrase, $params)
		]);
	}

	public function replaceUserName($user)
	{
		$userId = $user['user_id'];

		if (\XF::visitor()->user_id == $userId) {
			return $this->preEscaped('<bdi>' . \XF::escapeString($user['username']) . '</bdi>', 'html');
		} else {
			$userName = \xf::app()->em()->find('XF:User', $userId);

			return $this->preEscaped('<bdi>' . \XF::escapeString($userName['random_name']) . '</bdi>', 'html');
		}
	}
}
