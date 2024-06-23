<?php
// FROM HASH: 93b0eca1e8f45fa824e1cf4bac18e3dd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('dbtech_ecommerce_product_bb_code.less');
	$__finalCompiled .= '

<div class="embeddedProduct">

	<div class="embeddedProduct-info fauxBlockLink">
		<div class="contentRow">
			<div class="contentRow-figure">
				' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['product'], 'm', $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ), false), ), true) . '
			</div>
			<div class="contentRow-main">
				<h4 class="contentRow-title">
					<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ), true) . '" class="fauxBlockLink-blockLink u-cloaked">
						' . $__templater->escape($__vars['product']['title']) . '
					</a>
				</h4>
				<div class="contentRow-lesser p-description">
					<ul class="listInline listInline--bullet is-structureList">
						<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('Seller', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['product']['User'], false, array(
		'defaultname' => $__vars['product']['username'],
		'class' => 'u-concealed',
	))) . '</li>
						';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array())) {
		$__finalCompiled .= '
							<li>' . $__templater->fontAwesome('fa-clock', array(
			'title' => $__templater->filter('Last update', array(array('for_attr', array()),), false),
		)) . ' ' . $__templater->func('date_dynamic', array($__vars['product']['last_update'], array(
		))) . '</li>
							';
		if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('dbtechEcommerceAdmin', 'viewDownloadLog', ))) {
			$__finalCompiled .= '
								<li>' . $__templater->fontAwesome('fa-download', array(
				'title' => $__templater->filter('Total downloads', array(array('for_attr', array()),), false),
			)) . ' ' . $__templater->filter($__vars['product']['full_download_count'], array(array('number_short', array()),), true) . '</li>
							';
		}
		$__finalCompiled .= '

							';
		if ($__vars['product']['LatestVersion']) {
			$__finalCompiled .= '
								<li>' . $__templater->fontAwesome('fa-upload', array(
				'title' => $__templater->filter('Latest version', array(array('for_attr', array()),), false),
			)) . ' ' . $__templater->escape($__vars['product']['LatestVersion']['version_string']) . '</li>
							';
		}
		$__finalCompiled .= '
						';
	} else {
		$__finalCompiled .= '
							<li>' . $__templater->fontAwesome('fa-clock', array(
			'title' => $__templater->filter('Released', array(array('for_attr', array()),), false),
		)) . ' ' . $__templater->func('date_dynamic', array($__vars['product']['creation_date'], array(
		))) . '</li>
						';
	}
	$__finalCompiled .= '

						' . '
					</ul>
				</div>
				';
	if (!$__templater->test($__vars['product']['tagline'], 'empty', array())) {
		$__finalCompiled .= '
					<div class="contentRow-snippet">
						' . $__templater->escape($__vars['product']['tagline']) . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				';
	if ($__templater->method($__vars['product'], 'canViewProductImages', array()) AND $__vars['product']['attach_count']) {
		$__compilerTemp1 .= '
					';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
										';
		if ($__templater->isTraversable($__vars['product']['Attachments'])) {
			foreach ($__vars['product']['Attachments'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail']) {
					$__compilerTemp2 .= '
											<div class="embeddedProduct-thumbList-item">
												<a href="' . $__templater->func('link', array('attachments', $__vars['attachment'], ), true) . '" target="_blank" class="js-lbImage">
													<img src="' . $__templater->escape($__vars['attachment']['thumbnail_url']) . '" alt="' . $__templater->escape($__vars['attachment']['filename']) . '" loading="lazy" />
												</a>
											</div>
										';
				}
			}
		}
		$__compilerTemp2 .= '
									';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__compilerTemp1 .= '
						<div class="lbContainer js-productBody"
							 data-xf-init="lightbox">

							<div class="productBody">
								<div class="embeddedProduct-thumbList js-lbContainer"
									 data-lb-id="dbtech_ecommerce_product-' . $__templater->escape($__vars['product']['product_id']) . '"
									 data-lb-caption-title="' . $__templater->escape($__vars['product']['title']) . '"
									 data-lb-caption-desc="' . ($__vars['product']['User'] ? $__templater->escape($__vars['product']['User']['username']) : $__templater->escape($__vars['product']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['product']['creation_date'], ), true) . '">

									' . $__templater->callMacro('lightbox_macros', 'setup', array(
				'canViewAttachments' => true . '}',
			), $__vars) . '

									' . $__compilerTemp2 . '

									';
			$__compilerTemp3 = $__templater->func('range', array(1, 10, ), false);
			if ($__templater->isTraversable($__compilerTemp3)) {
				foreach ($__compilerTemp3 AS $__vars['placeholder']) {
					$__compilerTemp1 .= '
										<div class="embeddedProduct-thumbList-item embeddedProduct-thumbList-item--placeholder"></div>
									';
				}
			}
			$__compilerTemp1 .= '
								</div>
							</div>
						</div>
					';
		}
		$__compilerTemp1 .= '
				';
	}
	$__compilerTemp1 .= '
			';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="embeddedProduct-container">
			' . $__compilerTemp1 . '
		</div>
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);