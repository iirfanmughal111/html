<?php
// FROM HASH: 0aa13e1a56d74e44ab51c143cbf724e9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['series'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '
	';
		$__vars['headerLink'] = $__templater->func('link', array('showcase/series/featured', ), false);
		$__finalCompiled .= '

	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . ' data-type="sc_series">
		';
		if ($__vars['style'] == 'carousel') {
			$__finalCompiled .= '
			<h3 class="block-header">
				<a href="' . $__templater->escape($__vars['headerLink']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Featured series') . '</a>
			</h3>			
			' . $__templater->callMacro('xa_sc_series_index_macros', 'featured_carousel', array(
				'featuredSeries' => $__vars['series'],
				'viewAllLink' => $__templater->func('link', array('showcase/series/featured', ), false),
				'isWidget' => true,
			), $__vars) . '
		';
		} else {
			$__finalCompiled .= '
			<div class="block-container">
				';
			if ($__vars['style'] == 'list_view') {
				$__finalCompiled .= '
					<h3 class="block-header">
						<a href="' . $__templater->escape($__vars['headerLink']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Featured series') . '</a>
					</h3>
					<div class="block-body">
						<div class="structItemContainer">
							';
				if ($__templater->isTraversable($__vars['series'])) {
					foreach ($__vars['series'] AS $__vars['seriesItem']) {
						$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_series_list_macros', 'series_list', array(
							'allowInlineMod' => false,
							'series' => $__vars['seriesItem'],
						), $__vars) . '
							';
					}
				}
				$__finalCompiled .= '
						</div>
					</div>
				';
			} else {
				$__finalCompiled .= '
					<h3 class="block-minorHeader">
						<a href="' . $__templater->escape($__vars['headerLink']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Featured series') . '</a>
					</h3>
					<ul class="block-body">
						';
				if ($__templater->isTraversable($__vars['series'])) {
					foreach ($__vars['series'] AS $__vars['seriesItem']) {
						$__finalCompiled .= '
							<li class="block-row">
								' . $__templater->callMacro('xa_sc_series_list_macros', 'series_simple', array(
							'series' => $__vars['seriesItem'],
						), $__vars) . '
							</li>
						';
					}
				}
				$__finalCompiled .= '
					</ul>
				';
			}
			$__finalCompiled .= '
			</div>
		';
		}
		$__finalCompiled .= '
	</div>
';
	}
	return $__finalCompiled;
}
);