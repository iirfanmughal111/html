<?php
// FROM HASH: 4d6da5164f009412607d564f8268ef01
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['albums'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__templater->includeCss('xfmg_album_list.less');
		$__finalCompiled .= '
	';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '

	';
		$__templater->includeJs(array(
			'src' => 'vendor/lightslider/lightslider.min.js',
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
				<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest albums') . '</a>
			</h3>
			<div class="block-body block-row">
				<div class="itemList itemList--slider"
					data-xf-init="item-slider"
					data-xf-item-slider="' . $__templater->filter($__vars['options']['slider'], array(array('json', array()),), true) . '">

					';
		if ($__templater->isTraversable($__vars['albums'])) {
			foreach ($__vars['albums'] AS $__vars['album']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xfmg_album_list_macros', 'album_list_item_slider', array(
					'album' => $__vars['album'],
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