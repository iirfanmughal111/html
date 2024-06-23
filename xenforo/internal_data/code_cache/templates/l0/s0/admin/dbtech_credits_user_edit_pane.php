<?php
// FROM HASH: a5f40f5f843f4e3a134590f9d48f30f7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['currencies'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\Credits:Currency', )), 'getCurrencies', array());
	$__finalCompiled .= '

<li role="tabpanel" id="dbtech-credits">
	<div class="block-body">
		';
	if ($__templater->isTraversable($__vars['currencies'])) {
		foreach ($__vars['currencies'] AS $__vars['currency']) {
			$__finalCompiled .= '
			' . $__templater->formNumberBoxRow(array(
				'name' => 'credits[' . $__vars['currency']['currency_id'] . ']',
				'value' => $__templater->method($__vars['user'], 'getDbtechCreditsCurrency', array($__vars['currency'], )),
				'step' => 'any',
			), array(
				'label' => $__templater->escape($__vars['currency']['title']),
			)) . '
		';
		}
	}
	$__finalCompiled .= '
	</div>
</li>';
	return $__finalCompiled;
}
);