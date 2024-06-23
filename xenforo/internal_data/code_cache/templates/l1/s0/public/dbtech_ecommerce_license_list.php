<?php
// FROM HASH: 548d85c95e6827a67eb592f2b1754060
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
		));
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Your licenses');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Licenses owned by ' . $__templater->escape($__vars['user']['username']) . '');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 = '';
		if ($__vars['xf']['options']['dbtechEcommerceEnableApi']) {
			$__compilerTemp1 .= '
		' . $__templater->button('
			' . 'View API key' . '
		', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/account/api-key', ), false),
				'icon' => 'config',
				'overlay' => 'true',
			), '', array(
			)) . '
	';
		}
		$__compilerTemp2 = '';
		if ($__vars['hasExpired']) {
			$__compilerTemp2 .= '
		' . $__templater->button('
			' . 'Renew licenses' . '
		', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/renew', ), false),
				'class' => 'button--cta',
				'icon' => 'refresh',
				'overlay' => 'true',
			), '', array(
			)) . '
	';
		}
		$__compilerTemp3 = '';
		if ($__vars['xf']['visitor']['dbtech_ecommerce_is_distributor']) {
			$__compilerTemp3 .= '
		' . $__templater->button('
			' . 'Generate license' . '
		', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/generate', ), false),
				'class' => 'button--cta',
				'icon' => 'add',
				'overlay' => 'true',
			), '', array(
			)) . '
	';
		}
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '
	' . $__compilerTemp2 . '
	' . $__compilerTemp3 . '
');
	}
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['tree'], 'empty', array())) {
		$__finalCompiled .= '

	';
		$__templater->includeCss('structured_list.less');
		$__finalCompiled .= '
	';
		$__templater->includeCss('dbtech_ecommerce.less');
		$__finalCompiled .= '

	<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="dbtech_ecommerce_license" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
		<div class="block-outer">';
		$__compilerTemp4 = '';
		$__compilerTemp5 = '';
		$__compilerTemp5 .= '
							';
		if ($__vars['canInlineMod']) {
			$__compilerTemp5 .= '
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							';
		}
		$__compilerTemp5 .= '
						';
		if (strlen(trim($__compilerTemp5)) > 0) {
			$__compilerTemp4 .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp5 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= $__templater->func('trim', array('

			' . $__compilerTemp4 . '

		'), false) . '</div>
		
		<div class="block-container">
			<div class="block-body">
				<div class="structItemContainer">
					' . $__templater->callMacro('dbtech_ecommerce_license_list_macros', 'license_list', array(
			'children' => $__vars['tree'],
			'allowInlineMod' => $__vars['canInlineMod'],
		), $__vars) . '
				</div>
			</div>
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">
		';
		if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
			' . 'You have not purchased any licenses yet.' . '
		';
		} else {
			$__finalCompiled .= '
			' . '' . $__templater->escape($__vars['user']['username']) . ' has not purchased any licenses yet.' . '
		';
		}
		$__finalCompiled .= '
	</div>
';
	}
	return $__finalCompiled;
}
);