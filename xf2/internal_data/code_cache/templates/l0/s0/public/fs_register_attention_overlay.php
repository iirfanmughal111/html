<?php
// FROM HASH: b52fbdfe2f6397d7635191afb6f7cf09
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'fs_register_attentaion' . ' ');
	$__finalCompiled .= '

	  
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				<div class="block-row">
				<p>
					' . 'overlay_attention_body' . '
				</p>
				
				' . $__templater->button('
					' . 'fs_register_i_aggree' . '
				', array(
		'href' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], ), false),
		'class' => '',
		'icon' => 'write',
		'rel' => 'nofollow',
	), '', array(
	)) . '
						
						' . $__templater->button('
						' . 'fs_register_cancel_ad' . '
						', array(
		'class' => ' button--cta js-overlayClose',
	), '', array(
	)) . '
				</div>	
			</div>
		</div>
	</div>';
	return $__finalCompiled;
}
);