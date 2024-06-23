<?php
// FROM HASH: 51c7f6fdc69257fbc9b294a9f30d633f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['comment']['message'], 'xfmg_comment', ($__vars['content'] ?: $__vars['report']['User']), ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<dl class="pairs pairs--inline">
		';
	if ($__vars['report']['content_info']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
			<dt>' . 'Media item' . '</dt>
			<dd>
				<a href="' . $__templater->func('link', array('media', array('media_id' => $__vars['report']['content_info']['content_id'], 'title' => $__vars['report']['content_info']['content_title'], ), ), true) . '">' . $__templater->escape($__vars['report']['content_info']['content_title']) . '</a>
			</dd>
		';
	} else {
		$__finalCompiled .= '
			<dt>' . 'Album' . '</dt>
			<dd>
				<a href="' . $__templater->func('link', array('media/albums', array('album_id' => $__vars['report']['content_info']['content_id'], 'title' => $__vars['report']['content_info']['content_title'], ), ), true) . '">' . $__templater->escape($__vars['report']['content_info']['content_title']) . '</a>
			</dd>
		';
	}
	$__finalCompiled .= '
	</dl>
</div>';
	return $__finalCompiled;
}
);