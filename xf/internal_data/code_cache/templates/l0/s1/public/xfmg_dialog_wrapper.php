<?php
// FROM HASH: 4fecc7a95128c342a6c7271679296d64
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= $__templater->filter($__vars['innerContent'], array(array('raw', array()),), true);
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				' . $__compilerTemp1 . '
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);