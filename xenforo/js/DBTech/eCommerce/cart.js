var DBTecheCommerce = window.DBTecheCommerce || {};

!function($, window, document)
{
	"use strict";

	$.extend(DBTecheCommerce, {
		config: {
			cartCounts: {
                dbtech_ecommerce_cart_items: 0
			}
		},

		onPageLoad: function()
		{
			$(document).on('ajax:complete', function(e, xhr, status)
			{
				var data = xhr.responseJSON;
				if (!data)
				{
					return;
				}

				if (data.visitor)
				{
					DBTecheCommerce.updateCartCounts(data.visitor);
				}
			});

			DBTecheCommerce.updateCartCounts(DBTecheCommerce.config.cartCounts);
		},

		updateCartCounts: function(visitor)
		{
			if (!visitor || XF.getApp() != 'public')
			{
				return;
			}

			XF.badgeCounterUpdate($('.js-badge--dbtechEcommerceCart'), visitor.dbtech_ecommerce_cart_items);
		}
	});

	$(DBTecheCommerce.onPageLoad);

	// ################################## CONTROL MULTI-DISABLER HANDLER ###########################################

	DBTecheCommerce.MultiDisabler = XF.Element.newHandler({
		options: {
			container: '< li | ul, ol, dl',
			controls: 'input, select, textarea, button, .js-attachmentUpload',
			inputControls: 'input[type=radio], input[type=checkbox]',
			hide: false,
			optional: false,
			invert: false // if true, system will disable on checked
		},

		$container: null,

		init: function()
		{
			this.$container = XF.findRelativeIf(this.options.container, this.$target);

			if (!this.$container.length)
			{
				if (!this.options.optional)
				{
					console.error('Could not find the disabler control container');
				}
			}

			var $inputContainer = this.$target,
				$form = $inputContainer.closest('form'),
				$self = this;
			if ($form.length)
			{
				$form.on('reset', XF.proxy(this, 'formReset'));
			}

			$inputContainer.find(this.options.inputControls).each(function()
			{
				var $input = $(this);

				if ($input.is(':radio'))
				{
					var $context = $form,
						name = $input.attr('name');
					if (!$form.length)
					{
						$context = $(document.body);
					}

					// radios only fire events for the element we click normally, so we need to know
					// when we move away from the value by firing every radio's handler for every click
					$context.on('click', 'input:radio[name="' + name + '"]', XF.proxy($self, 'click'));
				}
				else
				{
					$input.click(XF.proxy($self, 'click'));
				}

				// this ensures that nested disablers are disabled properly
				$input.on('control:enabled control:disabled', XF.proxy($self, 'recalculateAfter'));
			});

			// this ensures that dependent editors are initialised properly as disabled if needed
			this.$container.one('editor:init', XF.proxy(this, 'recalculateAfter'));

			this.recalculate(true);
		},

		click: function(e, options)
		{
			var noSelect = (options && options.triggered);
			this.recalculateAfter(false, noSelect);
		},

		formReset: function(e)
		{
			this.recalculateAfter(false, true);
		},

		recalculateAfter: function(init, noSelect)
		{
			var t = this;
			setTimeout(function()
			{
				t.recalculate(init, noSelect);
			}, 0);
		},

		recalculate: function(init, noSelect)
		{
			var $container = this.$container,
				$inputContainer = this.$target,
				$input = $inputContainer.find(this.options.inputControls),
				$controls = $container.find(this.options.controls).not($input),
				speed = init ? 0 : XF.config.speed.fast,
				enable = $input.not(':enabled').length == 0 && (($input.not(':checked').length == 0 && !this.options.invert) || (this.options.invert && $input.not(':checked').length != 0)),
				select = function()
				{
					if (noSelect)
					{
						return;
					}

					$container.find('input:not([type=hidden], [type=file]), textarea, select, button').not($input)
						.first().autofocus();
				};

			if (enable)
			{
				$container
					.prop('disabled', false)
					.removeClass('is-disabled');

				$controls
					.prop('disabled', false)
					.removeClass('is-disabled')
					.each(function(i, ctrl)
					{
						var $ctrl = $(ctrl);

						if ($ctrl.is('select.is-readonly'))
						{
							// readonly has to be implemented through disabling so we can't undisable this
							$ctrl.prop('disabled', true);
						}
					})
					.trigger('control:enabled');

				if (this.options.hide)
				{
					if (init)
					{
						$container.show();
					}
					else
					{
						var cb = function()
						{
							XF.layoutChange();
							select();
						};

						$container.slideDown(speed, cb);
					}
					XF.layoutChange();
				}
				else if (!init)
				{
					select();
				}
			}
			else
			{
				if (this.options.hide)
				{
					if (init)
					{
						$container.hide();
					}
					else
					{
						$container.slideUp(speed, XF.layoutChange);
					}
					XF.layoutChange();
				}

				$container
					.prop('disabled', true)
					.addClass('is-disabled');

				$controls
					.prop('disabled', true)
					.addClass('is-disabled')
					.trigger('control:disabled')
					.each(function(i, ctrl)
					{
						var $ctrl = $(ctrl),
							disabledVal = $ctrl.data('disabled');

						if (disabledVal !== null && typeof(disabledVal) != 'undefined')
						{
							$ctrl.val(disabledVal);
						}
					});
			}
		}
	});

	XF.Element.register('dbtech-ecommerce-multi-disabler', 'DBTecheCommerce.MultiDisabler');
}
(window.jQuery, window, document);