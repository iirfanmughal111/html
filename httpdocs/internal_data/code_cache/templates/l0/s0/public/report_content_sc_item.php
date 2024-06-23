<?php
// FROM HASH: a7095d51582720566562e14c649b484f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['message'], 'sc_item', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Item' . '</dt>
			<dd><a href="' . $__templater->func('link', array('showcase', $__vars['report']['content_info'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['title']) . '</a></dd>
		</dl>
	</div>
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Category' . '</dt>
			<dd><a href="' . $__templater->func('link', array('showcase/categories', $__vars['report']['content_info'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['category_title']) . '</a></dd>
		</dl>
	</div>
</div>';
	return $__finalCompiled;
}
);