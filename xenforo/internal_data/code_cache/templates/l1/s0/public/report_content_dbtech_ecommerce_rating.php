<?php
// FROM HASH: 54da8d5ba1b045ced993cf0b0b102c58
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('structured_text', array($__vars['report']['content_info']['rating']['message'], ), true) . '
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