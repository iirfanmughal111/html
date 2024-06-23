<?php
// FROM HASH: 42207483d437e3d3ed948c5eab012fcd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h3 class="block-formSectionHeader">
	<span class="block-formSectionHeader-aligner">' . 'Options for auto-generated downloads' . '</span>
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
			' . $__templater->formCodeEditorRow(array(
					'name' => 'handler_data[' . $__vars['version'] . '][demo]',
					'value' => $__vars['data']['downloadVersions'][$__vars['version']]['demo']['directories'],
					'mode' => 'text',
					'data-line-wrapping' => 'true',
					'class' => 'codeEditor--autoSize',
				), array(
					'label' => 'Download repositories (Demo version)',
					'explain' => 'You can specify one or more locations for the demo (Lite) version of this download to generate itself from.<br />
It is recommended these locations are outside of your web-accessible folder and are readable.',
				)) . '
		';
			}
			$__finalCompiled .= '

		' . $__templater->formCodeEditorRow(array(
				'name' => 'handler_data[' . $__vars['version'] . '][full]',
				'value' => $__vars['data']['downloadVersions'][$__vars['version']]['full']['directories'],
				'mode' => 'text',
				'data-line-wrapping' => 'true',
				'class' => 'codeEditor--autoSize',
			), array(
				'label' => 'Download repositories (Full version)',
				'explain' => 'You can specify one or more locations for this download to generate itself from.<br />
It is recommended these locations are outside of your web-accessible folder and are readable.<br />
<br />
Seperate multiple repositories by a new line, for example:<br />
<code>/home/downloads/1</code><br />
<code>/home/downloads/2</code>',
			)) . '
	';
		}
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);