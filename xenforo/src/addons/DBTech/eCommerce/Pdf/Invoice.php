<?php
namespace DBTech\eCommerce\Pdf;

use DBTech\eCommerce\Entity\Order;


use XF\Util\File;

/**
 * Class Invoice
 *
 * @package DBTech\eCommerce\Pdf
 */
class Invoice extends Pdf
{
	/** @var Order */
	protected $order;
	
	/** @var string */
	protected $logo;
	
	/** @var */
	protected $date;
	
	/** @var */
	protected $time;
	
	/** @var string */
	protected $due;
	
	/** @var array */
	protected $items = [];
	
	/** @var array */
	protected $totals = [];
	
	/** @var bool */
	protected $taxField = false;
	
	/** @var bool */
	protected $shippingField = false;
	
	/** @var bool */
	protected $discountField = false;
	
	/** @var bool */
	protected $productsEnded = false;
	
	/** @var string */
	protected $invoiceId = '';
	
	/** @var int */
	protected $languageId = -1;
	
	/** @var array */
	protected $phrases = [];
	
	/** @var string */
	protected $font = 'DejaVuSans';
	
	/** @var string */
	protected $boldFont = 'DejaVuSans';

	/** @var array */
	protected $color = [
		0, 127, 255
	];
	
	/** @var int */
	protected $angle = 0;
	
	/** @var int */
	protected $columnSpacing   = 0.3;
	
	
	
	/**
	 * PDF constructor.
	 * @param string $str_orientation
	 * @param string $str_units
	 * @param string $str_size
	 */
	public function __construct($str_orientation = 'P', $str_units = 'mm', $str_size = 'A4')
	{
		$fontPath = File::canonicalizePath('internal_data/dbtechEcommerce/fonts/');
		
		File::createDirectory($fontPath, false);
		
		/**
		 *
		 */
		define('FPDF_FONTPATH', $fontPath);
		
		parent::__construct($str_orientation, $str_units, $str_size);
		
		$this->AddFont('DejaVuSans', '', 'DejaVuSans.ttf', true);
		$this->AddFont('DejaVuSans', 'B', 'DejaVuSans-Bold.ttf', true);
	}
	
	
	/**
	 * @param $order
	 */
	public function setOrder($order)
	{
		$this->order = $order;
	}
	
	/**
	 * @param $logo
	 */
	public function setLogo($logo)
	{
		$this->logo = $logo;
	}
	
	/**
	 * @param $languageId
	 */
	public function setLanguageId($languageId)
	{
		$this->languageId = $languageId;
	}
	
	/**
	 * @param $invoiceId
	 */
	public function setInvoiceId($invoiceId)
	{
		$this->invoiceId = $invoiceId;
	}
	
	/**
	 * @param $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	/**
	 * @param $time
	 */
	public function setTime($time)
	{
		$this->time = $time;
	}
	
	/**
	 * @param $taxField
	 */
	public function setTaxField($taxField)
	{
		$this->taxField = $taxField;
	}
	
	/**
	 * @param $shippingField
	 */
	public function setShippingField($shippingField)
	{
		$this->shippingField = $shippingField;
	}
	
	/**
	 * @param $discountField
	 */
	public function setDiscountField($discountField)
	{
		$this->discountField = $discountField;
	}
	
	/**
	 * @param $phraseKey
	 * @param array $params
	 * @param array $options
	 *
	 * @return mixed|null|string|string[]
	 */
	public function renderPhrase($phraseKey, array $params = [], array $options = [])
	{
		if ($this->languageId == -1)
		{
			// Render the phrase
			return \XF::language()->renderPhrase($phraseKey, $params, 'html', $options);
		}

		// This is being rendered in another language
		return \XF::app()->language($this->languageId)->renderPhrase($phraseKey, $params, 'html', $options);
	}
	
