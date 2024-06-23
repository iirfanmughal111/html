<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Service\User;

/**
 * Class RegistrationComplete
 *
 * @package DBTech\Credits\XF\Service\User
 */
class RegistrationComplete extends XFCP_RegistrationComplete
{
	/**
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function triggerCompletionActions()
	{
		parent::triggerCompletionActions();

		$user = $this->user;

		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');

		$eventTriggerRepo->getHandler('registration')
			->apply($user->user_id, [
				'source_user_id' => $user->user_id,
				'content_type'   => 'user',
				'content_id'     => $user->user_id
			], $user)
		;
	}
}