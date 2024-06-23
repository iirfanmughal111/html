<?php
// FROM HASH: 26648037101f09d99f86d4f1f883415d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->func('in_array', array('mediaItems', $__vars['steps'], ), false) AND $__vars['baseConfig']['attachpath']) {
		$__finalCompiled .= '
	<h3 class="block-formSectionHeader">' . 'Attachments' . '</h3>
	' . $__templater->formTextBoxRow(array(
			'name' => 'step_config[mediaItems][path]',
			'value' => $__vars['stepConfig']['mediaItems']['path'],
			'required' => 'required',
		), array(
			'label' => 'Path to attachments directory',
			'explain' => 'Enter the full path to the folder containing your vBulletin attachments. This path must be readable to import media items.',
		)) . '
';
	}
	return $__finalCompiled;
}
);