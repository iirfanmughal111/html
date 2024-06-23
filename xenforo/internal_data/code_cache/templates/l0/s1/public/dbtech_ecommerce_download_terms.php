<?php
// FROM HASH: e6eaeebbfea6ff475631daf109351894
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumbs($__templater->method($__vars['product']['Category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['product']['title'])), $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Releases'), $__templater->func('link', array('dbtech-ecommerce/releases', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['download']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['download']['title']));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<h3 class="block-formSectionHeader">
			' . $__templater->escape($__vars['xf']['visitor']['dbtech_ecommerce_terms']['title']) . '
			<span class="block-desc">
				<b>' . 'Last updated' . $__vars['xf']['language']['label_separator'] . '</b> ' . $__templater->func('date_time', array($__vars['xf']['visitor']['dbtech_ecommerce_terms']['modified_date'], ), true) . '
			</span>
		</h3>
		<div class="block-body">
			<div class="block-row" style="max-height:25vh; overflow:auto">
				' . $__templater->includeTemplate($__templater->method($__vars['xf']['visitor']['dbtech_ecommerce_terms'], 'getTemplateName', array()), $__vars) . '
			</div>

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'confirm',
		'data-xf-init' => 'disabler',
		'data-container' => '.js-submitDisable',
		'label' => 'I have read and agree to the Terms of Service',
		'_type' => 'option',
	)), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
		</div>

		' . $__templater->func('redirect_input', array(null, null, true)) . '

		' . $__templater->formSubmitRow(array(
		'icon' => 'markRead',
	), array(
		'rowtype' => 'simple',
		'rowclass' => 'js-submitDisable',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/release/download', $__vars['download'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);