<?php

namespace XenAddons\Showcase\Service\SeriesPart;

use XenAddons\Showcase\Entity\SeriesPart;

class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XenAddons\Showcase\Entity\SeriesPart
	 */
	protected $seriesPart;
	
	/**
	 * @var \XenAddons\Showcase\Service\Page\Preparer
	 */
	protected $seriesPartPreparer;

	public function __construct(\XF\App $app, SeriesPart $seriesPart)
	{
		parent::__construct($app);

		$this->seriesPart = $this->setUpSeriesPart($seriesPart);
	}

	protected function setUpSeriesPart(SeriesPart $seriesPart)
	{
		$this->seriesPart = $seriesPart;
		
		$this->seriesPartPreparer = $this->service('XenAddons\Showcase:SeriesPart\Preparer', $this->seriesPart);		
		
		return $seriesPart;
	}

	public function getSeriesPart()
	{
		return $this->seriesPart;
	}

	protected function _validate()
	{
		$seriesPart = $this->seriesPart;

		$seriesPart->preSave();
		$errors = $seriesPart->getErrors();

		return $errors;
	}

	protected function _save()
	{
		$seriesPart = $this->seriesPart;
		$visitor = \XF::visitor();
		
		$seriesPart->save(true, false);
		
		$this->seriesPartPreparer->afterUpdate();

		return $seriesPart;
	}
}