	/**
	 *
	 * @throws \Exception
	 */
	public function Header()
	{
		$this->phrases = [
			'invoice' => $this->renderPhrase('dbtech_ecommerce_invoice'),
			'date' => $this->renderPhrase('date'),
			'number' => $this->renderPhrase('dbtech_ecommerce_invoice_id'),
			'time' => $this->renderPhrase('time'),
			'from' => $this->renderPhrase('from'),
			'product' => $this->renderPhrase('dbtech_ecommerce_product'),
			'shipping' => $this->renderPhrase('dbtech_ecommerce_shipping_cost'),
			'tax' => $this->renderPhrase('dbtech_ecommerce_sales_tax'),
			'tax_rate_x' => $this->renderPhrase('dbtech_ecommerce_sales_tax_rate_x', [
				'rate' => $this->order->getSalesTaxRate('digital')
			]),
			'quantity' => $this->renderPhrase('dbtech_ecommerce_quantity'),
			'price' => $this->renderPhrase('dbtech_ecommerce_price'),
			'discount' => $this->renderPhrase('dbtech_ecommerce_discount'),
			'discounts' => $this->renderPhrase('dbtech_ecommerce_discounts'),
			'sub_total' => $this->renderPhrase('dbtech_ecommerce_sub_total'),
			'total' => $this->renderPhrase('dbtech_ecommerce_total'),
			'new' =>$this->renderPhrase('dbtech_ecommerce_item_type.new'),
			'upgrade' => $this->renderPhrase('dbtech_ecommerce_item_type.upgrade'),
			'renew' => $this->renderPhrase('dbtech_ecommerce_item_type.renew')
		];
		
		// First page
		if ($this->PageNo() == 1)
		{
			if ($this->logo)
			{
				$this->Image($this->logo, $this->GetX(), 10);
			}
			
			$this->SetXY($this->GetX(), $this->GetY() + 15);
			$this->SetLineWidth(0.4);
		
			// Title
			$this->SetTextColor(0, 0, 0);
			$this->SetFont($this->boldFont, 'B', 20);
			$this->Cell(0, 0, \mb_strtoupper($this->phrases['invoice']), 0, 1, 'R');
			$this->SetFont($this->font, '', 9);
			$this->Ln(5);
	
			$lineHeight = 5;
	
			//Calculate position of strings
			$this->SetFont($this->boldFont, 'B', 9);
			$positionX = 145 - max(
				\mb_strtoupper($this->GetStringWidth($this->phrases['number'])),
				\mb_strtoupper($this->GetStringWidth($this->phrases['date'])),
				\mb_strtoupper($this->GetStringWidth($this->phrases['time']))
			);
	
			//Number
			if ($this->invoiceId)
			{
				$this->Cell($positionX, $lineHeight);
				$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);
				$this->Cell(32, $lineHeight, \mb_strtoupper($this->phrases['number']) . ': ', 0, 0, 'L');
				$this->SetTextColor(50, 50, 50);
				$this->SetFont($this->font, '', 9);
				$this->Cell(0, $lineHeight, $this->invoiceId, 0, 1, 'R');
			}
	
			// Date
			$this->Cell($positionX, $lineHeight);
			$this->SetFont($this->boldFont, 'B', 9);
			$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);
			$this->Cell(32, $lineHeight, \mb_strtoupper($this->phrases['date']).': ', 0, 0, 'L');
			$this->SetTextColor(50, 50, 50);
			$this->SetFont($this->font, '', 9);
			$this->Cell(0, $lineHeight, $this->date, 0, 1, 'R');
	
