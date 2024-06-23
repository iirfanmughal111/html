var XASC = window.XASC || {};

!function($, window, document, _undefined)
{
	"use strict";

	XF.Inserter = XF.extend(XF.Inserter, {

		__backup: {
			'_scApplyAppend': '__scApplyAppend'
		},

		_scApplyAppend: function(selectorOld, $old, $new)
		{
			var validSelectors = ['.js-yourItemsList', '.js-yourPagesList', '.js-yourSeriesList', '.js-browseItemsList', '.js-browsePagesList', '.js-browseSeriesList'];
			if (validSelectors.indexOf(selectorOld) < 0)
			{
				this.__scApplyAppend(selectorOld, $old, $new);
				return;
			}

			var $placeholders = $old.find('.scItemList-item--placeholder'),
				$children = $new.children();

			if (!$placeholders.length)
			{
				this.__scApplyAppend(selectorOld, $old, $new);
				return;
			}

			$children.addClass('scItemList-item--placeholder--temp');

			this.__scApplyAppend(selectorOld, $old, $new);

			setTimeout(function()
			{
				$children.removeClass('scItemList-item--placeholder--temp');
				$placeholders.remove();

				XF.layoutChange();
			}, 10);
		}
	});

	XASC.editorButton = {
		init: function()
		{
			XASC.editorButton.initializeDialog();
			XF.EditorHelpers.dialogs.showcase = new XASC.EditorDialogShowcase('showcase');

			if ($.FE.COMMANDS.xfCustom_showcase)
			{
				$.FE.COMMANDS.xfCustom_showcase.callback = XASC.editorButton.callback;
			}
		},

		initializeDialog: function()
		{
			XASC.EditorDialogShowcase = XF.extend(XF.EditorDialog, {
				cache: false,
				$container: null,

				_init: function(overlay)
				{
					var $container = overlay.$container;
					$container.on('change', '.js-itemsPicker', XF.proxy(this, 'pick'));
					this.$container = $container;

					$('#xa_sc_editor_dialog_form').submit(XF.proxy(this, 'submit'));
				},

				_afterShow: function(overlay)
				{
					this.tabCounts = {
						yourItems: 0,
						yourPages: 0,
						yourSeries: 0,
						browseItems: 0,
						browsePages: 0,
						browseSeries: 0
					};
				},

				pick: function(e)
				{
					var $checkbox = this.$container.find(e.currentTarget),
						checked = $checkbox.is(':checked'),
						$item = $checkbox.parent();

					if (checked)
					{
						this.checked($item);
					}
					else
					{
						this.unchecked($item);
					}
				},

				checked: function($item, $checkbox)
				{
					$item.addClass('is-selected');

					var $pane = $item.closest('ul > li.is-active'),
						$tab = this.$container.find($pane.data('tab')),
						tabType = $tab.attr('id');

					if (!$tab.hasClass('has-selected'))
					{
						$tab.addClass('has-selected');
					}

					var $valueEl = this.$container.find('.js-embedValue'),
						value = JSON.parse($valueEl.val()),
						type = $item.data('type'), id = $item.data('id'),
						itemId = type + '-' + id;

					if (value.hasOwnProperty(itemId))
					{
						return;
					}

					value[itemId] = 1;

					var $countEl = $tab.find('.js-tabCounter');

					this.tabCounts[tabType] += 1;
					$countEl.text(this.tabCounts[tabType]);

					$valueEl.val(JSON.stringify(value));
				},

				unchecked: function($item, $checkbox)
				{
					$item.removeClass('is-selected');

					var $pane = $item.closest('ul > li.is-active'),
						$tab = this.$container.find($pane.data('tab')),
						tabType = $tab.attr('id');

					var $valueEl = this.$container.find('.js-embedValue'),
						value = JSON.parse($valueEl.val()),
						type = $item.data('type'), id = $item.data('id'),
						itemId = type + '-' + id;

					if (!value.hasOwnProperty(itemId))
					{
						return;
					}

					delete value[itemId];

					var $countEl = $tab.find('.js-tabCounter');

					this.tabCounts[tabType] -= 1;

					if (this.tabCounts[tabType])
					{
						$countEl.text(this.tabCounts[tabType]);
					}
					else
					{
						$countEl.text(0);
						$tab.removeClass('has-selected');
					}

					$valueEl.val(JSON.stringify(value));
				},

				submit: function(e)
				{
					e.preventDefault();

					var ed = this.ed,
						overlay = this.overlay,
						$valueEl = this.$container.find('.js-embedValue'),
						value = JSON.parse($valueEl.val()),
						output = '';

					for (var key in value)
					{
						if (!value.hasOwnProperty(key))
						{
							continue;
						}

						var parts = key.split('-'),
							type = parts[0], id = parts[1];

						output += XF.htmlspecialchars('[SHOWCASE=' + type + ', ' + parseInt(id) + '][/SHOWCASE]');
						output += '<p><br></p>';
					}

					ed.selection.restore();
					ed.html.insert(output);
					overlay.hide();
				}
			});
		},

		callback: function()
		{
			XF.EditorHelpers.loadDialog(this, 'showcase');
		}
	};

	$(document).on('editor:first-start', XASC.editorButton.init);
}
(jQuery, window, document);
