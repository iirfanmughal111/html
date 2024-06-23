<?php
// FROM HASH: 6dd10fd5f28b06dad48a53d61f43acc7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('API key');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				<strong><code class="js-copyTarget">' . $__templater->escape($__vars['xf']['visitor']['DBTechEcommerceApiKey']['api_key']) . '</code></strong>
				' . $__templater->button('', array(
		'icon' => 'copy',
		'data-xf-init' => 'copy-to-clipboard',
		'data-copy-target' => '.js-copyTarget',
		'data-success' => 'API key copied to clipboard.',
		'class' => 'button--link',
	), '', array(
	)) . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);