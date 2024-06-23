<?php
// FROM HASH: 07d6859e2e9c79bd00505989c5cb3fed
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['mediaItems'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__templater->includeCss('xfmg_media_list.less');
		$__finalCompiled .= '
	';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '

	';
		$__templater->includeJs(array(
			'src' => 'vendor/lightslider/lightslider.js',
			'min' => '1',
		));
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xfmg/slider.js',
			'min' => '1',
		));
		$__finalCompiled .= '

	<div class="block" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">
				<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest media') . '</a>
			</h3>
			<div class="block-body block-row">
				<div class="itemList itemList--slider"
					data-xf-init="item-slider"
					data-xf-item-slider="' . $__templater->filter($__vars['options']['slider'], array(array('json', array()),), true) . '">

					';
		if ($__templater->isTraversable($__vars['mediaItems'])) {
			foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xfmg_media_list_macros', 'media_list_item_slider', array(
					'mediaItem' => $__vars['mediaItem'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);