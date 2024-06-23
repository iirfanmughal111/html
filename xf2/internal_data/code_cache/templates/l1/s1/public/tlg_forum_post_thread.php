<?php
// FROM HASH: 3d6080df1bce7497d43edbd46a8d9bd0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('discussions');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title"><h2 class="p-title-value">' . 'Post thread' . '</h2></div>
</div>

' . $__templater->includeTemplate('forum_post_thread', $__vars);
	return $__finalCompiled;
}
);