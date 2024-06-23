<?php
// FROM HASH: 1197455c5f25370bedd04e27d66edaf6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
    ' . $__templater->func('bb_code', array($__vars['report']['content_info']['message'], 'tl_group', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
    <dl class="pairs pairs--inline">
        <dt>' . 'Category' . '</dt>
        <dd><a href="' . $__templater->func('link', array('group-categories', $__vars['report']['content_info'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['category_title']) . '</a></dd>
    </dl>
</div>';
	return $__finalCompiled;
}
);