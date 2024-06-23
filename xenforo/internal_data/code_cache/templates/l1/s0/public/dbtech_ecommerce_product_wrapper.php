<?php
// FROM HASH: ffbe23ca4f119e89ffcd502928c4f3fa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumbs($__templater->method($__vars['product']['Category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	if ($__templater->method($__vars['product'], 'isAddOn', array())) {
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['product']['Parent']['title'])), $__templater->func('link', array('dbtech-ecommerce', $__vars['product']['Parent'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_product_page_macros', 'product_page_options', array(
		'category' => $__vars['product']['Category'],
		'product' => $__vars['product'],
	), $__vars) . '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => $__templater->method($__vars['product'], 'canViewProductImages', array()),
	), $__vars) . '

';
	$__compilerTemp1 = '';
	if ($__vars['xf']['options']['enableTagging'] AND ($__templater->method($__vars['product'], 'canEditTags', array()) OR $__vars['product']['tags'])) {
		$__compilerTemp1 .= '
			<li>
				<i class="fa fa-tags" aria-hidden="true" title="' . $__templater->filter('Tags', array(array('for_attr', array()),), true) . '"></i>
				<span class="u-srOnly">' . 'Tags' . '</span>

				';
		if ($__vars['product']['tags']) {
			$__compilerTemp1 .= '
					';
			if ($__templater->isTraversable($__vars['product']['tags'])) {
				foreach ($__vars['product']['tags'] AS $__vars['tag']) {
					$__compilerTemp1 .= '
						<a href="' . $__templater->func('link', array('tags', $__vars['tag'], ), true) . '" class="tagItem" dir="auto">' . $__templater->escape($__vars['tag']['tag']) . '</a>
					';
				}
			}
			$__compilerTemp1 .= '
				';
		} else {
			$__compilerTemp1 .= '
					' . 'None' . '
				';
		}
		$__compilerTemp1 .= '
				';
		if ($__templater->method($__vars['product'], 'canEditTags', array())) {
			$__compilerTemp1 .= '
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/tags', $__vars['product'], ), true) . '" class="u-concealed" data-xf-click="overlay"
					   data-xf-init="tooltip" title="' . $__templater->filter('Edit tags', array(array('for_attr', array()),), true) . '">
						<i class="fa fa-pencil" aria-hidden="true"></i>
						<span class="u-srOnly">' . 'Edit' . '</span>
					</a>
				';
		}
		$__compilerTemp1 .= '
			</li>
		';
	}
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('
	<ul class="listInline listInline--bullet">
		<li>
			<i class="fa fa-user" aria-hidden="true" title="' . $__templater->filter('Seller', array(array('for_attr', array()),), true) . '"></i>
			<span class="u-srOnly">' . 'Seller' . '</span>

			' . $__templater->func('username_link', array($__vars['product']['User'], false, array(
		'defaultname' => $__vars['product']['username'],
		'class' => 'u-concealed',
	))) . '
		</li>
		<li>
			<i class="fa fa-clock-o" aria-hidden="true" title="' . $__templater->filter('Creation date', array(array('for_attr', array()),), true) . '"></i>
			<span class="u-srOnly">' . 'Creation date' . '</span>

			<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['product']['creation_date'], array(
	))) . '</a>
		</li>
		' . $__compilerTemp1 . '
	</ul>
	<div class="p-description">' . $__templater->escape($__vars['product']['tagline']) . '</div>
');
	$__templater->pageParams['pageDescriptionMeta'] = false;
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
			';
	if ($__vars['product']['product_state'] == 'deleted') {
		$__compilerTemp2 .= '
				<dd class="blockStatus-message blockStatus-message--deleted">
					' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['product']['DeletionLog'],
		), $__vars) . '
				</dd>
				';
	} else if ($__vars['product']['product_state'] == 'moderated') {
		$__compilerTemp2 .= '
				<dd class="blockStatus-message blockStatus-message--moderated">
					' . 'Awaiting approval before being displayed publicly.' . '
				</dd>
			';
	}
	$__compilerTemp2 .= '
		';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
	<dl class="blockStatus blockStatus--standalone">
		<dt>' . 'Status' . '</dt>
		' . $__compilerTemp2 . '
	</dl>
