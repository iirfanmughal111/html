<?php
// FROM HASH: 6d9e645ca4a5285a1c725d9bc52808c2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Create series');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['series'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

' . $__templater->form('
	
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['series']['title_'],
		'class' => 'input--title',
		'maxlength' => $__templater->func('max_length', array($__vars['series'], 'title', ), false),
		'placeholder' => 'Title' . $__vars['xf']['language']['ellipsis'],
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Title',
	)) . '

			' . $__templater->callMacro('xa_sc_series_edit_macros', 'message', array(
		'message' => $__vars['series']['message_'],
		'attachmentData' => $__vars['attachmentData'],
	), $__vars) . '

			<hr class="formRowSep" />

			' . $__templater->callMacro('xa_sc_series_edit_macros', 'description', array(
		'series' => $__vars['series'],
	), $__vars) . '

			<hr class="formRowSep" />
		
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'community_series',
		'selected' => $__templater->method($__vars['series'], 'isCommunitySeries', array()),
		'label' => 'Community series',
		'afterhint' => 'When enabled, allows other members of the community to add their own items(s) to this series. ',
		'_type' => 'option',
	)), array(
	)) . '			
		</div>
		
		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Search engine optimization options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->callMacro('xa_sc_series_edit_macros', 'og_title_meta_title', array(
		'series' => $__vars['series'],
	), $__vars) . '			
			' . $__templater->callMacro('xa_sc_series_edit_macros', 'meta_description', array(
		'series' => $__vars['series'],
	), $__vars) . '
		</div>		
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/series/create-series', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-preview-url' => $__templater->func('link', array('showcase/series/preview', $__vars['series'], ), false),
	));
	return $__finalCompiled;
}
);