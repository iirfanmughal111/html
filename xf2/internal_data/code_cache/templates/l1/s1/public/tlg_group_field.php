<?php
// FROM HASH: 0158fe6c9bfc70e3a5cad7ce750b36f9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['group']['name']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('field_' . $__templater->escape($__vars['fieldId']));
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title"><h2 class="p-title-value">' . $__templater->escape($__vars['fieldDefinition']['title']) . '</h2></div>
    <div class="p-description">' . $__templater->escape($__vars['fieldDefinition']['description']) . '</div>
</div>

<div class="block">
    <div class="block-container">
        <div class="block-body block-row">
            ' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
		'definition' => $__vars['fieldDefinition'],
		'value' => $__vars['fieldValue'],
	), $__vars) . '
        </div>
    </div>
</div>';
	return $__finalCompiled;
}
);