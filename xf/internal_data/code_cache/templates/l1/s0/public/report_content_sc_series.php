<?php
// FROM HASH: e99f6ea0eb0d581acc7edece9233b8eb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['description'], 'sc_series', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['message'], 'sc_series', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<div>
		<dl class="pairs pairs--inline">
			<dt>' . 'Series' . '</dt>
			<dd><a href="' . $__templater->func('link', array('showcase/series', $__vars['report']['content_info'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['title']) . '</a></dd>
		</dl>
	</div>
</div>';
	return $__finalCompiled;
}
);