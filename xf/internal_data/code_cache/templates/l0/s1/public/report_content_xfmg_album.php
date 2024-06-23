<?php
// FROM HASH: e674f98f7ba65c5a3b8ecb94c04db58c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xfmg_album_view.less');
	$__finalCompiled .= '
';
	if ($__vars['report']['content_info']['description']) {
		$__finalCompiled .= '
	<div class="block-row block-row--separated">
		<div class="bbCodeBlock bbCodeBlock--expandable bbCodeBlock--albumDescription js-expandWatch">
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