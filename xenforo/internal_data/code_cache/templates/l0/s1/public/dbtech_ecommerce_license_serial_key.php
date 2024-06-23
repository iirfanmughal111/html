<?php
// FROM HASH: ed71030070009d94afd705c0f9e3a844
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Serial key');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['license']['Product']['LatestVersion'], 'empty', array())) {
		$__compilerTemp1 .= '
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
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['license']['Product'], 'canPurchase', array($__vars['license'], ))) {
		$__compilerTemp2 .= '
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
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['license']['Product'], 'canPurchaseAddOns', array($__vars['license'], ))) {
		$__compilerTemp3 .= '
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
	' . $__compilerTemp1 . '
	' . $__compilerTemp2 . '
	' . $__compilerTemp3 . '
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
	if ($__vars['isValid']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
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