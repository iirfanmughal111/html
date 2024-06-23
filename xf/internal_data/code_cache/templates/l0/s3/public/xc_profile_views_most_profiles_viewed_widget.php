<?php
// FROM HASH: 860edece58d25c9bd63e0b32bbd485e1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
    <div class="block-container">
        <h3 class="block-minorHeader">' . 'Most Profiles Viewed ' . '</h3>
        <ul class="block-body">
            ';
	if ($__templater->isTraversable($__vars['mostProfilesViewed'])) {
		foreach ($__vars['mostProfilesViewed'] AS $__vars['user']) {
			$__finalCompiled .= '
                <li class="block-row">
                    <div class="contentRow contentRow--alignMiddle">
                        <div class="contentRow-figure">
								';
			if ($__vars['displayAvatar']) {
				$__finalCompiled .= '
								' . $__templater->func('avatar', array($__vars['user'], 'xs', false, array(
				))) . '
							';
			}
			$__finalCompiled .= '
                        </div>
                        <div class="contentRow-main ' . ($__vars['displayAvatar'] ? 'contentRow-main--close' : '') . '" style="' . ($__vars['displayAvatar'] ? '' : 'padding-left: 0px;') . '">
							<div class="contentRow-extra contentRow-extra--large">
								' . $__templater->escape($__vars['user']['xc_pv_profile_view_count']) . '
							</div>
                            <div class="contentRow-minor">
								<h3 class="contentRow-title">' . $__templater->func('username_link', array($__vars['user'], true, array(
			))) . '</h3>
                            </div>
                        </div>
                    </div>
                </li>
            ';
		}
	}
	$__finalCompiled .= '
        </ul>
    </div>
</div>';
	return $__finalCompiled;
}
);