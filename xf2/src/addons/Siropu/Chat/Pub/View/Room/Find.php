<?php

namespace Siropu\Chat\Pub\View\Room;

class Find extends \XF\Mvc\View
{
	public function renderJson()
	{
		$results = [];

		foreach ($this->params['rooms'] AS $room)
		{
			$results[] = [
				'id'   => $room->room_name,
				'text' => $room->room_name,
				'q'    => $this->params['q']
			];
		}

		return [
			'results' => $results,
			'q'       => $this->params['q']
		];
	}
}
