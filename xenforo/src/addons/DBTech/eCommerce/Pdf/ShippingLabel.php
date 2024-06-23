<?php
namespace DBTech\eCommerce\Pdf;

use DBTech\eCommerce\Entity\Order;


use XF\Util\File;

/**
 * Class ShippingLabel
 *
 * @package DBTech\eCommerce\Pdf
 */
class ShippingLabel extends Barcode
{
	/**
	 * @var Order
	 */
	protected $order;
	
	/**
	 * @var
	 */
	protected $date;
	
	/**
	 * @var
	 */
	protected $time;
	
	/**
	 * @var array
	 */
	protected $items = [];
	
	/**
	 * @var array
	 */
	protected $totals = [];
	
	/**
	 * @var bool
	 */
	protected $taxField = false;
	
	/**
	 * @var bool
	 */
	protected $shippingField = false;
	
	/**
	 * @var bool
	 */
	protected $discountField = false;
	
	/**
	 * @var bool
	 */
	protected $productsEnded = false;
	
	/**
	 * @var string
	 */
	protected $invoiceId = '';
	
	/**
	 * @var int
	 */
	protected $languageId = -1;
	
	/**
	 * @var array
	 */
	protected $phrases = [];
	
	/**
	 * @var string
	 */
	protected $font = 'DejaVuSans';
	
	/**
	 * @var string
	 */
	protected $boldFont = 'DejaVuSans';

	/**
	 * @var array
	 */
	protected $color = [
		0, 127, 255
	];
	
	/**
	 * @var int
	 */
	protected $angle = 0;
	
	/**
	 * @var int
	 */
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
	 *
	 * @return mixed|null|string|string[]
	 */
	public function renderPhrase($phraseKey, array $params = [])
	{
		if ($this->languageId == -1)
		{
			// Render the phrase
			return \XF::language()->renderPhrase($phraseKey, $params);
		}

		// This is being rendered in another language
		return \XF::app()->language($this->languageId)->renderPhrase($phraseKey, $params);
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
			'from' => $this->renderPhrase('dbtech_ecommerce_from'),
			'to' => $this->renderPhrase('dbtech_ecommerce_to'),
		];
		
		$this->SetXY($this->GetX(), $this->GetY() + 15);
		$this->SetLineWidth(0.4);
	
		$lineHeight = 5;
		
