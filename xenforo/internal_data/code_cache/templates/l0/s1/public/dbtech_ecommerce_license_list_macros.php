<?php
// FROM HASH: 31e809413349920486ba3538fc3d027d
return array(
'macros' => array('license_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'depth' => '1',
		'allowInlineMod' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['child']) {
			$__finalCompiled .= '
		' . $__templater->callMacro(null, 'license_list_entry', array(
				'license' => $__vars['child']['record'],
				'children' => $__vars['child']['children'],
				'depth' => $__vars['depth'],
				'allowInlineMod' => $__vars['allowInlineMod'],
			), $__vars) . '
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'license_list_entry' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'license' => '!',
		'children' => '!',
		'allowInlineMod' => true,
		'chooseName' => '',
		'extraInfo' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__vars['isValid'] = $__templater->method($__vars['license'], 'hasValidLicenseFields', array('user', ));
	$__finalCompiled .= '

	<div class="structItem structItem--product ' . ($__templater->method($__vars['license'], 'isIgnored', array()) ? 'is-ignored' : '') . ((($__vars['license']['license_state'] == 'moderated') OR ($__vars['license']['license_state'] == 'awaiting_payment')) ? 'is-moderated' : '') . (($__vars['license']['license_state'] == 'deleted') ? 'is-deleted' : '') . ' js-inlineModContainer js-ProductListItem-' . $__templater->escape($__vars['license']['license_id']) . '" data-author="' . ($__templater->escape($__vars['license']['User']['username']) ?: $__templater->escape($__vars['license']['username'])) . '">
		';
	if ($__vars['chooseName']) {
		$__finalCompiled .= '
			<div class="structItem-cell structItem-cell--checkbox">
				' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'name' => $__vars['chooseName'] . '[]',
			'value' => $__vars['license']['license_key'],
			'class' => 'js-chooseItem',
			'_type' => 'option',
		))) . '
			</div>
		';
	}
	$__finalCompiled .= '
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded">
			<div class="structItem-iconContainer">
				' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['license']['Product'], 's', $__templater->func('link', array('dbtech-ecommerce', $__vars['license']['Product'], ), false), ), true) . '
			</div>
		</div>
		<div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['license']['license_state'] == 'moderated') {
		$__compilerTemp1 .= '
							<li>
								<i class="structItem-status structItem-status--moderated" aria-hidden="true" title="' . $__templater->filter('Awaiting approval', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Awaiting approval' . '</span>
							</li>
						';
	} else if ($__vars['license']['license_state'] == 'awaiting_payment') {
		$__compilerTemp1 .= '
							<li>
								<i class="structItem-status structItem-status--attention" aria-hidden="true" title="' . $__templater->filter('Awaiting payment', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Awaiting payment' . '</span>
							</li>
						';
	} else if ($__vars['license']['license_state'] == 'deleted') {
		$__compilerTemp1 .= '
							<li>
								<i class="structItem-status structItem-status--deleted" aria-hidden="true" title="' . $__templater->filter('Deleted', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Deleted' . '</span>
							</li>
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
				<ul class="structItem-statuses">
					' . $__compilerTemp1 . '
				</ul>
			';
	}
	$__finalCompiled .= '

			<div class="structItem-title">
				';
	if ($__vars['license']['license_state'] == 'awaiting_payment') {
		$__finalCompiled .= '
					<span class="label label--orange label--smallest label--aligner">
						' . 'Awaiting payment' . '
					</span>
				';
	} else {
		$__finalCompiled .= '
					';
		if ($__templater->method($__vars['license'], 'isAssigned', array())) {
			$__finalCompiled .= '

						';
			if (!$__vars['isValid']) {
				$__finalCompiled .= '
							<span class="label label--red label--smallest label--aligner">
								<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['license'], ), true) . '" class="u-concealed">' . 'Missing license information' . '</a>
							</span>
						';
			}
			$__finalCompiled .= '

						';
			if (!$__templater->method($__vars['license'], 'hasRequiredUserGroups', array())) {
				$__finalCompiled .= '
							<span class="label label--red label--smallest label--aligner">
								<a href="' . $__templater->func('link', array('account/upgrades', ), true) . '" class="u-concealed">' . 'All-Access Pass Expired' . '</a>
							</span>
						';
			}
			$__finalCompiled .= '

						';
			if ($__vars['license']['LatestDownloaded']) {
				$__finalCompiled .= '
							';
				if ($__vars['license']['LatestDownloaded']['release_date'] < $__vars['license']['Product']['LatestVersion']['release_date']) {
					$__finalCompiled .= '
								<span class="label label--orange label--smallest label--aligner">
									' . 'Update available' . '
								</span>
							';
				}
				$__finalCompiled .= '
						';
			} else if ($__vars['license']['Product']['LatestVersion']) {
				$__finalCompiled .= '
							<span class="label label--green label--smallest label--aligner">
								' . 'Not yet downloaded' . '
							</span>
						';
			}
			$__finalCompiled .= '

						';
			if ($__vars['license']['expiry_date'] AND ($__vars['license']['expiry_date'] < $__vars['xf']['time'])) {
				$__finalCompiled .= '
							<span class="label label--red label--smallest label--aligner">
								<a href="' . ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/purchase', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), true) : '') . '" class="u-concealed" data-xf-click="overlay">
									' . 'Expired' . '
								</a>
							</span>
						';
			}
			$__finalCompiled .= '
					';
		} else {
			$__finalCompiled .= '
						<span class="label label--red label--smallest label--aligner">
							' . 'Unassigned' . '
						</span>
					';
		}
		$__finalCompiled .= '
				';
	}
	$__finalCompiled .= '

				';
	if ($__vars['license']['Product']['prefix_id']) {
		$__finalCompiled .= '
					' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['license']['Product'], 'html', '', ), true) . '
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['license']['license_state'] != 'visible') {
		$__finalCompiled .= '
					' . $__templater->escape($__vars['license']['full_title']) . '
				';
	} else {
		$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['license'], ), true) . '" data-tp-primary="on">' . $__templater->escape($__vars['license']['full_title']) . '</a>
				';
	}
	$__finalCompiled .= '
				';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceLicenses',
		'group' => 'list',
		'set' => $__vars['license']['license_fields'],
		'wrapperClass' => 'structItem-parts',
		'valueClass' => 'pairs pairs--inline pairs--fixedSmall',
	), $__vars) . '
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
					-
					' . $__compilerTemp2 . '
				';
	}
	$__finalCompiled .= '
			</div>

			<div class="structItem-minor">
				';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
						';
	if ($__vars['extraInfo']) {
		$__compilerTemp3 .= '
							<li>' . $__templater->escape($__vars['extraInfo']) . '</li>
						';
	}
	$__compilerTemp3 .= '
						';
	if ($__vars['allowInlineMod'] AND $__templater->method($__vars['license']['Product'], 'canUseInlineModeration', array())) {
		$__compilerTemp3 .= '
							<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['license']['license_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'_type' => 'option',
		))) . '</li>
						';
	}
	$__compilerTemp3 .= '
					';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
					<ul class="structItem-extraInfo">
					' . $__compilerTemp3 . '
					</ul>
				';
	}
	$__finalCompiled .= '

				';
	if ($__vars['license']['license_state'] == 'deleted') {
		$__finalCompiled .= '
					';
		if ($__vars['extraInfo']) {
			$__finalCompiled .= '<span class="structItem-extraInfo">' . $__templater->escape($__vars['extraInfo']) . '</span>';
		}
		$__finalCompiled .= '

					' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['license']['DeletionLog'],
		), $__vars) . '
				';
	} else {
		$__finalCompiled .= '
					<ul class="structItem-parts">
						<li><a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/info', $__vars['license'], ), true) . '" data-xf-click="overlay" data-tp-clickable="on">' . $__templater->escape($__vars['license']['license_key']) . '</a></li>
						<li>' . 'Purchased' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['license']['purchase_date'], array(
		))) . '</li>
						<li>' . 'Expiry date' . $__vars['xf']['language']['label_separator'] . '
							';
		if ($__templater->method($__vars['license'], 'isLifetime', array())) {
			$__finalCompiled .= '
								' . 'Never' . '
							';
		} else {
			$__finalCompiled .= '
								<a href="' . ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/purchase', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), true) : '') . '" class="u-concealed" data-xf-click="overlay">
									' . $__templater->func('date_dynamic', array($__vars['license']['expiry_date'], array(
			))) . '
								</a>
							';
		}
		$__finalCompiled .= '
						</li>
					</ul>
				';
	}
	$__finalCompiled .= '
			</div>

			';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
						';
	if ($__templater->isTraversable($__vars['license']['Product']['requirements'])) {
		foreach ($__vars['license']['Product']['requirements'] AS $__vars['requirement']) {
			$__compilerTemp4 .= '
							<span class="label label--accent label--smallest">' . $__templater->escape($__vars['requirement']) . '</span>
						';
		}
	}
	$__compilerTemp4 .= '
					';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__finalCompiled .= '
				<div class="structItem-productRequirements">
					' . $__compilerTemp4 . '
				</div>
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['license']['license_state'] != 'deleted') {
		$__finalCompiled .= '
				<div class="structItem-productTagLine">' . $__templater->escape($__vars['license']['Product']['tagline']) . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
		<div class="structItem-cell structItem-cell--productMeta">
			';
	if ($__templater->method($__vars['license']['Product'], 'hasDownloadFunctionality', array())) {
		$__finalCompiled .= '
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--lastUpdate">
					<dt>' . 'Downloaded' . '</dt>
					<dd>
						';
		if ($__vars['license']['LatestDownloaded']) {
			$__finalCompiled .= '
							';
			if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('dbtechEcommerceAdmin', 'viewDownloadLog', ))) {
				$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/downloads', null, array('criteria' => array('License' => array('license_key' => $__vars['license']['license_key'], ), ), ), ), true) . '" class="u-concealed">' . $__templater->escape($__vars['license']['LatestDownloaded']['version_string']) . '</a>
							';
			} else {
				$__finalCompiled .= '
								<span class="u-concealed">' . $__templater->escape($__vars['license']['LatestDownloaded']['version_string']) . '</span>
							';
			}
			$__finalCompiled .= '
						';
		} else {
			$__finalCompiled .= '
							' . 'N/A' . '
						';
		}
		$__finalCompiled .= '
					</dd>
				</dl>
				';
		if ($__vars['license']['Product']['LatestVersion']) {
			$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--lastUpdate">
						<dt>' . 'Latest' . '</dt>
						<dd>
							<a href="' . $__templater->func('link', array('dbtech-ecommerce/release', $__vars['license']['Product']['LatestVersion'], array('license_key' => $__vars['license']['license_key'], ), ), true) . '" data-xf-click="overlay" class="u-concealed">' . $__templater->escape($__vars['license']['Product']['LatestVersion']['version_string']) . '</a></dd>
					</dl>
				';
		} else {
			$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--lastUpdate">
						<dt>' . 'Released' . '</dt>
						<dd><a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['license']['Product'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['license']['Product']['creation_date'], array(
			))) . '</a></dd>
					</dl>
				';
		}
		$__finalCompiled .= '
			';
	} else {
		$__finalCompiled .= '
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--lastUpdate">
					<dt>' . 'Released' . '</dt>
					<dd><a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['license']['Product'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['license']['Product']['creation_date'], array(
		))) . '</a></dd>
				</dl>
			';
	}
	$__finalCompiled .= '

			' . $__templater->callMacro(null, 'license_buttons', array(
		'license' => $__vars['license'],
		'isValid' => $__vars['isValid'],
	), $__vars) . '
		</div>
	</div>

	' . $__templater->callMacro(null, 'license_list', array(
		'children' => $__vars['children'],
		'depth' => ($__vars['depth'] + 1),
		'allowInlineMod' => $__vars['allowInlineMod'],
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'license_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'license' => '!',
		'withMeta' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['license']['Product'], 'xxs', $__templater->func('link', array('dbtech-ecommerce', $__vars['license'], ), false), ), true) . '
		</div>
		<div class="contentRow-main contentRow-main--close">
			<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['license']['Product'], ), true) . '">' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['license']['Product'], ), true) . $__templater->escape($__vars['license']['title']) . '</a>
			<div class="contentRow-lesser">' . $__templater->escape($__vars['license']['Product']['tagline']) . '</div>
			';
	if ($__vars['withMeta']) {
		$__finalCompiled .= '
				<div class="contentRow-minor contentRow-minor--smaller">
					<ul class="listInline listInline--bullet">
						<li>' . $__templater->escape($__vars['license']['license_key']) . '</li>
						<li>' . 'Purchased' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['license']['purchase_date'], array(
		))) . '</li>
					</ul>
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'license_buttons' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'license' => '!',
		'isValid' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="paddedButtonGroup">
		';
	if ($__templater->method($__vars['license']['Product'], 'hasDownloadFunctionality', array()) AND (!$__templater->test($__vars['license']['Product']['LatestVersion'], 'empty', array()) AND $__templater->method($__vars['license']['Product'], 'canDownload', array()))) {
		$__finalCompiled .= '
			' . $__templater->button('
				' . 'Download' . '
			', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/releases/download', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), false),
			'data-xf-click' => 'overlay',
			'class' => 'button--cta button--fullWidth ' . (($__vars['isValid'] AND $__templater->method($__vars['license'], 'hasRequiredUserGroups', array())) ? '' : 'is-disabled'),
			'icon' => 'download',
		), '', array(
		)) . '
		';
	}
	$__finalCompiled .= '

		' . $__templater->button('
			' . 'Edit license' . '
		', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['license'], ), false),
		'class' => 'button--fullWidth ' . ($__templater->method($__vars['license'], 'hasRequiredUserGroups', array()) ? '' : 'is-disabled'),
		'icon' => 'edit',
	), '', array(
	)) . '

		';
	if (($__vars['license']['Product']['product_type'] == 'dbtech_ecommerce_key') AND !$__templater->test($__vars['license']['SerialKey'], 'empty', array())) {
		$__finalCompiled .= '
			' . $__templater->button('
				' . $__templater->fontAwesome('fa-key', array(
		)) . '
				' . 'View serial key' . '
			', array(
			'href' => ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/licenses/serial-key', $__vars['license'], ), false) : ''),
			'data-xf-click' => 'overlay',
			'class' => 'button--cta button--fullWidth ' . (($__vars['isValid'] AND $__templater->method($__vars['license'], 'hasRequiredUserGroups', array())) ? '' : 'is-disabled'),
		), '', array(
		)) . '
		';
	}
	$__finalCompiled .= '

		';
	if ($__templater->method($__vars['license']['Product'], 'canPurchaseAddOns', array($__vars['license'], ))) {
		$__finalCompiled .= '
			' . $__templater->button('
				' . 'Buy add-ons' . '
			', array(
			'href' => ($__vars['isValid'] ? $__templater->func('link', array('dbtech-ecommerce/purchase/add-ons', $__vars['license']['Product'], array('license_key' => $__vars['license']['license_key'], ), ), false) : ''),
			'class' => (($__vars['isValid'] AND $__templater->method($__vars['license'], 'hasRequiredUserGroups', array())) ? '' : 'is-disabled') . ' button--primary button--fullWidth',
			'icon' => 'purchase',
			'overlay' => 'true',
			'data-cache' => 'false',
		), '', array(
		)) . '
		';
	}
	$__finalCompiled .= '
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);