			// Time
			if ($this->time)
			{
				$this->Cell($positionX, $lineHeight);
				$this->SetFont($this->boldFont, 'B', 9);
				$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);
				$this->Cell(32, $lineHeight, \mb_strtoupper($this->phrases['time']).': ', 0, 0, 'L');
				$this->SetTextColor(50, 50, 50);
				$this->SetFont($this->font, '', 9);
				$this->Cell(0, $lineHeight, $this->time, 0, 1, 'R');
			}

		
			if ($this->GetY() < 15)
			{
				$this->SetY(20);
			}
			else
			{
				$this->SetY($this->GetY() + 10);
			}
			
			$this->Ln(5);
			$this->SetFillColor($this->color[0], $this->color[1], $this->color[2]);
			$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);

			$this->SetDrawColor($this->color[0], $this->color[1], $this->color[2]);
			$this->SetFont($this->boldFont, 'B', 10);
	
			$options = \XF::options();
			
			if ($options->dbtechEcommerceBusinessTitle)
			{
				//Information
				$this->SetTextColor(50, 50, 50);
				$this->SetFont($this->boldFont, 'B', 10);
				$this->Cell(90, $lineHeight, $options->dbtechEcommerceBusinessTitle, 0, 0, 'L');
				if ($this->order->Address)
				{
					$this->Cell(0, $lineHeight, $this->order->Address->business_title, 0, 0, 'R');
				}
				
				$this->SetFont($this->font, '', 8);
				$this->SetTextColor(100, 100, 100);
				$this->Ln(7);
				
				$this->Cell(90, $lineHeight, $options->dbtechEcommerceBusinessCo, 0, 0, 'L');
				if ($this->order->Address)
				{
					$this->Cell(0, $lineHeight, $this->order->Address->business_co, 0, 0, 'R');
				}
				$this->Ln(5);
				
				for ($i = 1; $i < 5; $i++)
				{
					$optionKey = 'dbtechEcommerceBusinessAddress' . $i;
					$addressKey = 'address' . $i;
					$this->Cell(90, $lineHeight, $options->$optionKey, 0, 0, 'L');
					if ($this->order->Address)
					{
						$this->Cell(0, $lineHeight, $this->order->Address->$addressKey, 0, 0, 'R');
					}
					$this->Ln(5);
				}
				
				/** @var \DBTech\eCommerce\Repository\Country $countryRepo */
				$countryRepo = \XF::repository('DBTech\eCommerce:Country');
				
				if ($options->dbtechEcommerceAddressCountry)
				{
					$this->Cell(90, $lineHeight, $countryRepo->findCountryByCode($options->dbtechEcommerceAddressCountry)->fetchOne()->native_name, 0, 0, 'L');
				}
				if ($this->order->Address && $this->order->Address->Country)
				{
					$this->Cell(0, $lineHeight, $this->order->Address->Country->native_name, 0, 0, 'R');
				}
				$this->Ln(5);
				
				if ($options->dbtechEcommerceBusinessTaxId)
				{
					$this->Cell(90, $lineHeight, $options->dbtechEcommerceBusinessTaxId, 0, 0, 'L');
					if ($this->order->Address)
					{
						$this->Cell(0, $lineHeight, $this->order->Address->sales_tax_id, 0, 0, 'R');
					}
					$this->Ln(5);
				}
				
				$this->Ln(-6);
				$this->Ln(5);
			}
			else
			{
				$this->Ln(-10);
			}
		}

		// Table header
		if (!$this->productsEnded)
		{
			$productWidth = 69 + $this->columnSpacing
				+ ($this->taxField ? 0 : (29 + $this->columnSpacing))
				+ ($this->discountField ? 0 : (29 + $this->columnSpacing))
			;
			
			$this->SetTextColor(50, 50, 50);
			$this->Ln(12);
			$this->SetFont($this->boldFont, 'B', 9);
			$this->Cell(4, 10, '', 0, 0, 'L', 0);
			$this->Cell($productWidth, 10, \mb_strtoupper($this->phrases['product']), 0, 0, 'L', 0);
			$this->Cell($this->columnSpacing, 10, '', 0, 0, 'L', 0);

			$this->Cell(29, 10, \mb_strtoupper($this->phrases['price']), 0, 0, 'C', 0);
			$this->Cell($this->columnSpacing, 10, '', 0, 0, 'L', 0);

			if ($this->taxField)
			{
				$this->Cell(29, 10, \mb_strtoupper($this->phrases['tax']), 0, 0, 'C', 0);
				$this->Cell($this->columnSpacing, 10, '', 0, 0, 'L', 0);
			}

			if ($this->discountField)
			{
				$this->Cell(29, 10, \mb_strtoupper($this->phrases['discount']), 0, 0, 'C', 0);
				$this->Cell($this->columnSpacing, 10, '', 0, 0, 'L', 0);
			}
			
			$this->Cell(23, 10, \mb_strtoupper($this->phrases['total']), 0, 0, 'C', 0);
			$this->Ln();
			
			$this->SetLineWidth($this->columnSpacing);
			$this->SetDrawColor($this->color[0], $this->color[1], $this->color[2]);
			$this->Line(15, $this->GetY(), 210- 15, $this->GetY());
			$this->Ln(2);
		}
		else
		{
			$this->Ln(12);
		}
	}
	
	/**
	 * @throws \Exception
	 */
	public function Body()
	{
		/** @var \XF\Data\Currency $currencyData */
		$currencyData = \XF::app()->data('XF:Currency');
		
		$this->AddPage();
		$leftPadding = 4.7;
		$productWidth = 69 + $this->columnSpacing + ($this->taxField ? 0 : (29 + $this->columnSpacing)) + ($this->discountField ? 0 : (29 + $this->columnSpacing));
		$cellHeight = 8;
		$bgcolor = 239.7;
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $item */
		foreach ($this->order->Items as $item)
		{
			$title = $item->quantity . 'x ' . $item->getFullTitle();
			$title .= ' (' . $this->phrases[$item->item_type] . ')';
			
			$this->SetFont($this->boldFont, 'B', 8);
			$this->SetTextColor(50, 50, 50);
			$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
			$this->Cell($leftPadding, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell($productWidth, $cellHeight, $title, 0, 0, 'L', 1);
			
			$this->SetTextColor(50, 50, 50);
			$this->SetFont($this->font, '', 8);
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(29, $cellHeight, $currencyData->languageFormat($item->base_price, $this->order->currency), 0, 0, 'C', 1);
			
			if ($this->taxField)
			{
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				$this->Cell(29, $cellHeight, $currencyData->languageFormat($item->sales_tax, $this->order->currency), 0, 0, 'C', 1);
			}
			
			if ($this->discountField)
			{
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				$this->Cell(29, $cellHeight, $currencyData->languageFormat(($item->coupon_discount + $item->sale_discount) * -1, $this->order->currency), 0, 0, 'C', 1);
			}
			
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(23, $cellHeight, $currencyData->languageFormat($item->price, $this->order->currency), 0, 0, 'C', 1);
			$this->Ln();
			$this->Ln($this->columnSpacing);
		}
		
		// Grab the coords where we'll put the badge
		$badgeX = $this->GetX();
		$badgeY = $this->GetY();
		
		// Sub-total
		$this->SetTextColor(50, 50, 50);
		$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
		$this->Cell($leftPadding, $cellHeight, '', 0, 0, 'L', 0);
		$this->Cell($productWidth, $cellHeight, '', 0, 0, 'L', 0);
		
		if ($this->taxField)
		{
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
		}
		
		if ($this->discountField)
		{
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
		}
		
		$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
		$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont($this->boldFont, 'B', 8);
		$this->Cell(29, $cellHeight, $this->phrases['sub_total'], 0, 0, 'L', 1);
		
		$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
		$this->SetFont($this->boldFont, 'B', 8);
		$this->SetTextColor(0, 0, 0);
		$this->Cell(23, $cellHeight, $currencyData->languageFormat($this->order->sub_total, $this->order->currency), 0, 0, 'C', 1);
		$this->Ln();
		$this->Ln($this->columnSpacing);
		
		// Discount
		$this->SetTextColor(50, 50, 50);
		$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
		$this->Cell($leftPadding, $cellHeight, '', 0, 0, 'L', 0);
		$this->Cell($productWidth, $cellHeight, '', 0, 0, 'L', 0);
		
		if ($this->taxField)
		{
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
		}
		
		if ($this->discountField)
		{
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
		}
		
		$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
		$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont($this->font, '', 8);
		$this->Cell(29, $cellHeight, $this->phrases['discounts'], 0, 0, 'L', 1);
		
		$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
		$this->SetFont($this->font, '', 8);
		$this->SetTextColor(0, 0, 0);
		$this->Cell(23, $cellHeight, $currencyData->languageFormat(
			($this->order->coupon_discounts + $this->order->automatic_discounts + $this->order->store_credit_amount) * -1,
			$this->order->currency
		), 0, 0, 'C', 1);
		$this->Ln();
		$this->Ln($this->columnSpacing);
		
		if ($this->shippingField)
		{
			// Shipping
			$this->SetTextColor(50, 50, 50);
			$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
			$this->Cell($leftPadding, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell($productWidth, $cellHeight, '', 0, 0, 'L', 0);
			
			if ($this->taxField)
			{
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
			}
			
			if ($this->discountField)
			{
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
			}
			
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
			$this->SetTextColor(0, 0, 0);
			$this->SetFont($this->font, '', 8);
			$this->Cell(29, $cellHeight, $this->phrases['shipping'], 0, 0, 'L', 1);
			
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->SetFont($this->font, '', 8);
			$this->SetTextColor(0, 0, 0);
			$this->Cell(23, $cellHeight, $currencyData->languageFormat($this->order->shipping_cost, $this->order->currency), 0, 0, 'C', 1);
			$this->Ln();
			$this->Ln($this->columnSpacing);
		}
		
		if ($this->taxField)
		{
			// Tax
			$this->SetTextColor(50, 50, 50);
			$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
			$this->Cell($leftPadding, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell($productWidth, $cellHeight, '', 0, 0, 'L', 0);
			
			if ($this->taxField)
			{
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
			}
			
			if ($this->discountField)
			{
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
			}
			
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
			$this->SetTextColor(0, 0, 0);
			$this->SetFont($this->font, '', 8);
			$this->Cell(29, $cellHeight, $this->phrases['tax_rate_x'], 0, 0, 'L', 1);
			
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->SetFont($this->font, '', 8);
			$this->SetTextColor(0, 0, 0);
			$this->Cell(23, $cellHeight, $currencyData->languageFormat($this->order->sales_tax, $this->order->currency), 0, 0, 'C', 1);
			$this->Ln();
			$this->Ln($this->columnSpacing);
		}
		
		// Total
		$this->SetTextColor(50, 50, 50);
		$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
		$this->Cell($leftPadding, $cellHeight, '', 0, 0, 'L', 0);
		$this->Cell($productWidth, $cellHeight, '', 0, 0, 'L', 0);
		
		if ($this->taxField)
		{
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
		}
		
		if ($this->discountField)
		{
			$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
			$this->Cell(29, $cellHeight, '', 0, 0, 'L', 0);
		}
		
		$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
		$this->SetTextColor(255, 255, 255);
		$this->SetFillColor($this->color[0], $this->color[1], $this->color[2]);
		$this->SetFont($this->boldFont, 'B', 8);
		$this->Cell(29, $cellHeight, $this->phrases['total'], 0, 0, 'L', 1);
		
		$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
		$this->SetFont($this->boldFont, 'B', 8);
		$this->SetFillColor($bgcolor, $bgcolor, $bgcolor);
		$this->SetTextColor(255, 255, 255);
		$this->SetFillColor($this->color[0], $this->color[1], $this->color[2]);
		$this->Cell(23, $cellHeight, $currencyData->languageFormat($this->order->order_total, $this->order->currency), 0, 0, 'C', 1);
		$this->Ln();
		$this->Ln($this->columnSpacing);
		
		$this->productsEnded = true;
		$this->Ln();
		$this->Ln(3);
		
		// Badge
		$badge = ' ' . strtoupper($this->renderPhrase('dbtech_ecommerce_invoice_paid')) . ' ';
		$resetX = $this->GetX();
		$resetY = $this->GetY();
		$this->SetXY($badgeX, $badgeY + 15);
		$this->SetLineWidth(0.4);
		$this->SetDrawColor(255, 0, 0);
		$this->SetTextColor(255, 0, 0);
		$this->SetFont($this->boldFont, 'B', 15);
		$this->Rotate(10, $this->GetX(), $this->GetY());
		$this->Rect($this->GetX(), $this->GetY(), $this->GetStringWidth($badge) + 2, 10);
		$this->Write(10, $badge);
		$this->Rotate(0);
		
		if ($resetY > $this->GetY() + 20)
		{
			$this->SetXY($resetX, $resetY);
		}
		else
		{
			$this->Ln(18);
		}
		
		$phrase = $this->renderPhrase('dbtech_ecommerce_invoice_closing_line', [], ['nameOnInvalid' => false]);
		if ($phrase)
		{
			$this->SetFont($this->font, '', 8);
			$this->SetTextColor(50, 50, 50);
			$this->Cell(0, 10, $phrase, 0, 0, 'C');
			$this->Ln(12);
		}
	}
	
	/**
	 *
	 */
	public function Footer()
	{
		$this->SetY(-15);
		$this->SetLineWidth($this->columnSpacing);
		$this->SetDrawColor($this->color[0], $this->color[1], $this->color[2]);
		$this->Line(15, $this->GetY(), 210- 15, $this->GetY());
		$this->Ln(2);
		
		$this->SetY(-15);
		$this->SetFont($this->font, '', 8);
		$this->SetTextColor(50, 50, 50);
		$this->Cell(0, 10, $this->renderPhrase('dbtech_ecommerce_thank_you_for_business'), 0, 0, 'C');
	}
	
	/**
	 * @param $angle
	 * @param int $x
	 * @param int $y
	 */
	public function Rotate($angle, $x =- 1, $y =- 1)
	{
		if ($x == -1)
		{
			$x = $this->flt_position_x;
		}
		
		if ($y == -1)
		{
			$y = $this->flt_position_y;
		}
		
		if ($this->angle != 0)
		{
			$this->Out('Q');
		}
		
		$this->angle = $angle;
		
		if ($angle != 0)
		{
			$angle *= M_PI / 180;
			$c = cos($angle);
			$s = sin($angle);
			$cx = $x * $this->flt_scale_factor;
			$cy = ($this->flt_current_height - $y) * $this->flt_scale_factor;
			$this->Out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
		}
	}
	
	/**
	 *
	 */
	protected function EndPage()
	{
		if ($this->angle != 0)
		{
			$this->angle = 0;
			$this->Out('Q');
		}
		
		parent::EndPage();
	}
	
	/**
	 * @param string $fileName
	 *
	 * @return void
	 * @throws \LogicException
	 */
	public function writePdf($fileName)
	{
		File::writeToAbstractedPath('internal-data://dbtechEcommerce/invoices/'. $fileName, $this->output(), [], true);
	}
}