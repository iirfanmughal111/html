<?php
// FROM HASH: 1a1bec36a2b4762f49597110edab9290
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block">
	<div class="block-container">
		<div class="block-header">
			<div class="header-public-key" style="display: flex;justify-content: space-between;">
				<h3 class="block-minorHeader">' . 'PGP Public key' . '</h3>
				';
	if ($__vars['user']['public_key']) {
		$__finalCompiled .= '	
				  ' . $__templater->button('Copy to Clipboard', array(
			'icon' => 'copy',
			'data-xf-init' => 'copy-to-clipboard',
			'data-copy-target' => '.js-copyTarget',
			'data-success' => 'Public key Copy to Clipboard',
			'class' => 'button--link is-hidden',
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= '
		</div>
		</div>
			
		<div class="block-body block-row block-row--separated">
			<div class="block-body">

		<Pre class="js-copyTarget">' . ($__vars['user']['public_key'] ? $__templater->escape($__vars['user']['public_key']) : 'Not Found') . '</Pre>
		
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);