		$options = \XF::options();
		if ($options->dbtechEcommerceBusinessTitle)
		{
			// First line
			$this->SetTextColor(50, 50, 50);
			$this->SetFont($this->boldFont, 'B', 12);
			$this->Cell(90, $lineHeight, \mb_strtoupper($this->phrases['from']) . ':', 0, 0, 'L');
			$this->Ln(12);
			
			// Business title
			$this->SetTextColor(100, 100, 100);
			$this->SetFont($this->font, '', 12);
			$this->Cell(90, $lineHeight, $options->dbtechEcommerceBusinessTitle, 0, 0, 'L');
			
			if ($this->invoiceId)
			{
				$this->SetTextColor(50, 50, 50);
				$this->SetFont($this->boldFont, 'B', 12);
				$this->Cell(0, $lineHeight, \mb_strtoupper($this->phrases['number']) . ': ' . $this->invoiceId, 0, 0, 'R');
			}
			
			$this->Ln(7);
			
			// Business c/o
			$this->SetTextColor(100, 100, 100);
			$this->SetFont($this->font, '', 12);
			$this->Cell(90, $lineHeight, $options->dbtechEcommerceBusinessCo, 0, 0, 'L');
			
			$this->SetTextColor(50, 50, 50);
			$this->SetFont($this->boldFont, 'B', 12);
			$this->Cell(0, $lineHeight, \mb_strtoupper($this->phrases['date']) . ': ' . $this->date, 0, 0, 'R');
			
			$this->Ln(7);
			
			// Address 1
			$this->SetTextColor(100, 100, 100);
			$this->SetFont($this->font, '', 12);
			$this->Cell(90, $lineHeight, $options->dbtechEcommerceBusinessAddress1, 0, 0, 'L');
			
			if ($this->time)
			{
				$this->SetTextColor(50, 50, 50);
				$this->SetFont($this->boldFont, 'B', 12);
				$this->Cell(0, $lineHeight, \mb_strtoupper($this->phrases['time']) . ': ' . $this->time, 0, 0, 'R');
			}
			
			$this->Ln(7);
			
			// Address 2
			$this->SetTextColor(100, 100, 100);
			$this->SetFont($this->font, '', 12);
			$this->Cell(90, $lineHeight, $options->dbtechEcommerceBusinessAddress2, 0, 0, 'L');
			
			$code = 'ORDER ID ' . $this->order->order_id;
			$this->Code128(206, $this->GetY(), $code, 80, 20);
			
			$this->Ln(7);
			
			$this->SetFont($this->font, '', 12);
			$this->SetTextColor(100, 100, 100);
			
			for ($i = 3; $i < 5; $i++)
			{
				$optionKey = 'dbtechEcommerceBusinessAddress' . $i;
				
				// Address line $i
				$this->Cell(90, $lineHeight, $options->$optionKey, 0, 0, 'L');
				
				$this->Ln(7);
			}
		}
		else
		{
			$positionX = 230 - max(
				\mb_strtoupper($this->GetStringWidth($this->phrases['number'])),
				\mb_strtoupper($this->GetStringWidth($this->phrases['date'])),
				\mb_strtoupper($this->GetStringWidth($this->phrases['time']))
			);
			
			//Calculate position of strings
			$this->SetFont($this->boldFont, 'B', 12);
			
			//Number
			if ($this->invoiceId)
			{
				$this->Cell($positionX, $lineHeight);
				$this->Cell(32, $lineHeight, \mb_strtoupper($this->phrases['number']) . ': ', 0, 0, 'L');
				$this->SetTextColor(50, 50, 50);
				$this->SetFont($this->font, '', 12);
				$this->Cell(0, $lineHeight, $this->invoiceId, 0, 1, 'R');
			}
			
			// Date
			$this->Cell($positionX, $lineHeight);
			$this->SetFont($this->boldFont, 'B', 12);
			$this->Cell(32, $lineHeight, \mb_strtoupper($this->phrases['date']).': ', 0, 0, 'L');
			$this->SetTextColor(50, 50, 50);
			$this->SetFont($this->font, '', 12);
			$this->Cell(0, $lineHeight, $this->date, 0, 1, 'R');
			
			// Time
			if ($this->time)
			{
				$this->Cell($positionX, $lineHeight);
				$this->SetFont($this->boldFont, 'B', 12);
				$this->Cell(32, $lineHeight, \mb_strtoupper($this->phrases['time']).': ', 0, 0, 'L');
				$this->SetTextColor(50, 50, 50);
				$this->SetFont($this->font, '', 12);
				$this->Cell(0, $lineHeight, $this->time, 0, 1, 'R');
			}
			
			$code = 'ORDER ID ' . $this->order->order_id;
			$this->Code128($positionX - 24, $this->GetY(), $code, 80, 20);
			
			$this->Ln(7);
		}
		
		if ($this->GetY() < 15)
		{
			$this->SetY(20);
		}
		else
		{
			$this->SetY($this->GetY() + 10);
		}

		$this->Ln(12);
	}
	
	/**
	 * @throws \Exception
	 */
	public function Body()
	{
		$this->AddPage();
		
		$this->Ln(5);
		
		$this->SetFont($this->font, '', 18);
		
		$lineHeight = 5;
		$positionX = 125;
		
		if ($this->order->Address)
		{
			$this->Cell($positionX, $lineHeight, $this->phrases['to'] . ': ', 0, 0, 'R');
			$this->Cell(0, $lineHeight, $this->order->Address->business_title, 0, 0, 'L');
			$this->Ln(9);
			
			if ($this->order->Address->business_co)
			{
				$this->Cell($positionX, $lineHeight, '', 0, 0, 'C');
				$this->Cell(0, $lineHeight, $this->order->Address->business_co, 0, 0, 'L');
				$this->Ln(9);
			}
			
			for ($i = 1; $i < 5; $i++)
			{
				$addressKey = 'address' . $i;
				$this->Cell($positionX, $lineHeight, '', 0, 0, 'C');
				$this->Cell(0, $lineHeight, $this->order->Address->$addressKey, 0, 0, 'L');
				$this->Ln(9);
			}
		}
		
		$this->Ln(18);
	}
	
	/**
	 *
	 */
	public function Footer()
	{
		$this->SetY(-15);
		$this->SetLineWidth($this->columnSpacing);
		$this->Line(15, $this->GetY(), 285, $this->GetY());
		$this->Ln(2);
		
		$this->SetY(-15);
		$this->SetFont($this->font, '', 8);
		$this->SetTextColor(50, 50, 50);
		$this->Cell(0, 10, $this->renderPhrase('dbtech_ecommerce_thank_you_for_business'), 0, 0, 'C');
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
		File::writeToAbstractedPath('internal-data://dbtechEcommerce/shippingLabels/'. $fileName, $this->output(), [], true);
	}
}