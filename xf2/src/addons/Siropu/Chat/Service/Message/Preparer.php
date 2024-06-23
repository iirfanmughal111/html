<?php

namespace Siropu\Chat\Service\Message;

class Preparer extends \XF\Service\AbstractService
{
	protected $message;
	protected $bbCodeProcessor;
	protected $errors = [];
	protected $isValid = false;
     protected $disabledBBcodes = [];

	public function __construct(\XF\App $app)
	{
		parent::__construct($app);

          $this->disabledBBcodes = $this->getChatData()->getDisabledBbCodes();
	}
	public function prepare($message)
	{
		$this->message = $this->processMessage($message);

		if (empty($this->message))
          {
               $this->errors[] = \XF::phraseDeferred('please_enter_valid_message');
          }

		if (($maxLength = \XF::options()->siropuChatMaxMessageLength) && utf8_strlen($this->message) > $maxLength)
		{
			$this->errors[] = \XF::phraseDeferred('please_enter_message_with_no_more_than_x_characters', ['count' => $maxLength]);
		}

		if (empty($this->errors))
		{
			$this->isValid = true;
		}
	}
	public function isValid()
	{
		return $this->isValid;
	}
	public function getMessage()
	{
		return $this->message;
	}
	public function getErrors()
	{
		return $this->errors;
	}
	public function getUserMentions()
	{
		return $this->bbCodeProcessor->getFilterer('mentions')->getMentionedUsers();
	}
	protected function processMessage($message)
	{
		$this->bbCodeProcessor = $this->getBbCodeProcessor();
		$bbCodeContainer       = $this->app->bbCode();

		return $this->bbCodeProcessor->render($message, $bbCodeContainer->parser(), $bbCodeContainer->rules('siropu_chat'));
	}
	protected function getBbCodeProcessor()
	{
		$bbCodeContainer = $this->app->bbCode();
		$bbCodeProcessor = $bbCodeContainer->processor();

          if (!in_array('url', $this->disabledBBcodes))
          {
               $bbCodeProcessor->addProcessorAction('autolink', $bbCodeContainer->processorAction('autolink'));
          }

		$bbCodeProcessor->addProcessorAction('mentions', $bbCodeContainer->processorAction('mentions'));

		$limit = $bbCodeContainer->processorAction('limit');
		$bbCodeProcessor->addProcessorAction('limit', $limit->disableTag($this->disabledBBcodes));

		return $bbCodeProcessor;
	}
	public function getChatData()
	{
		$class = $this->app->extendClass('Siropu\Chat\Data');
		return new $class();
	}
}
