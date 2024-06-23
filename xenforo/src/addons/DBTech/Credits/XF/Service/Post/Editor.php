<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Service\Post;

class Editor extends XFCP_Editor
{
	/**
	 * @return array
	 */
	protected function _validate()
	{
		$errors = parent::_validate();

		$creditsErrors = $this->postPreparer->validateDragonByteCreditsEventsBeforeUpdate();
		$errors = array_merge($errors, $creditsErrors);

		return $errors;
	}
}