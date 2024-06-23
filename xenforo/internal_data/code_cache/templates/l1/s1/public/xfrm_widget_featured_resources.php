<?php
// FROM HASH: e97163fc4fe0087111b5cc623a39d07a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['resources'], 'empty', array())) {
		$__finalCompiled .= '
	';
		if ($__vars['style'] == 'carousel') {
			$__finalCompiled .= '
		';
			$__templater->includeCss('carousel.less');
			$__finalCompiled .= '
		';
			$__templater->includeCss('lightslider.less');
			$__finalCompiled .= '
		';
			$__templater->includeJs(array(
				'prod' => 'xf/carousel-compiled.js',
				'dev' => 'vendor/lightslider/lightslider.min.js, xf/carousel.js',
			));
			$__finalCompiled .= '

		<div class="carousel carousel--withFooter" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
			<ul class="carousel-body carousel-body--show2" data-xf-init="carousel">
				';
			if ($__templater->isTraversable($__vars['resources'])) {
				foreach ($__vars['resources'] AS $__vars['resource']) {
					$__finalCompiled .= '
					<li>
						<div class="carousel-item">
							<div class="contentRow">
								<div class="contentRow-figure">
									';
					if ($__vars['xf']['options']['xfrmAllowIcons']) {
						$__finalCompiled .= '
										' . $__templater->func('resource_icon', array($__vars['resource'], 'm', $__templater->func('link', array('resources', $__vars['resource'], ), false), ), true) . '
									';
					} else {
						$__finalCompiled .= '
										' . $__templater->func('avatar', array($__vars['resource']['User'], 'm', false, array(
							'notooltip' => 'true',
						))) . '
									';
					}
					$__finalCompiled .= '
								</div>

								<div class="contentRow-main">
									<h4 class="contentRow-title">
										<a href="' . $__templater->func('link', array('resources', $__vars['resource'], ), true) . '">' . $__templater->func('prefix', array('resource', $__vars['resource'], ), true) . $__templater->escape($__vars['resource']['title']) . '</a>
									</h4>

									<div class="contentRow-lesser">
										' . $__templater->escape($__vars['resource']['tag_line']) . '
									</div>

									<div class="contentRow-minor contentRow-minor--smaller">
										<ul class="listInline listInline--bullet">
											<li>' . ($__templater->escape($__vars['resource']['User']['username']) ?: $__templater->escape($__vars['resource']['username'])) . '</li>
											<li>
												' . $__templater->callMacro('rating_macros', 'stars', array(
						'rating' => $__vars['resource']['rating_avg'],
					), $__vars) . '
											</li>
											<li>' . 'Updated' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['resource']['last_update'], array(
					))) . '</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</li>
				';
				}
			}
			$__finalCompiled .= '
			</ul>

			<div class="carousel-footer">
				<a href="' . $__templater->escape($__vars['link']) . '">' . 'View all featured resources' . '</a>
			</div>
		</div>
	';
		} else {
			$__finalCompiled .= '
		<div class="block" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
			<div class="block-container">
				';
			if ($__vars['style'] == 'full') {
				$__finalCompiled .= '
					<h3 class="block-header">
						<a href="' . $__templater->escape($__vars['link']) . '">' . ($__templater->escape($__vars['title']) ?: 'Featured resources') . '</a>
					</h3>

					<div class="block-body">
						<div class="structItemContainer">
							';
				if ($__templater->isTraversable($__vars['resources'])) {
					foreach ($__vars['resources'] AS $__vars['resource']) {
						$__finalCompiled .= '
								' . $__templater->callMacro('xfrm_resource_list_macros', 'resource', array(
							'allowInlineMod' => false,
							'resource' => $__vars['resource'],
						), $__vars) . '
							';
					}
				}
				$__finalCompiled .= '
						</div>
					</div>

					';
				if ($__vars['hasMore']) {
					$__finalCompiled .= '
						<div class="block-footer">
							<span class="block-footer-controls">
								' . $__templater->button('View more' . $__vars['xf']['language']['ellipsis'], array(
						'href' => $__vars['link'],
					), '', array(
					)) . '
							</span>
						</div>
					';
				}
				$__finalCompiled .= '
				';
			} else {
				$__finalCompiled .= '
					<h3 class="block-minorHeader">
						<a href="' . $__templater->escape($__vars['link']) . '">' . ($__templater->escape($__vars['title']) ?: 'Featured resources') . '</a>
					</h3>

					<ul class="block-body">
						';
				if ($__templater->isTraversable($__vars['resources'])) {
					foreach ($__vars['resources'] AS $__vars['resource']) {
						$__finalCompiled .= '
							<li class="block-row">
								' . $__templater->callMacro('xfrm_resource_list_macros', 'resource_simple', array(
							'resource' => $__vars['resource'],
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
		</div>
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);