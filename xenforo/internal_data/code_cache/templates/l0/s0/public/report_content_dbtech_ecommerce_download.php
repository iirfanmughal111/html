<?php
// FROM HASH: fd6e3bf72e57acbebbd24b4c190026f5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['download']['change_log'], 'dbtech_ecommerce_download', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Product' . '</dt>
			<dd><a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['report']['content_info']['product'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['product']['title']) . '</a></dd>
		</dl>
	</div>
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Category' . '</dt>
			<dd><a href="' . $__templater->func('link', array('dbtech-ecommerce/categories', $__vars['report']['content_info']['category'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['category']['title']) . '</a></dd>
		</dl>
	</div>
</div>';
	return $__finalCompiled;
}
);