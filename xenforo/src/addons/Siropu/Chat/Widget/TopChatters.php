<?php

namespace Siropu\Chat\Widget;

class TopChatters extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
		'limit'  => 5,
		'avatar' => true,
		'grid'   => false
	];

	public function render()
	{
		$options = $this->options;

		return $this->renderer('siropu_chat_widget_top_chatters', [
			'users'   => $this->repository('Siropu\Chat:User')->findTopUsers($options['limit'])->fetch(),
			'title'   => $this->getTitle(),
			'options' => $options
		]);
	}
	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit'  => 'uint',
			'avatar' => 'bool',
			'grid'   => 'bool',
		]);

		return true;
	}
}
