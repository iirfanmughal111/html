<?php
// FROM HASH: aa957a4a802a1fddbc75eb683cb50e2a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
    ' . $__templater->func('bb_code', array($__vars['report']['content_info']['message'], 'tl_group_comment', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>

<div class="block-row block-row--separated block-row--minor">
    <dl class="pairs pairs--inline">
        <dt>' . 'Group' . '</dt>
        <dd><a href="' . $__templater->func('link', array('groups', $__vars['report']['content_info'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['group_name']) . '</a></dd>
    </dl>
</div>';
	return $__finalCompiled;
}
);