<?php


namespace FS\BitCoinPayment\Pub\View;
use XF\Mvc\View;

class VideoStatus extends View
{
	public function renderJson()
	{
		var_dump('echo shico');exit;
		$results = [];
	

		return [
			'results' => $results,
			// 'q' => $this->params['q']
		];
	}
}