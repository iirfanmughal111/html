<?php

namespace DBTech\eCommerce\Service\Order;

use DBTech\eCommerce\Entity\Order;
use DBTech\eCommerce\Pdf;
use mikehaertl\wkhtmlto\Pdf as HtmlPdf;
use XF\Util\File;

/**
 * Class Invoice
 *
 * @package DBTech\eCommerce\Service\Order
 */
class Invoice extends \XF\Service\AbstractService
{
	/** @var \XF\Entity\User */
	protected $user;
	
	/** @var \DBTech\eCommerce\Entity\Order */
	protected $order;


	/**
	 * Invoice constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Order $order
	 * @param \XF\Entity\User $user
	 */
	public function __construct(\XF\App $app, Order $order, \XF\Entity\User $user)
	{
		parent::__construct($app);
		
		$this->user = $user;
		$this->setOrder($order);
	}
	
	/**
	 * @return \XF\Entity\User
	 */
	public function getUser(): \XF\Entity\User
	{
		return $this->user;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Order $order
	 *
	 * @return $this
	 */
	protected function setOrder(Order $order): Invoice
	{
		$this->order = $order;

		return $this;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Order
	 */
	public function getOrder(): Order
	{
		return $this->order;
	}
	
	/**
	 * @param bool $force
	 *
	 * @throws \Exception
	 */
	public function generate(bool $force = false)
	{
		if ($force === true)
		{
			$this->delete();
		}

		if (\XF::fs()->has($this->getInvoiceAbstractPath()))
		{
			return;
		}
		
		if ($this->app->options()->dbtechEcommerceHtmlInvoice['enabled'])
		{
			$this->generateFromHtml();
		}
		else
		{
			$this->generateFromClass();
		}
	}
	
	/**
	 * @throws \Exception
	 */
	protected function generateFromHtml()
	{
		$order = $this->order;
		
		$invoiceTempFile = File::getTempFile();
		
		$pdf = new HtmlPdf();
		$pdf->setOptions([
			'no-outline',         // Make Chrome not complain
			'margin-top'    => 0,
			'margin-right'  => 0,
			'margin-bottom' => 0,
			'margin-left'   => 0,
			
			'binary' => $this->app->options()->dbtechEcommerceHtmlInvoice['path'],
			'disable-smart-shrinking',
			'ignoreWarnings' => true,
			'commandOptions' => [
				'useExec' => true,
			],
		]);
		
		$templater = $this->app->templater();
		//		$templater->setLanguage($language);
		$templater->addDefaultParam('xf', $this->app->getGlobalTemplateData());
		
		if ($this->app->options()->dbtechEcommerceInvoiceOverrideStyle)
		{
			$templater->setStyle($this->app->create('style', $this->app->options()->dbtechEcommerceInvoiceOverrideStyle));
		}
		
		$viewParams = [
			'includeLogo' => false,
			'order' => $order,
			'hasSalesTax' => (($order->Address && $order->Address->sales_tax_id) || $order->getSalesTax() > 0.00),
			'hasDiscount' => $order->getDiscountTotal() > 0.00,
//			'hasDiscount' => true,
			'footerColspan' => 1
		];
		
		if ($viewParams['hasSalesTax'])
		{
			$viewParams['footerColspan']++;
		}
		
		if ($viewParams['hasDiscount'])
		{
			$viewParams['footerColspan']++;
		}
		
		$orderItems = $order->Items->count();
//		$orderItems = 11; // Debug line
		$fitsOnFirstPage = 11;
		if ($orderItems > $fitsOnFirstPage)
		{
			$fitsOnFirstPage = 15;
		}
		$otherTotal = ($orderItems - $fitsOnFirstPage);
		$perPage = 19;
		$maxOtherPages = intval($otherTotal <= 0 ? 0 : ceil($otherTotal / $perPage));
		$invoiceDebugOutput = '';
		
		$pageParams = $viewParams;
		
		// General configuration
		if (!empty($this->app->options()->dbtechEcommerceInvoiceIconPath))
		{
			try
			{
				$pageParams['includeLogo'] = true;
				$pageParams['logo'] = base64_encode($this->app->fs()->read('data://dbtechEcommerce/invoiceIcons/' . $this->app->options()->dbtechEcommerceInvoiceIconPath));
			}
			/** @noinspection PhpRedundantCatchClauseInspection */
			catch (\League\Flysystem\FileNotFoundException $e)
			{
			}
		}
		
		$pageParams['items'] = $order->Items->sliceToPage(1, $fitsOnFirstPage);
		
		if (!$maxOtherPages)
		{
			$pageParams['isLastPage'] = true;
		}
		
		$invoice = $templater->renderTemplate(
			'public:dbtech_ecommerce_invoice',
			$pageParams
		);
		$invoiceDebugOutput .= $invoice;
		
		$pdf->addPage($invoice, [], HtmlPdf::TYPE_HTML);
		
		
		for ($i = 1; $i <= $maxOtherPages; $i++)
		{
			$pageParams = $viewParams;
			
			if ($i == $maxOtherPages)
			{
				$pageParams['isLastPage'] = true;
			}
			
			$pageParams['items'] = $order->Items->slice((($i - 1) * $perPage) + $fitsOnFirstPage, $perPage);
			
			$invoice = $templater->renderTemplate(
				'public:dbtech_ecommerce_invoice',
				$pageParams
			);
			$invoiceDebugOutput .= $invoice;
			
			$pdf->addPage($invoice, [], HtmlPdf::TYPE_HTML);
		}
		
		if (!$pdf->saveAs($invoiceTempFile))
		{
			$error = $pdf->getError();
			
			throw new \XF\PrintableException($error);
		}
		
		File::copyFileToAbstractedPath($invoiceTempFile, 'internal-data://dbtechEcommerce/invoices/INV' . $order->order_id . '.pdf');
		
//		echo $invoiceDebugOutput;
//		die();
	}
	
	/**
	 * @throws \Exception
	 */
	protected function generateFromClass()
	{
		$order = $this->order;
		
		// Needed to generate the invoice properly
		$dateTime = \XF::language()
			->getDateTimeParts($this->order->order_date)
		;
		
		$pdf = new Pdf\Invoice();
		
		// General configuration
		if (!empty($this->app->options()->dbtechEcommerceInvoiceIconPath))
		{
			$filename = \basename($this->app->options()->dbtechEcommerceInvoiceIconPath);
			$destFile = File::getNamedTempFile($filename);
			
			try
			{
				$stream = \XF::app()->fs()->readStream('data://dbtechEcommerce/invoiceIcons/' . $this->app->options()->dbtechEcommerceInvoiceIconPath);
				$tempResource = fopen($destFile, 'w');
				
				stream_copy_to_stream($stream, $tempResource);
				@fclose($tempResource);
				@fclose($stream);
				
				$pdf->setLogo($destFile);
			}
			/** @noinspection PhpRedundantCatchClauseInspection */
			catch (\League\Flysystem\FileNotFoundException $e)
			{
			}
		}
		$pdf->setOrder($order);
		$pdf->setLanguageId($this->user->language_id);
		$pdf->setInvoiceId('INV' . $this->order->order_id);
		$pdf->setDate($dateTime[0]);
		$pdf->setTime($dateTime[1]);
		
		// Include shipping cost if it was charged on this order
		if ($order->hasPhysicalProduct() || $order->getShippingCost() > 0)
		{
			$pdf->setShippingField(true);
		}
		
		// Include sales tax if it was charged on this order
		if (($order->Address && $order->Address->sales_tax_id) || $order->getSalesTax() > 0)
		{
			$pdf->setTaxField(true);
		}
		
		if (
			$order->sale_discounts
			|| $order->coupon_discounts
			|| $order->automatic_discounts
		) {
			$pdf->setDiscountField(true);
		}
		
		// Add the invoice body
		$pdf->Body();
		
		// Output the invoice to the specified file
		$pdf->writePdf('INV' . $this->order->order_id . '.pdf');
	}
	
	/**
	 *
	 */
	public function delete()
	{
		File::deleteFromAbstractedPath($this->getInvoiceAbstractPath());
	}
	
	/**
	 * @return string
	 */
	public function getInvoiceFileName(): string
	{
		return sprintf(
			'INV%d.pdf',
			$this->order->order_id
		);
	}

	/**
	 * @return string
	 */
	public function getInvoiceAbstractPath(): string
	{
		return sprintf(
			'internal-data://dbtechEcommerce/invoices/INV%d.pdf',
			$this->order->order_id
		);
	}
}