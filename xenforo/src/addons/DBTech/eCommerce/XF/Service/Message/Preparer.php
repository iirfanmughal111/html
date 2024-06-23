<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Service\Message;

/**
 * Class Preparer
 *
 * @package DBTech\eCommerce\XF\Service\Message
 */
class Preparer extends XFCP_Preparer
{
	/** @var array  */
	protected $productEmbeds = [];
	
	/**
	 * @param $message
	 * @param bool $checkValidity
	 *
	 * @return mixed
	 */
	public function prepare($message, $checkValidity = true)
	{
		$message = parent::prepare($message, $checkValidity);

		/** @var \DBTech\eCommerce\XF\BbCode\ProcessorAction\AnalyzeUsage $usage */
		$usage = $this->bbCodeProcessor->getAnalyzer('usage');
		$this->productEmbeds = $usage->getProductEmbeds();

		return $message;
	}
	
	/**
	 * @return array
	 */
	public function getEmbeddedProductItems()
	{
		return $this->productEmbeds;
	}
	
	/**
	 * @return array
	 */
	public function getEmbedMetadata()
	{
		$metadata = parent::getEmbedMetadata();
		if ($this->productEmbeds)
		{
			$metadata['productEmbeds'] = $this->productEmbeds;
		}

		return $metadata;
	}
}