<?php
// FROM HASH: 9ce2445814508597a13d9be2f59db92d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['resource']['title']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('resources');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title">
        <h2 class="p-title-value">' . $__templater->escape($__vars['resource']['title']) . '</h2>
        ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= $__templater->callMacro(null, 'tlg_resource_macros::resource_actions', array(
		'resource' => $__vars['resource'],
	), $__vars);
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
            <div class="block-row">
                ' . $__templater->func('bb_code', array($__vars['resource']['FirstComment']['message'], 'tl_group_comment', $__vars['resource']['FirstComment'], ), true) . '
            </div>
        </div>

        <div class="block-footer">
            <ul class="listInline listInline--bullet">
                <li>
                    ' . $__templater->fontAwesome('fa-user', array(
	)) . '
                    ' . $__templater->func('username_link', array($__vars['resource']['User'], false, array(
		'defaultname' => $__vars['resource']['username'],
	))) . '
                </li>
                <li>
                    ' . $__templater->fontAwesome('fa-history', array(
	)) . '
                    ' . $__templater->func('date_dynamic', array($__vars['resource']['resource_date'], array(
	))) . '
                </li>
                <li class="structItem-downloadCount"
                    data-xf-init="tooltip" title="' . 'Downloads' . '">
                    ' . $__templater->fontAwesome('fa-download', array(
	)) . '&nbsp;' . $__templater->filter($__vars['resource']['download_count'], array(array('number', array()),), true) . '
                </li>
            </ul>
        </div>
    </div>
</div>

';
	$__vars['afterHtml'] = $__templater->preEscaped('
    ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['data']['page'],
		'total' => $__vars['data']['totalComments'],
		'link' => 'group-resources',
		'data' => $__vars['data']['event'],
		'params' => $__vars['data']['pageNavParams'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['data']['perPage'],
	))) . '
');
	$__finalCompiled .= '
' . $__templater->callMacro('tlg_comment_macros', 'comment_list', array(
		'afterHtml' => $__vars['afterHtml'],
		'comments' => $__vars['comments'],
		'content' => $__vars['resource'],
	), $__vars) . '
' . $__templater->callMacro('tlg_comment_macros', 'comment_form_block', array(
		'formAction' => $__templater->func('link', array('group-resources/comment', $__vars['resource'], ), false),
		'comments' => $__vars['comments'],
		'content' => $__vars['resource'],
		'attachmentData' => $__vars['attachmentData'],
	), $__vars);
	return $__finalCompiled;
}
);