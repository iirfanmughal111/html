<?php
// FROM HASH: 3d5cf7b8bc5e6e32645765dcf7307fd2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xfmg_media_view.less');
	$__finalCompiled .= '
<div class="media block-row block-row--separated">
	<div class="media-container">
		' . $__templater->callMacro('xfmg_media_view_macros', 'media_content', array(
		'mediaItem' => $__vars['content'],
	), $__vars) . '
	</div>
</div>
';
	if ($__vars['report']['content_info']['description']) {
		$__finalCompiled .= '
	<div class="block-row block-row--separated">
		<div class="bbCodeBlock bbCodeBlock--expandable bbCodeBlock--mediaDescription js-expandWatch">
			<div class="bbCodeBlock-content">
				<div class="bbCodeBlock-expandContent js-expandContent">
					' . $__templater->func('structured_text', array($__vars['report']['content_info']['description'], ), true) . '
				</div>
				<div class="bbCodeBlock-expandLink js-expandLink"><a role="button" tabindex="0">' . 'Click to expand...' . '</a></div>
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);