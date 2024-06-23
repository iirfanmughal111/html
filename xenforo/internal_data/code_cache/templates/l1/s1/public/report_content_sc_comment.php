<?php
// FROM HASH: 047154c405301f7d1fdb40fc923361eb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['comment']['message'], 'sc_comment', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<dl class="pairs pairs--inline">
		<dt>' . 'Item' . '</dt>
		<dd>
			<a href="' . $__templater->func('link', array('showcase', array('item_id' => $__vars['report']['content_info']['content_id'], 'title' => $__vars['report']['content_info']['content_title'], ), ), true) . '">' . $__templater->escape($__vars['report']['content_info']['content_title']) . '</a>
		</dd>
	</dl>
</div>';
	return $__finalCompiled;
}
);