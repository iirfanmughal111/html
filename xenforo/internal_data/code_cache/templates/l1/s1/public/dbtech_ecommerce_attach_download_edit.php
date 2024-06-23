<?php
// FROM HASH: 566f6122cc483f43a6c97f3aed903c5d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h3 class="block-formSectionHeader">
	<span class="block-formSectionHeader-aligner">' . 'Options for attachment based downloads' . '</span>
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
			<div data-xf-init="attachment-manager">
				';
				$__compilerTemp1 = '';
				if ($__vars['data']['attachmentData'][$__vars['version']]['demo']) {
					$__compilerTemp1 .= '
						' . $__templater->callMacro('public:helper_attach_upload', 'upload_block', array(
						'attachmentData' => $__vars['data']['attachmentData'][$__vars['version']]['demo'],
						'hiddenName' => 'handler_data[' . $__vars['version'] . '][demo]',
					), $__vars) . '
					';
				}
				$__finalCompiled .= $__templater->formRow('

					' . $__compilerTemp1 . '
				', array(
					'label' => 'Attached download (Demo version)',
				)) . '
			</div>
		';
			}
			$__finalCompiled .= '

		<div data-xf-init="attachment-manager">
			';
			$__compilerTemp2 = '';
			if ($__vars['data']['attachmentData'][$__vars['version']]['full']) {
				$__compilerTemp2 .= '
					' . $__templater->callMacro('public:helper_attach_upload', 'upload_block', array(
					'attachmentData' => $__vars['data']['attachmentData'][$__vars['version']]['full'],
					'hiddenName' => 'handler_data[' . $__vars['version'] . '][full]',
				), $__vars) . '
				';
			}
			$__finalCompiled .= $__templater->formRow('

				' . $__compilerTemp2 . '
			', array(
				'label' => 'Attached download (Full version)',
			)) . '
		</div>
	';
		}
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);