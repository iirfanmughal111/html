<?php
// FROM HASH: 0bf36b99083ea5f489fc43cc3b374e35
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h3 class="block-formSectionHeader">
	<span class="block-formSectionHeader-aligner">' . 'Options for external downloads' . '</span>
</h3>
<div class="block-body">
	';
	if ($__templater->isTraversable($__vars['download']['Product']['product_versions'])) {
		foreach ($__vars['download']['Product']['product_versions'] AS $__vars['version'] => $__vars['text']) {
			$__finalCompiled .= '
		' . $__templater->formRow('
			<h4 class="block-textHeader">' . $__templater->escape($__templater->method($__vars['download']['Product'], 'getVersionLabel', array($__vars['version'], ))) . '</h4>
		', array(
			)) . '

		';
			if ($__vars['download']['Product']['has_demo']) {
				$__finalCompiled .= '
			' . $__templater->formTextBoxRow(array(
					'name' => 'handler_data[' . $__vars['version'] . '][demo]',
					'value' => $__vars['data']['downloadVersions'][$__vars['version']]['demo']['download_url'],
				), array(
					'label' => 'External download URL (Demo version)',
				)) . '
		';
			}
			$__finalCompiled .= '

		' . $__templater->formTextBoxRow(array(
				'name' => 'handler_data[' . $__vars['version'] . '][full]',
				'value' => $__vars['data']['downloadVersions'][$__vars['version']]['full']['download_url'],
			), array(
				'label' => 'External download URL (Full version)',
			)) . '
	';
		}
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);