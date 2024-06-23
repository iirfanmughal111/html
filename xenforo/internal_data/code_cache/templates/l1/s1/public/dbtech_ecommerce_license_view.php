<?php
// FROM HASH: 191216afaf8e2f36a10f2bb1c4b0d2d4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['license']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
		));
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped('Your licenses'), $__templater->func('link', array('dbtech-ecommerce/licenses', $__vars['license']['User'], ), false), array(
		));
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped('Licenses owned by ' . $__templater->escape($__vars['license']['User']['username']) . ''), $__templater->func('link', array('dbtech-ecommerce/licenses', $__vars['license']['User'], ), false), array(
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['license']['Product'], 'escaped', ), true) . $__templater->escape($__vars['license']['Product']['title'])) . ' (' . $__templater->escape($__vars['license']['license_key']) . ')');
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['license'], 'isLifetime', array())) {
		$__compilerTemp1 .= '
		' . 'Never' . '
	';
	} else {
		$__compilerTemp1 .= '
		' . $__templater->func('date_dynamic', array($__vars['license']['expiry_date'], array(
		))) . '
	';
	}
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('
	' . 'Expiry date' . $__vars['xf']['language']['label_separator'] . '
	' . $__compilerTemp1 . '
');
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped('' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['license']['Product'], ), true) . $__templater->escape($__vars['license']['Product']['title'])) . ' (' . $__templater->escape($__vars['license']['license_key']) . ')');
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if (!$__templater->test($__vars['license']['Product']['LatestVersion'], 'empty', array())) {
		$__compilerTemp2 .= '
		' . $__templater->button('
			' . 'Download' . '
		', array(
			'href' => ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/releases/download', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), false) : ''),
			'data-xf-click' => 'overlay',
			'class' => 'button--cta ' . ($__vars['isValid'] ? '' : 'is-disabled'),
			'icon' => 'download',
		), '', array(
		)) . '

		' . $__templater->button('
			' . 'View all releases' . '
		', array(
			'href' => ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/releases', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), false) : '') . '#releases',
			'class' => ($__vars['isValid'] ? '' : 'is-disabled'),
			'icon' => 'search',
		), '', array(
		)) . '
	';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['license']['Product'], 'canPurchase', array($__vars['license'], ))) {
		$__compilerTemp3 .= '
		' . $__templater->button('
			' . 'Renew license' . '
		', array(
			'href' => ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/purchase', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), false) : ''),
			'class' => ($__vars['isValid'] ? '' : 'is-disabled'),
			'icon' => 'purchase',
			'overlay' => 'true',
			'data-cache' => 'false',
		), '', array(
		)) . '
	';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['license']['Product'], 'canPurchaseAddOns', array($__vars['license'], ))) {
		$__compilerTemp4 .= '
		' . $__templater->button('
			' . 'Buy add-ons' . '
		', array(
			'href' => ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/purchase/add-ons', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), false) : ''),
			'class' => ($__vars['isValid'] ? '' : 'is-disabled'),
			'icon' => 'purchase',
			'overlay' => 'true',
			'data-cache' => 'false',
		), '', array(
		)) . '
	';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__compilerTemp2 . '
	' . $__compilerTemp3 . '
	' . $__compilerTemp4 . '
');
	$__finalCompiled .= '

';
	if ($__vars['validationWarnings']) {
		$__finalCompiled .= '
	<div class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
		<ul class="listPlain">
			';
		if ($__templater->isTraversable($__vars['validationWarnings'])) {
			foreach ($__vars['validationWarnings'] AS $__vars['warning']) {
				$__finalCompiled .= '
				<li>' . $__templater->escape($__vars['warning']) . '</li>
			';
			}
		}
		$__finalCompiled .= '
		</ul>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['validationErrors']) {
		$__finalCompiled .= '
	<div class="block-rowMessage block-rowMessage--error block-rowMessage--iconic">
		' . 'The following errors must be resolved before continuing' . $__vars['xf']['language']['label_separator'] . '
		<ul>
			';
		if ($__templater->isTraversable($__vars['validationErrors'])) {
			foreach ($__vars['validationErrors'] AS $__vars['error']) {
				$__finalCompiled .= '
				<li>' . $__templater->escape($__vars['error']) . '</li>
			';
			}
		}
		$__finalCompiled .= '
		</ul>
	</div>
';
	}
	$__finalCompiled .= '