';
	}
	$__finalCompiled .= '

<div class="block">
	';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
					';
	if ($__templater->method($__vars['product'], 'canRate', array(false, )) AND $__vars['xf']['options']['dbtechEcommerceEnableRate']) {
		$__compilerTemp3 .= '
						' . $__templater->button('
							' . 'Leave a rating' . '
						', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/rate', $__vars['product'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp3 .= '
					';
	if ($__templater->method($__vars['product'], 'canReleaseUpdate', array())) {
		$__compilerTemp3 .= '
						' . $__templater->button('Release update', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/release/add', $__vars['product'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp3 .= '

					';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
								';
	if ($__templater->method($__vars['product'], 'canUndelete', array()) AND ($__vars['product']['product_state'] == 'deleted')) {
		$__compilerTemp4 .= '
									' . $__templater->button('
										' . 'Undelete' . '
									', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/undelete', $__vars['product'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp4 .= '
								';
	if ($__templater->method($__vars['product'], 'canApproveUnapprove', array()) AND ($__vars['product']['product_state'] == 'moderated')) {
		$__compilerTemp4 .= '
									' . $__templater->button('
										' . 'Approve' . '
									', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/approve', $__vars['product'], array('t' => $__templater->func('csrf_token', array(), false), ), ), false),
			'class' => 'button--link',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp4 .= '
								';
	if ($__templater->method($__vars['product'], 'canWatch', array())) {
		$__compilerTemp4 .= '
									';
		$__compilerTemp5 = '';
		if ($__vars['product']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp5 .= '
											' . 'Unwatch' . '
											';
		} else {
			$__compilerTemp5 .= '
											' . 'Watch' . '
										';
		}
		$__compilerTemp4 .= $__templater->button('

										' . $__compilerTemp5 . '
									', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/watch', $__vars['product'], ), false),
			'class' => 'button--link',
			'data-xf-click' => 'switch-overlay',
			'data-sk-watch' => 'Watch',
			'data-sk-unwatch' => 'Unwatch',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp4 .= '

								';
	if ($__templater->method($__vars['product'], 'canBookmarkContent', array())) {
		$__compilerTemp4 .= '
									' . $__templater->callMacro('bookmark_macros', 'button', array(
			'content' => $__vars['product'],
			'confirmUrl' => $__templater->func('link', array('dbtech-ecommerce/bookmark', $__vars['product'], ), false),
		), $__vars) . '
								';
	}
	$__compilerTemp4 .= '

								';
	$__compilerTemp6 = '';
	$__compilerTemp6 .= '
													' . '
													';
	if ($__templater->method($__vars['product'], 'canEdit', array())) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/edit', $__vars['product'], ), true) . '" class="menu-linkRow">' . 'Edit product' . '</a>
													';
	}
	$__compilerTemp6 .= '
													';
	if ($__templater->method($__vars['product'], 'canEditIcon', array())) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/edit-icon', $__vars['product'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Edit product icon' . '</a>
													';
	}
	$__compilerTemp6 .= '
													';
	if ($__templater->method($__vars['product'], 'canAddAddOn', array())) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/add-on/add', $__vars['product'], ), true) . '" class="menu-linkRow">' . 'Add add-on product' . '</a>
													';
	}
	$__compilerTemp6 .= '
													';
	if ($__templater->method($__vars['product'], 'canMove', array())) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/move', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Move product' . '</a>
													';
	}
	$__compilerTemp6 .= '
													';
	if ($__templater->method($__vars['product'], 'canChangeParent', array())) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/add-on/move', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Change parent product' . '</a>
													';
	}
	$__compilerTemp6 .= '
													';
	if ($__templater->method($__vars['product'], 'canReassign', array())) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/reassign', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Reassign product' . '</a>
													';
	}
	$__compilerTemp6 .= '
													';
	if ($__templater->method($__vars['product'], 'canChangeDiscussionThread', array())) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/change-thread', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Change discussion thread' . '</a>
													';
	}
	$__compilerTemp6 .= '
													';
	if ($__templater->method($__vars['product'], 'canDelete', array('soft', ))) {
		$__compilerTemp6 .= '
														<a href="' . $__templater->func('link', array('dbtech-ecommerce/delete', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Delete product' . '</a>
													';
	}
	$__compilerTemp6 .= '
													' . '
													';
	if ($__templater->method($__vars['product'], 'canUseInlineModeration', array())) {
		$__compilerTemp6 .= '
														<div class="menu-footer"
															 data-xf-init="inline-mod"
															 data-type="dbtech_ecommerce_product"
															 data-href="' . $__templater->func('link', array('inline-mod', ), true) . '"
															 data-toggle=".js-productInlineModToggle">
															' . $__templater->formCheckBox(array(
		), array(array(
			'class' => 'js-productInlineModToggle',
			'label' => 'Select for moderation',
			'_type' => 'option',
		))) . '
														</div>
														';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__compilerTemp6 .= '
													';
	}
	$__compilerTemp6 .= '
													' . '
												';
	if (strlen(trim($__compilerTemp6)) > 0) {
		$__compilerTemp4 .= '
									<div class="buttonGroup-buttonWrapper">
										' . $__templater->button('&#8226;&#8226;&#8226;', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => $__templater->filter('More options', array(array('for_attr', array()),), false),
		), '', array(
		)) . '
										<div class="menu" data-menu="menu" aria-hidden="true">
											<div class="menu-content">
												<h4 class="menu-header">' . 'More options' . '</h4>
												' . $__compilerTemp6 . '
											</div>
										</div>
									</div>
								';
	}
	$__compilerTemp4 .= '
							';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__compilerTemp3 .= '
						<div class="buttonGroup">
							' . $__compilerTemp4 . '
						</div>
					';
	}
	$__compilerTemp3 .= '
				';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer">
			<div class="block-outer-opposite">
				' . $__compilerTemp3 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceProducts',
		'group' => 'above_main',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['product']['product_fields'],
		'wrapperClass' => 'blockStatus-message',
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer js-productStatusField">
			<div class="blockStatus blockStatus--info">
				' . $__compilerTemp7 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp8 = '';
	$__compilerTemp8 .= '
					' . $__templater->callMacro(null, 'dbtech_ecommerce_product_wrapper_macros::description', array(
		'product' => $__vars['product'],
	), $__vars) . '
					';
	$__compilerTemp9 = '';
	$__compilerTemp9 .= '
												';
	if ($__templater->isTraversable($__vars['product']['Attachments'])) {
		foreach ($__vars['product']['Attachments'] AS $__vars['attachment']) {
			$__compilerTemp9 .= '
													' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
				'attachment' => $__vars['attachment'],
				'canView' => $__templater->method($__vars['product'], 'canViewProductImages', array()),
			), $__vars) . '
												';
		}
	}
	$__compilerTemp9 .= '
											';
	if (strlen(trim($__compilerTemp9)) > 0) {
		$__compilerTemp8 .= '
						<div class="block-row">
							<div class="lbContainer js-productBody"
								 data-xf-init="lightbox">

								<div class="productBody">
									<div class="productBody--main js-lbContainer"
										 data-lb-id="dbtech_ecommerce_product-' . $__templater->escape($__vars['product']['product_id']) . '"
										 data-lb-caption-title="' . $__templater->escape($__vars['product']['title']) . '"
										 data-lb-caption-desc="' . ($__vars['product']['User'] ? $__templater->escape($__vars['product']['User']['username']) : $__templater->escape($__vars['product']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['product']['creation_date'], ), true) . '">

										';
		$__templater->includeCss('attachments.less');
		$__compilerTemp8 .= '
										<ul class="attachmentList productBody--attachments">
											' . $__compilerTemp9 . '
										</ul>
									</div>
								</div>
							</div>
						</div>
					';
	}
	$__compilerTemp8 .= '
				';
	if (strlen(trim($__compilerTemp8)) > 0) {
		$__finalCompiled .= '
		<div class="block-container">
			<div class="block-body">
				' . $__compilerTemp8 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp10 = '';
	$__compilerTemp10 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceProducts',
		'group' => 'below_main',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['product']['product_fields'],
		'wrapperClass' => 'blockStatus-message',
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp10)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after js-productStatusFieldAfter">
			<div class="blockStatus blockStatus--info">
				' . $__compilerTemp10 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
