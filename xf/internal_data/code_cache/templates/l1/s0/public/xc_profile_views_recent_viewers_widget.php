<?php
// FROM HASH: 4fab0d5a04bec2144a0f01725bb23b35
return array(
'macros' => array('username' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'users' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ul class="listInline listInline--comma" style="margin-bottom: 5px;">
        ';
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__finalCompiled .= $__templater->func('trim', array('
            <li>' . $__templater->func('username_link', array($__vars['user']['Visitor'], true, array(
				'class' => ((!$__vars['user']['Visitor']['visible']) ? 'username--invisible' : ''),
			))) . '</li>
        '), false);
		}
	}
	$__finalCompiled .= '
    </ul>
';
	return $__finalCompiled;
}
),
'avatar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'users' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	 <ul class="listHeap" style="margin-bottom: 5px;">
        ';
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__finalCompiled .= '
            <li>
                ' . $__templater->func('avatar', array($__vars['user']['Visitor'], 'xs', false, array(
				'img' => 'true',
			))) . '
            </li>
        ';
		}
	}
	$__finalCompiled .= '
    </ul>
';
	return $__finalCompiled;
}
),
'avataruser' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'users' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__finalCompiled .= '
        <li class="block-row">
            <div class="contentRow">
                <div class="contentRow-figure">
                    ' . $__templater->func('avatar', array($__vars['user']['Visitor'], 'xs', false, array(
			))) . '
                </div>
                <div class="contentRow-main">
                    ' . $__templater->func('username_link', array($__vars['user']['Visitor'], true, array(
			))) . '
                    <div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['user']['view_date'], array(
			))) . '</div>
                </div>
            </div>
        </li>
    ';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xc_profile_views.less');
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['xc_profile_views_enable_block_profile_views'] AND $__vars['user']['xc_pv_profile_view_count']) {
		$__finalCompiled .= '

	<div class="block" data-widget-section="userWhoSaw"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">' . 'Profile views' . '</h3>
			<div class="block-body">
				<div class="view-count center">
					
					<span class="viewCount">' . $__templater->escape($__vars['user']['xc_pv_profile_view_count']) . '</span>
					
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['xc_profile_views_enable_widget_recent_viewers'] AND !$__templater->test($__vars['userViewers'], 'empty', array())) {
		$__finalCompiled .= '
    <div class="block" data-widget-section="userWhoSaw"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
        <div class="block-container">
            <h3 class="block-minorHeader">' . 'Recent viewers' . '</h3>
			
			';
		$__compilerTemp1 = '';
		if ($__vars['displayNumber']) {
			$__compilerTemp1 .= '
					<li class="block-row block-row--minor">
						<span class="profile-views-count">
							' . $__templater->filter($__vars['user']['xc_pv_profile_view_count'], array(array('number', array()),), true) . ' ' . 'Profile views' . '
						</span>
					</li>
				';
		}
		$__vars['profileViewsCount'] = $__templater->preEscaped('
				' . $__compilerTemp1 . '
			');
		$__finalCompiled .= '
			
			';
		$__compilerTemp2 = '';
		if ($__vars['showAll']) {
			$__compilerTemp2 .= '
					<li class="block-row">
						<a href="' . $__templater->func('link', array('members/show-viewers', $__vars['user'], ), true) . '" data-xf-click="overlay">' . 'Show all' . '</a>
					</li>
				';
		}
		$__vars['templateShowAll'] = $__templater->preEscaped('
				' . $__compilerTemp2 . '
			');
		$__finalCompiled .= '
			
			';
		if ($__vars['displayUser'] == 'avataruser') {
			$__finalCompiled .= '
				<ol class="block-body">
					' . $__templater->filter($__vars['profileViewsCount'], array(array('raw', array()),), true) . '
					
					' . $__templater->callMacro(null, 'avataruser', array(
				'users' => $__vars['userViewers'],
			), $__vars) . '
					
					' . $__templater->filter($__vars['templateShowAll'], array(array('raw', array()),), true) . '
				</ol>
			';
		} else {
			$__finalCompiled .= '
				<div class="block-body">
					<div class="block-row block-row--minor">
						' . $__templater->filter($__vars['profileViewsCount'], array(array('raw', array()),), true) . '
						
						' . $__templater->callMacro(null, $__vars['displayUser'], array(
				'users' => $__vars['userViewers'],
			), $__vars) . '

						' . $__templater->filter($__vars['templateShowAll'], array(array('raw', array()),), true) . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= '
        </div>
    </div>
';
	}
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);