';
	if (($__templater->method($__vars['license'], 'isExpired', array()) AND (!$__templater->test($__vars['license']['Product']['LatestVersion'], 'empty', array()) AND ($__vars['license']['Product']['LatestVersion']['release_date'] > $__vars['license']['expiry_date'])))) {
		$__finalCompiled .= '
	<div class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
		' . 'Your license expired <b>' . $__templater->func('date_time', array($__vars['license']['expiry_date'], ), true) . '</b> and the latest version was released <b>' . $__templater->func('date_time', array($__vars['license']['Product']['LatestVersion']['release_date'], ), true) . '</b>.<br />
If you want to download the latest version, please renew your license.' . '
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['license'], 'canEdit', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp5 = '';
		if ($__vars['license']['Product']['parent_product_id']) {
			$__compilerTemp5 .= '
			';
			if ($__vars['license']['parent_license_id']) {
				$__compilerTemp5 .= '
				';
				$__compilerTemp6 = '';
				$__compilerTemp6 .= '
								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'group' => 'list',
					'type' => 'dbtechEcommerceLicenses',
					'set' => $__vars['license']['license_fields'],
					'namePrefix' => 'license_fields',
					'valueClass' => 'formRow',
				), $__vars) . '
								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'group' => 'info',
					'type' => 'dbtechEcommerceLicenses',
					'set' => $__vars['license']['license_fields'],
					'namePrefix' => 'license_fields',
					'valueClass' => 'formRow',
				), $__vars) . '
							';
				if (strlen(trim($__compilerTemp6)) > 0) {
					$__compilerTemp5 .= '
					<div class="block-container">
						<h2 class="block-header">' . 'License info' . '</h2>
						<div class="block-body">
							' . $__compilerTemp6 . '
						</div>
					</div>
				';
				}
				$__compilerTemp5 .= '
			';
			} else {
				$__compilerTemp5 .= '
				<div class="block-container">
					<h2 class="block-header">' . 'License info' . '</h2>
					<div class="block-body">
						';
				$__compilerTemp7 = array(array(
					'value' => '0',
					'label' => $__templater->filter('None', array(array('parens', array()),), true),
					'_type' => 'option',
				));
				$__compilerTemp7 = $__templater->mergeChoiceOptions($__compilerTemp7, $__vars['parentLicenses']);
				$__compilerTemp5 .= $__templater->formSelectRow(array(
					'name' => 'parent_license',
				), $__compilerTemp7, array(
					'label' => 'Parent license',
					'explain' => 'Add-on product licenses must be assigned to a valid parent license.',
				)) . '
					</div>

					' . $__templater->formSubmitRow(array(
					'icon' => 'save',
				), array(
				)) . '
				</div>
			';
			}
			$__compilerTemp5 .= '
		';
		} else {
			$__compilerTemp5 .= '
			';
			$__compilerTemp8 = '';
			$__compilerTemp8 .= '
							' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
				'type' => 'dbtechEcommerceLicenses',
				'set' => $__vars['license']['license_fields'],
				'namePrefix' => 'license_fields',
				'valueClass' => 'formRow',
			), $__vars) . '
						';
			if (strlen(trim($__compilerTemp8)) > 0) {
				$__compilerTemp5 .= '
				<div class="block-container">
					<h2 class="block-header">' . 'License info' . '</h2>
					<div class="block-body">
						' . $__compilerTemp8 . '
					</div>

					' . $__templater->formSubmitRow(array(
					'sticky' => 'true',
					'icon' => 'save',
				), array(
				)) . '
				</div>

			';
			}
			$__compilerTemp5 .= '
		';
		}
		$__finalCompiled .= $__templater->form('
		' . $__compilerTemp5 . '
	', array(
			'action' => $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['license'], ), false),
			'class' => 'block',
			'ajax' => 'true',
		)) . '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['license']['Product'], 'hasDownloadFunctionality', array()) AND $__templater->func('property', array('dbtechEcommerceLicenseReleaseList', ), false)) {
		$__finalCompiled .= '
	';
		if (!$__templater->test($__vars['groupedDownloads'], 'empty', array())) {
			$__finalCompiled .= '
		';
			if ($__templater->isTraversable($__vars['license']['Product']['product_versions'])) {
				foreach ($__vars['license']['Product']['product_versions'] AS $__vars['version'] => $__vars['text']) {
					$__finalCompiled .= '
			';
					$__vars['downloads'] = $__vars['groupedDownloads'][$__vars['version']];
					$__finalCompiled .= '

			';
					if (!$__templater->test($__vars['downloads'], 'empty', array())) {
						$__finalCompiled .= '
				';
						$__compilerTemp9 = $__vars;
						$__compilerTemp9['_noWrap'] = true;
						$__compilerTemp9['_header'] = $__vars['text'];
						$__compilerTemp9['downloads'] = $__vars['downloads'];
						$__finalCompiled .= $__templater->includeTemplate('dbtech_ecommerce_product_releases', $__compilerTemp9) . '
			';
					}
					$__finalCompiled .= '
		';
				}
			}
			$__finalCompiled .= '
	';
		} else if (!$__templater->test($__vars['downloads'], 'empty', array())) {
			$__finalCompiled .= '
		';
			$__compilerTemp10 = $__vars;
			$__compilerTemp10['_noWrap'] = true;
			$__finalCompiled .= $__templater->includeTemplate('dbtech_ecommerce_product_releases', $__compilerTemp10) . '
	';
		} else {
			$__finalCompiled .= '
		<div class="blockMessage">' . 'There are no available downloads for this license.' . '</div>
	';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['isValid'] AND (($__vars['license']['Product']['product_type'] == 'dbtech_ecommerce_key') AND !$__templater->test($__vars['license']['SerialKey'], 'empty', array()))) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h2 class="block-header">' . 'Serial key' . '</h2>
			<div class="block-body">
				' . $__templater->formInfoRow('
					<strong><code class="js-copyTarget">' . $__templater->escape($__vars['license']['SerialKey']['serial_key']) . '</code></strong>
					' . $__templater->button('', array(
			'icon' => 'copy',
			'data-xf-init' => 'copy-to-clipboard',
			'data-copy-target' => '.js-copyTarget',
			'data-success' => 'Serial key copied to clipboard.',
			'class' => 'button--link',
		), '', array(
		)) . '
				', array(
			'rowtype' => 'confirm',
		)) . '
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);