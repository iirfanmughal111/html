var DBTecheCommerce = window.DBTecheCommerce || {};

!function($, window, document, _undefined)
{
	"use strict";

	// ################################## --- ###########################################
	DBTecheCommerce.ProductPricingManager = XF.Element.newHandler({

		options: {
			insertButton: '.js-addProductPricing',
			manageUrl: null,
			productType: null,
			pricingContainer: '.js-productCosts',
			costRow: '.js-productCost',
			actionButton: '.js-pricingAction',
			pricingTemplate: '.js-pricingTemplate'
		},

		$pricingContainer: null,
		template: null,

		manageUrl: null,
		productType: null,

		init: function()
		{
			var self = this,
				options = this.options,
				$target = this.$target;

			if (this.options.manageUrl)
			{
				this.manageUrl = this.options.manageUrl;
			}
			else if ($target.attr('href'))
			{
				this.manageUrl = $target.attr('href');
			}
			else
			{
				console.error('No manage URL specified.');
				return;
			}

			if (this.options.productType)
			{
				this.productType = this.options.productType;
			}
			else if ($target.data('product-type'))
			{
				this.productType = $target.data('product-type');
			}
			else
			{
				console.error('No product type specified.');
				return;
			}

			this.$pricingContainer = $target.find(options.pricingContainer);
			this.$pricingContainer
				.on('click', options.actionButton, $.proxy(this, 'actionButtonClick'));

			$target.on('click', options.insertButton, $.proxy(this, 'insertButtonClick'));

			this.template = $target.find(options.pricingTemplate).html();
			if (!this.template)
			{
				console.error('No attached file template found.');
			}
		},

		insertCostRow: function(cost, $existingHtml)
		{
			var $newHtml = this.applyCostTemplate(cost);

			if ($existingHtml)
			{
				$existingHtml.replaceWith($newHtml);
			}
			else
			{
				this.$pricingContainer.addClass('is-active');
				$newHtml.appendTo(this.$pricingContainer);
			}

			XF.activate($newHtml);
			XF.layoutChange();

			var event = $.Event('cost:row-inserted');
			$newHtml.trigger(event, [$newHtml, this]);
		},

		insertButtonClick: function(e)
		{
			e.preventDefault();

			var self = this;

			XF.ajax(
				'post',
				this.manageUrl,
				{ product_type: this.productType },
				function (data)
				{
					if (data.cost)
					{
						self.insertCostRow(data.cost);
					}
				},
				{ skipDefaultSuccess: true }
			);
		},

		actionButtonClick: function(e)
		{
			e.preventDefault();

			var $target = $(e.currentTarget),
				action = $target.attr('data-action'),
				$row = $target.closest(this.options.costRow);

			switch (action)
			{
				case 'delete':
					this.deleteProductCost($row);
					break;
			}
		},

		deleteProductCost: function($row)
		{
			var costId = $row.data('cost-id');
			if (!costId)
			{
				return;
			}

			var self = this;

			XF.ajax(
				'post',
				this.manageUrl,
				{ delete: costId },
				function (data)
				{
					if (data.delete)
					{
						self.removeCostRow($row);
					}
				},
				{ skipDefaultSuccess: true }
			);
		},

		applyCostTemplate: function(params)
		{
			var $html = $($.parseHTML(Mustache.render(this.template, params))),
				costRow = this.options.costRow;

			return $html.filter(function() { return $(this).is(costRow); });
		},

		removeCostRow: function($row)
		{
			$row.remove();

			if (!this.$pricingContainer.find(this.options.costRow).length)
			{
				this.$pricingContainer.removeClass('is-active');
				XF.layoutChange();
			}
		}
	});

	// ################################## --- ###########################################
	DBTecheCommerce.ProductPricingOnInsert = XF.Element.newHandler({

		options: {
			costRow: '.js-productCost',
			href: null,
			linkData: null
		},

		loading: false,

		init: function()
		{
			var $row = this.$target.closest(this.options.costRow);
			if (!$row.length || !this.options.href)
			{
				console.error('Cannot find inserted row or action to perform.');
			}
			$row.on('cost:row-inserted', $.proxy(this, 'onCostInsert'));
		},

		onCostInsert: function(e, $html, manager)
		{
			if (this.loading)
			{
				return;
			}

			var self = this,
				href = this.options.href,
				data = this.options.linkData || {};

			XF.ajax('post', href, data, $.proxy(this, 'onLoad')).always(function() { self.loading = false; });
		},

		onLoad: function(data)
		{
			if (!data.html)
			{
				return;
			}

			var self = this;

			XF.setupHtmlInsert(data.html, function($html, container, onComplete)
			{
				self.$target.replaceWith($html).xfFadeDown(XF.config.speed.xfast, function()
				{
					onComplete(true);
					XF.layoutChange();
				});
			});
		}
	});

	XF.Element.register('dbtech-ecommerce-product-pricing-manager', 'DBTecheCommerce.ProductPricingManager');
	XF.Element.register('dbtech-ecommerce-product-pricing-on-insert', 'DBTecheCommerce.ProductPricingOnInsert');
}(jQuery, window, document);