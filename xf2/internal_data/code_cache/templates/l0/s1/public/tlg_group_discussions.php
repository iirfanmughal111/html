<?php
// FROM HASH: ad438d4725e03f27acabf9e7c8ad632a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Discussions');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('discussions');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block">
    ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                            ';
	if ($__templater->method($__vars['group'], 'canAddForum', array())) {
		$__compilerTemp2 .= '
                                ' . $__templater->button('Add forum', array(
			'href' => $__templater->func('link', array('groups/add-forum', $__vars['group'], ), false),
			'overlay' => 'true',
			'icon' => 'add',
			'class' => 'button--link',
		), '', array(
		)) . '
                            ';
	}
	$__compilerTemp2 .= '
                        ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
        <div class="block-outer">
            <div class="block-outer-opposite">
                <div class="buttonGroup-buttonWrapper">
                    <div class="buttonGroup">
                        ' . $__compilerTemp2 . '
                    </div>
                </div>
            </div>
        </div>
    ';
	}
	$__finalCompiled .= '
</div>

' . $__templater->callMacro('forum_list', 'node_list', array(
		'children' => $__vars['nodeTree'],
		'extras' => $__vars['nodeExtras'],
	), $__vars);
	return $__finalCompiled;
}
);