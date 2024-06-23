<?php
// FROM HASH: f9f6b2166db06b04b70b8c6fa32af24c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . $__templater->escape($__vars['thread']['title']) . ' ');
	$__finalCompiled .= '
	  
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				
				   ' . $__templater->form('
						  ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'is_featured',
		'selected' => $__vars['thread']->{'is_featured'},
		'label' => 'is_featured_thread',
		'_type' => 'option',
	)), array(
		'label' => 'is_featured_thread_title',
	)) . '
					   ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
					', array(
		'action' => $__templater->func('link', array('threads/featured-save', $__vars['thread'], ), false),
		'ajax' => 'true',
		'data-force-flash-message' => 'true',
	)) . '
				
			</div>
		</div>
	</div>';
	return $__finalCompiled;
}
);