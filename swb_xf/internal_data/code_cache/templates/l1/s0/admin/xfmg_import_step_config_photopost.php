<?php
// FROM HASH: 2b15f052ed106289525f9a8b31b3605e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->func('in_array', array('mediaItems', $__vars['steps'], ), false)) {
		$__finalCompiled .= '
	<h3 class="block-formSectionHeader">' . 'Files path' . '</h3>
	' . $__templater->formTextBoxRow(array(
			'name' => 'step_config[mediaItems][path]',
			'value' => $__vars['stepConfig']['mediaItems']['path'],
			'required' => 'required',
		), array(
			'label' => 'Path to files directory',
			'explain' => 'Enter the full path to the folder containing your Photopost files. This path must be readable to import media items.',
		)) . '
';
	}
	return $__finalCompiled;
}
);