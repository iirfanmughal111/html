var DBTecheCommerce = window.DBTecheCommerce || {};

!function($, window, document)
{
	// ################################## --- ###########################################
	DBTecheCommerce.InfiniteScroll = XF.Element.newHandler(
	{
		init: function()
		{
			$grid = this.$target;

			var $scroller = $grid.infiniteScroll({
				button: '.product-button',
				append: '.productList-product',
				hideNav: '.block-outer--pagination',
				path: '.block-outer--pagination .pageNav-jump--next',
				status: '.product-status',
				history: $grid.data('infinite-scroll-history') ? 'push' : false
			});

			$scroller.on('last.infiniteScroll', function()
			{
				$('.product-status').hide();
				$('.product-loader').hide();
			});

			if ($grid.data('infinite-scroll-click'))
			{
				if ($grid.data('infinite-scroll-after'))
				{
					$scroller.on('load.infiniteScroll', function onPageLoad()
					{
						if ($scroller.data('infiniteScroll').loadCount == $grid.data('infinite-scroll-after'))
						{
							$('.product-loader').show();
							$scroller.infiniteScroll('option', { loadOnScroll: false });
							$scroller.off('load.infiniteScroll', onPageLoad);
						}
					});
				}
				else
				{
					$('.product-loader').show();
					$scroller.infiniteScroll('option', { loadOnScroll: false });
				}
			}
		}
	});

	// ################################## --- ###########################################

	XF.Element.register('dbtech-ecommerce-infinite-scroll', 'DBTecheCommerce.InfiniteScroll');
}
(window.jQuery, window, document);