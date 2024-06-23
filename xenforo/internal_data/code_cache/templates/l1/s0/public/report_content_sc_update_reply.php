<?php
// FROM HASH: f4a988c78f46a1e9f890ff827718a9e5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['reply']['message'], 'sc_update_reply', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'xa_sc_update' . '</dt>
			<dd><a href="' . $__templater->func('link', array('showcase/update', $__vars['report']['content_info']['update'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['update']['title']) . '</a></dd>
		</dl>
	</div>
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Item' . '</dt>
			<dd><a href="' . $__templater->func('link', array('showcase', $__vars['report']['content_info']['item'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['item']['title']) . '</a></dd>
		</dl>
	</div>
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Category' . '</dt>
			<dd><a href="' . $__templater->func('link', array('showcase/categories', $__vars['report']['content_info']['category'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['category']['title']) . '</a></dd>
		</dl>
	</div>
</div>';
	return $__finalCompiled;
}
);