<?php
// FROM HASH: f0187f108ef708dd6a8e8d55557754d6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Resources');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('resources');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title">
        <h2 class="p-title-value">' . 'Resources' . '</h2>
        ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                    ';
	if ($__templater->method($__vars['group'], 'canAddResource', array())) {
		$__compilerTemp2 .= '
                        ' . $__templater->button('Add resource', array(
			'href' => $__templater->func('link', array('group-resources/add', null, array('group_id' => $__vars['group']['group_id'], ), ), false),
			'icon' => 'write',
			'class' => 'button--link',
		), '', array(
		)) . '
                    ';
	}
	$__compilerTemp2 .= '
                ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
            <div class="p-title-pageAction">
                ' . $__compilerTemp2 . '
            </div>
        ';
	}
	$__finalCompiled .= '
    </div>
</div>

<div class="block">
    <div class="block-container">
        <div class="block-body">
            ';
	if ($__vars['total'] > 0) {
		$__finalCompiled .= '
                ' . $__templater->callMacro('tlg_resource_macros', 'resource_list', array(
			'resources' => $__vars['resources'],
			'group' => $__vars['group'],
		), $__vars) . '
            ';
	} else {
		$__finalCompiled .= '
                <div class="blockMessage">' . 'No items have been created yet.' . '</div>
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>

    <div class="block-outer block-outer--after">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'groups/resources',
		'data' => $__vars['group'],
		'perPage' => $__vars['perPage'],
	))) . '
    </div>
</div>';
	return $__finalCompiled;
}
);