</div>

<div class="block">
	<div class="block-container">
		' . $__templater->callMacro(null, 'dbtech_ecommerce_product_wrapper_macros::tabs', array(
		'product' => $__vars['product'],
		'pageSelected' => $__vars['pageSelected'],
	), $__vars) . '
		' . $__templater->filter($__vars['innerContent'], array(array('raw', array()),), true) . '
	</div>

	';
	$__compilerTemp11 = '';
	$__compilerTemp11 .= '
				' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/' . $__vars['pageSelected'],
		'data' => $__vars['product'],
		'params' => $__vars['licenseParams'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
			';
	if (strlen(trim($__compilerTemp11)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			' . $__compilerTemp11 . '
		</div>
	';
	}
	$__finalCompiled .= '
</div>

';
	$__compilerTemp12 = '';
	$__compilerTemp13 = '';
	$__compilerTemp13 .= '
						';
	if ($__templater->isTraversable($__vars['product']['requirements'])) {
		foreach ($__vars['product']['requirements'] AS $__vars['requirement']) {
			$__compilerTemp13 .= '
							<span class="label label--accent label--fullSize">' . $__templater->escape($__vars['requirement']) . '</span>
						';
		}
	}
	$__compilerTemp13 .= '
					';
	if (strlen(trim($__compilerTemp13)) > 0) {
		$__compilerTemp12 .= '
				<div class="block-body block-row block-row--minor">
					' . $__compilerTemp13 . '
				</div>
			';
	}
	$__compilerTemp14 = '';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array())) {
		$__compilerTemp14 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Last update' . '</dt> <dd>' . $__templater->func('date_dynamic', array($__vars['product']['last_update'], array(
		))) . '</dd></dl>
					';
		if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('dbtechEcommerceAdmin', 'viewDownloadLog', ))) {
			$__compilerTemp14 .= '
						<dl class="pairs pairs--justified"><dt>' . 'Total downloads' . '</dt> <dd>' . $__templater->filter($__vars['product']['full_download_count'], array(array('number', array()),), true) . '</dd></dl>
					';
		}
		$__compilerTemp14 .= '
				';
	}
	$__compilerTemp15 = '';
	if ($__vars['xf']['options']['dbtechEcommerceEnableRate'] AND ($__templater->func('property', array('dbtechEcommerceProductRatingStyle', ), false) == 'stars')) {
		$__compilerTemp15 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Customer rating' . '</dt> <dd>
						' . $__templater->callMacro('rating_macros', 'stars_text', array(
			'rating' => $__vars['product']['rating_avg'],
			'count' => $__vars['product']['rating_count'],
			'rowClass' => 'ratingStarsRow--textBlock',
			'starsClass' => 'ratingStars--smaller',
		), $__vars) . '
					</dd></dl>
				';
	}
	$__compilerTemp16 = '';
	if ($__vars['xf']['options']['dbtechEcommerceEnableRate'] AND ($__templater->func('property', array('dbtechEcommerceProductRatingStyle', ), false) == 'circle')) {
		$__compilerTemp16 .= '
					' . $__templater->callMacro('dbtech_ecommerce_rating_macros', 'stars_circle', array(
			'rating' => $__vars['product']['rating_avg'],
			'count' => $__vars['product']['rating_count'],
			'rowClass' => 'ratingStarsRow--textBlock',
			'starsClass' => 'ratingStars--smaller',
		), $__vars) . '
				';
	}
	$__compilerTemp17 = '';
	$__compilerTemp18 = '';
	$__compilerTemp18 .= '
							<!--[eCommerce:product_information_buttons_top]-->

							';
	if ($__templater->method($__vars['product'], 'hasViewableDiscussion', array())) {
		$__compilerTemp18 .= '
								' . $__templater->button('Join discussion', array(
			'href' => $__templater->func('link', array('threads', $__vars['product']['Discussion'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
							';
	}
	$__compilerTemp18 .= '

							';
	if ($__templater->method($__vars['product'], 'hasViewableSupportForum', array())) {
		$__compilerTemp18 .= '
								' . $__templater->button('Get support', array(
			'href' => $__templater->func('link', array($__templater->method($__vars['product']['SupportForum']['Node'], 'getRoute', array()), $__vars['product']['SupportForum'], ), false),
			'class' => 'button--link button--fullWidth',
		), '', array(
		)) . '
							';
	}
	$__compilerTemp18 .= '

							<!--[eCommerce:product_information_buttons_bottom]-->
						';
	if (strlen(trim($__compilerTemp18)) > 0) {
		$__compilerTemp17 .= '
					<div class="productInfo-buttons">
						' . $__compilerTemp18 . '
					</div>
				';
	}
	$__templater->modifySidebarHtml('productInfo', '
	<div class="block">
		<div class="block-container">
			<h3 class="block-minorHeader">' . 'Product Information' . '</h3>

			' . $__compilerTemp12 . '

			<div class="block-body block-row block-row--minor">
				<!--[eCommerce:product_information_above_info_fields]-->

				' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceProducts',
		'group' => 'above_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['product']['product_fields'],
	), $__vars) . '

				<!--[eCommerce:product_information_below_info_fields]-->

				<dl class="pairs pairs--justified"><dt>' . 'Seller' . '</dt> <dd>' . $__templater->func('username_link', array($__vars['product']['User'], true, array(
	))) . '</dd></dl>
				<dl class="pairs pairs--justified"><dt>' . 'Release date' . '</dt> <dd>' . $__templater->func('date_dynamic', array($__vars['product']['creation_date'], array(
	))) . '</dd></dl>
				' . $__compilerTemp14 . '

				<!--[eCommerce:product_information_below_pairs]-->

				' . $__compilerTemp15 . '

				<!--[eCommerce:product_information_above_info_fields2]-->

				' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceProducts',
		'group' => 'below_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['product']['product_fields'],
	), $__vars) . '

				<!--[eCommerce:product_information_below_info_fields2]-->

				' . $__compilerTemp16 . '

				' . $__compilerTemp17 . '

				<!--[eCommerce:product_information_bottom]-->
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '

