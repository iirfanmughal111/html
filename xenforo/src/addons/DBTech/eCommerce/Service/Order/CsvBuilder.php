<?php

namespace DBTech\eCommerce\Service\Order;

use XF\Mvc\Entity\ArrayCollection;

/**
 * Class CsvBuilder
 *
 * @package DBTech\eCommerce\Service\Order
 */
class CsvBuilder extends \XF\Service\AbstractService
{
	/** @var \XF\Mvc\Entity\ArrayCollection */
	protected $entries;

	/** @var string */
	protected $filePath;


	/**
	 * CsvBuilder constructor.
	 *
	 * @param \XF\App $app
	 * @param \XF\Mvc\Entity\ArrayCollection $entries
	 */
	public function __construct(\XF\App $app, ArrayCollection $entries)
	{
		parent::__construct($app);

		$this->entries = $entries;
		$this->filePath = \XF\Util\File::getTempFile();
	}

	/**
	 * @return string
	 */
	public function getFilePath(): string
	{
		return $this->filePath;
	}

	/**
	 *
	 * @throws \Exception
	 */
	public function build(): void
	{
		$language = \XF::language();

		$output = '';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_order_id') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_order_date') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_order_time') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_paid_date') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_paid_time') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_reversed_date') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_reversed_time') . '",';
		$output .= '"' . \XF::phrase('user_name') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_country') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_currency') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_sub_total') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_sales_tax') . '",';
		$output .= '"' . \XF::phrase('dbtech_ecommerce_order_total') . '",';
		$output .= '"' . \XF::phrase('payment_profile') . '",';
		$output .= '"' . \XF::phrase('transaction_id') . '"';

		/** @var \DBTech\eCommerce\Entity\Order $entry */
		foreach ($this->entries as $entry)
		{
			$output .= "\n";
			$output .= '"#' . $entry->order_id . '",';
			$output .= '"' . $language->date($entry->order_date, 'Y-m-d') . '",';
			$output .= '"' . $language->time($entry->order_date, 'H:i') . '",';
			$output .= '"' . ($entry->completed_date ? $language->date($entry->completed_date, 'Y-m-d') : \XF::phrase('n_a')) . '",';
			$output .= '"' . ($entry->completed_date ? $language->time($entry->completed_date, 'H:i') : \XF::phrase('n_a')) . '",';
			$output .= '"' . ($entry->reversed_date ? $language->date($entry->reversed_date, 'Y-m-d') : \XF::phrase('n_a')) . '",';
			$output .= '"' . ($entry->reversed_date ? $language->time($entry->reversed_date, 'H:i') : \XF::phrase('n_a')) . '",';
			$output .= '"' . ($entry->User ? $entry->User->username : \XF::phrase('unknown_user')) . '",';
			$output .= '"' . (($entry->Address && $entry->Address->Country) ? $entry->Address->Country->name : \XF::phrase('dbtech_ecommerce_unknown_address')) . '",';
			$output .= '"' . \strtoupper($entry->currency) . '",';
			$output .= '"' . $entry->sub_total . '",';
			$output .= '"' . $entry->sales_tax . '",';
			$output .= '"' . $entry->order_total . '",';
			$output .= '"' . (($entry->PurchaseRequest && $entry->PurchaseRequest->PaymentProfile) ? $entry->PurchaseRequest->PaymentProfile->title : \XF::phrase('n_a')) . '",';
			$output .= '"' . ($entry->SuccessfulPayment ? $entry->SuccessfulPayment->transaction_id : \XF::phrase('n_a')) . '"';
		}

		\XF\Util\File::writeFile($this->filePath, $output, false);
	}
}