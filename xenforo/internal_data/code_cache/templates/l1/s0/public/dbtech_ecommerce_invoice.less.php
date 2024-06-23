<?php
// FROM HASH: 70c507688e0bdd7bada545f0de16f1b5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'html {
	.xf-dbtechEcommerceInvoiceBackground();
}

body {
    margin:     0;
    padding:    0;
    width:      21cm;
    height:     29.7cm;
}

footer {
    position:   absolute;
    bottom:     0;
    width:      100%;
}

.p-pageWrapper
{
	&&--invoice
	{
		.xf-dbtechEcommerceInvoicePageWrapper();
	}
}

.p-body-inner
{
	padding-left: 0;
	padding-right: 0;
}

.invoice-header
{
	.xf-dbtechEcommerceInvoiceHeader();

	.invoice-header-logo
    {
    	float: left;
    	vertical-align: middle;
    	margin-right: auto;

    	a
    	{
    		color: inherit;
    		text-decoration: none;
    	}

    	&.invoice-header-logo--text
    	{
    		font-size: @xf-fontSizeLargest;
    	}

    	&.invoice-header-logo--image
    	{
    		img
    		{
    			vertical-align: top;
    			max-width: 100%;
    			max-height: 200px;
    		}
    	}
    }

	.invoice-header-details
	{
		float: right;
		text-transform: uppercase;
		font-weight: @xf-fontWeightHeavy;

		.p-title-value
		{
			font-weight: @xf-fontWeightHeavy;

			.invoice-header-paid
			{
				float: right;
				color: @xf-errorColor;

				&:after
				{
					content: "]";
				}

				&:before
				{
					content: "[";
				}
			}
		}

		.invoice-details-pairs
        {
        	padding: 0;
        	margin: 0;
        	overflow: hidden;

        	> dt
        	{
        		padding: 0;
        		margin: 0;
        		color: @xf-textColorMuted;

        		.m-appendColon();
        	}

        	> dd
        	{
        		padding: 0;
        		margin: 0;
        	}

        	&.pairs--columns
        	{
        		display: table;
//         		table-layout: fixed;
        		width: 100%;

        		> dt,
        		> dd
        		{
        			display: table-cell;
        		}

        		> dt
        		{
        			width: 50%;
        			padding-right: @xf-paddingMedium;
        		}

        		&.pairs--fixedSmall > dt
        		{
        			width: 150px;
        		}
        	}
        }
	}

	.invoice-header-addresses
	{
		clear: both;
		padding-top: @xf-paddingLargest;

		.address-seller
		{
			float: left;
			width: 40%;

			div:first-child
			{
				font-weight: @xf-fontWeightHeavy;
			}
		}

		.address-buyer
		{
			float: right;
			width: 40%;
			text-align: right;

			div:first-child
			{
				font-weight: @xf-fontWeightHeavy;
			}
		}
	}
}

.dataList-row
{
	&.dataList-row--subSection
	{
		.dataList-cell
		{
			&.footer-no-color
            {
            	background: none;
            	border: none;
            }
		}
	}
}

.pre-footer-closing-line
{
	margin-top: (@xf-paddingLargest * 2);
}

.invoice-grand-total
{
	font-weight: @xf-fontWeightHeavy;
}';
	return $__finalCompiled;
}
);