';
	$__compilerTemp19 = '';
	$__compilerTemp20 = '';
	$__compilerTemp20 .= '
					';
	if ((!$__vars['license']) OR $__templater->method($__vars['product'], 'canPurchase', array($__vars['license'], ))) {
		$__compilerTemp20 .= '
						<div class="block-body block-row block-row--minor block-row--pricingInfo block-row--pricingInfo' . $__templater->filter($__vars['product']['product_type'], array(array('to_upper', array('ucwords', )),), true) . '">
							';
		if (!$__templater->test($__vars['product']['Costs'], 'empty', array())) {
			$__compilerTemp20 .= '
								';
			if ($__templater->isTraversable($__vars['product']['Costs'])) {
				foreach ($__vars['product']['Costs'] AS $__vars['cost']) {
					$__compilerTemp20 .= '
									';
					if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
						$__compilerTemp20 .= '
										<dl class="pairs pairs--justified pairs--price">
											<dt>' . $__templater->escape($__vars['cost']['length']) . '</dt>
											<dd>
												' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array($__vars['license'], true, true, ))) . '
											</dd>
										</dl>
										';
						if ((!$__templater->method($__vars['cost'], 'isLifetime', array())) AND (!$__vars['license'])) {
							$__compilerTemp20 .= '
											<dl class="pairs pairs--justified pairs--price pairs--renewal">
												<dt>' . 'Renewal cost' . '</dt>
												<dd>
													' . $__templater->escape($__templater->method($__vars['cost'], 'getDigitalRenewalPrice', array(null, true, ))) . '
												</dd>
											</dl>
										';
						}
						$__compilerTemp20 .= '
									';
					} else {
						$__compilerTemp20 .= '
										<dl class="pairs pairs--justified pairs--stock">
											<dt>' . $__templater->escape($__vars['cost']['title']) . '</dt>
											';
						if ((!$__templater->method($__vars['product'], 'hasStockFunctionality', array())) OR $__vars['cost']['stock']) {
							$__compilerTemp20 .= '
												<dd>' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</dd>
											';
						} else if ($__templater->method($__vars['product'], 'hasStockFunctionality', array())) {
							$__compilerTemp20 .= '
												<dd>' . 'Out of stock!' . ' - <span class="u-muted" style="text-decoration:line-through">' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</span></dd>
											';
						}
						$__compilerTemp20 .= '
										</dl>
									';
					}
					$__compilerTemp20 .= '
								';
				}
			}
			$__compilerTemp20 .= '
							';
		}
		$__compilerTemp20 .= '
						</div>
					';
	}
	$__compilerTemp20 .= '

					';
	if (!$__templater->test($__vars['product']['Children'], 'empty', array())) {
		$__compilerTemp20 .= '
						';
		if ($__templater->isTraversable($__vars['product']['Children'])) {
			foreach ($__vars['product']['Children'] AS $__vars['childProduct']) {
				if ($__templater->method($__vars['childProduct'], 'canView', array())) {
					$__compilerTemp20 .= '
							<h3 class="block-minorHeader">' . $__templater->escape($__vars['childProduct']['title']) . '</h3>

							<div class="block-body block-row block-row--minor block-row--childProducts">
								';
					if (!$__templater->test($__vars['childProduct']['Costs'], 'empty', array())) {
						$__compilerTemp20 .= '
									';
						if ($__templater->isTraversable($__vars['childProduct']['Costs'])) {
							foreach ($__vars['childProduct']['Costs'] AS $__vars['cost']) {
								$__compilerTemp20 .= '
										';
								if ($__templater->method($__vars['childProduct'], 'hasLicenseFunctionality', array())) {
									$__compilerTemp20 .= '
											<dl class="pairs pairs--justified pairs--price">
												<dt>' . $__templater->escape($__vars['cost']['length']) . '</dt>
												<dd>' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</dd>
											</dl>
										';
								} else {
									$__compilerTemp20 .= '
											<dl class="pairs pairs--justified pairs--price">
												<dt>' . $__templater->escape($__vars['cost']['title']) . '</dt>
												<dd>' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</dd>
											</dl>
										';
								}
								$__compilerTemp20 .= '
									';
							}
						}
						$__compilerTemp20 .= '
								';
					}
					$__compilerTemp20 .= '
							</div>
						';
				}
			}
		}
		$__compilerTemp20 .= '

					';
	}
	$__compilerTemp20 .= '

					';
	if ($__templater->method($__vars['product'], 'canPurchase', array($__vars['license'], ))) {
		$__compilerTemp20 .= '
						<div class="block-body block-row block-row--minor block-row--purchaseParent">
							';
		$__compilerTemp21 = '';
		if ($__vars['license']) {
			$__compilerTemp21 .= '
									' . 'Renew' . '
								';
		} else if (($__templater->func('count', array($__vars['product']['cost_cache'], ), false) == 1) AND ($__templater->method($__templater->method($__vars['product'], 'getStartingCost', array()), 'getPrice', array()) == 0)) {
			$__compilerTemp21 .= '
									';
			if ($__templater->method($__vars['product'], 'canPurchaseAddOns', array())) {
				$__compilerTemp21 .= '
										' . 'Get free / Purchase add-ons' . '
									';
			} else {
				$__compilerTemp21 .= '
										' . 'Get free' . '
									';
			}
			$__compilerTemp21 .= '
								';
		} else {
			$__compilerTemp21 .= '
									' . 'Purchase' . '
								';
		}
		$__compilerTemp20 .= $__templater->button('
								' . $__compilerTemp21 . '
							', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/purchase', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false),
			'class' => 'button--fullWidth button--cta',
			'icon' => 'purchase',
			'overlay' => 'true',
			'data-cache' => 'false',
		), '', array(
		)) . '
						</div>

						';
		if ($__templater->method($__vars['product'], 'canPurchaseAllAccess', array($__vars['license'], ))) {
			$__compilerTemp20 .= '
							<div class="block-body block-row block-row--minor block-row--purchaseParent">
								' . $__templater->button('
									' . 'Get via All-Access Pass' . '
								', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/purchase/all-access', $__vars['product'], ), false),
				'class' => 'button--fullWidth button--primary',
				'icon' => 'download',
			), '', array(
			)) . '
							</div>
						';
		} else if ($__vars['product']['AllAccessLicense']) {
			$__compilerTemp20 .= '
							<div class="block-body block-row block-row--minor block-row--purchaseParent">
								' . $__templater->button('
									' . 'Owned via All-Access Pass' . '
								', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['product']['AllAccessLicense'], ), false),
				'class' => 'button--fullWidth button--primary is-disabled',
				'icon' => 'download',
			), '', array(
			)) . '
							</div>
						';
		}
		$__compilerTemp20 .= '
					';
	} else if ($__templater->method($__vars['product'], 'canPurchaseAddOns', array($__vars['license'], ))) {
		$__compilerTemp20 .= '
						<div class="block-body block-row block-row--minor block-row--purchaseAddOns">
							' . $__templater->button('
								' . 'Buy add-ons' . '
							', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/purchase/add-ons', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false),
			'icon' => 'purchase',
			'overlay' => 'true',
			'data-cache' => 'false',
		), '', array(
		)) . '
						</div>
					';
	}
	$__compilerTemp20 .= '

					';
	if ($__vars['showCheckout']) {
		$__compilerTemp20 .= '
						<div class="block-body block-row block-row--minor block-row--checkout">
							' . $__templater->button('Checkout', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/checkout', ), false),
			'class' => 'button--cta button--fullWidth',
			'fa' => 'fa-shopping-cart',
		), '', array(
		)) . '
						</div>
					';
	}
	$__compilerTemp20 .= '
				';
	if (strlen(trim($__compilerTemp20)) > 0) {
		$__compilerTemp19 .= '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader">' . 'Pricing information' . '</h3>

				' . $__compilerTemp20 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('pricingInfo', '
	' . $__compilerTemp19 . '
', 'replace');
	$__finalCompiled .= '

';
	$__compilerTemp22 = '';
	$__compilerTemp23 = '';
	$__compilerTemp23 .= '
					';
	$__compilerTemp24 = '';
	$__compilerTemp24 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp24)) > 0) {
		$__compilerTemp23 .= '
						<h3 class="block-minorHeader">' . 'Share this product' . '</h3>
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp24 . '
						</div>
					';
	}
	$__compilerTemp23 .= '
					';
	$__compilerTemp25 = '';
	$__compilerTemp25 .= '
								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy PRODUCT BB code',
		'text' => '[PRODUCT=product, ' . $__vars['product']['product_id'] . '][/PRODUCT]',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp25)) > 0) {
		$__compilerTemp23 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp25 . '
						</div>
					';
	}
	$__compilerTemp23 .= '
				';
	if (strlen(trim($__compilerTemp23)) > 0) {
		$__compilerTemp22 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp23 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('shareProduct', '
	' . $__compilerTemp22 . '
', 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar1735b92d50eebe749ea7d489d993eac9', $__templater->widgetPosition('dbtech_ecommerce_product_sidebar', array(
		'product' => $__vars['product'],
	)), 'replace');
	return $__finalCompiled;
}
);