<?php

namespace Siropu\Chat\Widget;

class Users extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
		'search' => 10,
		'avatar' => true,
		'grid'   => false
	];

	public function render()
	{
		return $this->renderer('siropu_chat_widget_users', [
			'users'   => $this->repository('Siropu\Chat:User')->findActiveUsers()->fetch(),
			'title'   => $this->getTitle(),
			'options' => $this->options
		]);
	}
	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'search' => 'uint',
			'avatar' => 'bool',
			'grid'   => 'bool'
		]);

		return true;
	}
}
