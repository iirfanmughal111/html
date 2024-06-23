<?php
// FROM HASH: c35a558fca7e1d658caeac8572bc1462
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['reply']['message'], 'sc_rating_reply', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Review' . '</dt>
			<dd><a href="' . $__templater->func('link', array('showcase/review', $__vars['report']['content_info']['rating'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['item']['title']) . '</a></dd>
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