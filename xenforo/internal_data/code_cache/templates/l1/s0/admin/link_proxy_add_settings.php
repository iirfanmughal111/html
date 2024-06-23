<?php
// FROM HASH: a39818b1d5cd0e9dae37526a709ca608
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Link Proxy');
	$__finalCompiled .= '


<div class="block">

    ';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['userGroup']);
	$__finalCompiled .= $__templater->form('
      <div class="block-body">
		  ' . $__templater->formRow('
			  ' . $__templater->formSelect(array(
		'name' => 'user_group_id',
		'value' => ($__vars['list']['user_group_id'] ? $__vars['list']['user_group_id'] : ''),
	), $__compilerTemp1) . '
		  ', array(
		'label' => 'Usergroup',
		'hint' => 'Required',
		'required' => 'true',
	)) . '
		  

            ' . $__templater->formNumberBoxRow(array(
		'name' => 'redirect_time',
		'value' => ($__vars['list']['redirect_time'] ? $__vars['list']['redirect_time'] : ''),
		'min' => '0',
		'required' => 'true',
	), array(
		'label' => 'Time',
		'explain' => '(in seconds)',
		'hint' => 'Required',
	)) . '        
		  
		  ' . $__templater->formCodeEditorRow(array(
		'name' => 'link_redirect_html',
		'value' => ($__vars['list']['link_redirect_html'] ? $__vars['list']['link_redirect_html'] : ''),
		'mode' => 'css',
		'data-line-wrapping' => 'true',
		'class' => 'codeEditor--autoSize',
	), array(
		'label' => 'HTML',
	)) . '

		
        ' . $__templater->formTextBoxRow(array(
		'type' => 'hidden',
		'name' => 'link_id',
		'value' => ($__vars['list']['list_id'] ? $__vars['list']['list_id'] : ''),
	), array(
	)) . '
      </div>

      ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
    ', array(
		'action' => $__templater->func('link', array('link-proxy/save', ), false),
		'ajax' => 'true',
		'class' => 'block-container',
		'novalidate' => 'novalidate',
	)) . '
  
</div>';
	return $__finalCompiled;
}
);