<?php

namespace Siropu\Chat\Widget;

class Rooms extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
		'search' => 10
	];

	public function render()
	{
		$options  = $this->options;
		$userRepo = $this->repository('Siropu\Chat:User');

		$users = $userRepo->findActiveUsers()
               ->fetch()
               ->filter(function(\XF\Entity\User $user)
               {
                    return ($user->isVisibleSiropuChat());
               });

          $rooms = $this->repository('Siropu\Chat:Room')
               ->findRoomsForList()
               ->fetch()
               ->filter(function(\Siropu\Chat\Entity\Room $room)
               {
                    return ($room->canJoin(null, false) || \XF::visitor()->canViewSiropuChatPrivateRooms());
               });

		return $this->renderer('siropu_chat_widget_rooms', [
			'rooms'   => $rooms,
			'users'   => $userRepo->groupUsersByRoom($users),
			'title'   => $this->getTitle(),
			'options' => $options
		]);
	}
	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'search'  => 'uint'
		]);

		return true;
	}
}
