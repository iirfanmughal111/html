<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\BbCode\ProcessorAction;

use XF\BbCode\Processor;
use XF\BbCode\ProcessorAction\AnalyzerHooks;

/**
 * Class AnalyzeUsage
 *
 * @package DBTech\eCommerce\XF\BbCode\ProcessorAction
 */
class AnalyzeUsage extends XFCP_AnalyzeUsage
{
	/** @var array  */
	protected $productEmbeds = [];
	
	/**
	 * @param AnalyzerHooks $hooks
	 */
	public function addAnalysisHooks(AnalyzerHooks $hooks)
	{
		parent::addAnalysisHooks($hooks);

		$hooks->addTagHook('product', 'analyzeProductTag');
	}
	
	/**
	 * @return array
	 */
	public function getProductEmbeds()
	{
		return $this->productEmbeds;
	}
	
	/**
	 * @param array $tag
	 * @param array $options
	 * @param $finalOutput
	 * @param Processor $processor
	 */
	public function analyzeProductTag(array $tag, array $options, $finalOutput, Processor $processor)
	{
		if (!$finalOutput || !$tag['option'])
		{
			// was stripped
			return;
		}

		$parts = explode(',', $tag['option']);
		foreach ($parts AS &$part)
		{
			$part = trim($part);
			$part = str_replace(' ', '', $part);
		}

		$type = strtolower(array_shift($parts));
		$id = array_shift($parts);
		if ($type && $id)
		{
			$this->productEmbeds[$type][$id] = $id;
		}
	}
}