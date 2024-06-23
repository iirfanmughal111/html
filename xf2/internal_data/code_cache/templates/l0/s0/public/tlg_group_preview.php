<?php
// FROM HASH: f51fde607a6a44d608ab026b53bca309
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('member_tooltip.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '

<div class="tooltip-content-inner tooltip-expanded tooltip-group--inner">
    <div class="memberTooltip">
        ' . $__templater->callMacro('tlg_group_macros', 'cover', array(
		'group' => $__vars['group'],
		'forceHeight' => '90',
	), $__vars) . '

        <div class="memberTooltip-header">
			<span class="memberTooltip-avatar">
                ' . $__templater->callback('Truonglv\\Groups\\Callback', 'renderAvatar', '', array('group' => $__vars['group'], )) . '
			</span>
            <div class="memberTooltip-headerInfo">
                ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                            ' . $__templater->callMacro('tlg_group_macros', 'settings', array(
		'group' => $__vars['group'],
	), $__vars) . '
                        ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
                    <div class="memberTooltip-headerAction">
                        ' . $__compilerTemp1 . '
                    </div>
                ';
	}
	$__finalCompiled .= '

                <h4 class="memberTooltip-name"><a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '">' . $__templater->escape($__vars['group']['name']) . '</a></h4>

                <div class="memberTooltip-blurb u-muted groupItem--meta">
                    <div>' . $__templater->callMacro('tlg_group_macros', 'privacy_html', array(
		'group' => $__vars['group'],
	), $__vars) . '</div>
                </div>
            </div>
        </div>
        <div class="memberTooltip-info">
            <div class="memberTooltip-stats">
                <div class="pairJustifier">
                    <dl class="pairs pairs--rows pairs--rows--centered">
                        <dt>' . 'Created' . '</dt>
                        <dd>' . $__templater->func('date_dynamic', array($__vars['group']['created_date'], array(
	))) . '</dd>
                    </dl>
                    <dl class="pairs pairs--rows pairs--rows--centered">
                        <dt>' . 'Members' . '</dt>
                        <dd>' . $__templater->filter($__vars['group']['member_count'], array(array('number_short', array()),), true) . '</dd>
                    </dl>
                    <dl class="pairs pairs--rows pairs--rows--centered">
                        <dt>' . 'Discussions' . '</dt>
                        <dd>' . $__templater->filter($__vars['group']['discussion_count'], array(array('number_short', array()),), true) . '</dd>
                    </dl>
                    <dl class="pairs pairs--rows pairs--rows--centered">
                        <dt>' . 'Events' . '</dt>
                        <dd>' . $__templater->filter($__vars['group']['event_count'], array(array('number_short', array()),), true) . '</dd>
                    </dl>
                </div>
            </div>
        </div>

        <hr class="memberTooltip-separator" />

        <div class="memberTooltip-actions">
            <ol class="listInline flex--grow">
                ';
	if ($__templater->isTraversable($__vars['group']['CardMembers'])) {
		foreach ($__vars['group']['CardMembers'] AS $__vars['member']) {
			$__finalCompiled .= '
                    <li>' . $__templater->func('avatar', array($__vars['member']['User'], 'xs', false, array(
				'defaultname' => $__vars['member']['username'],
			))) . '</li>
                ';
		}
	}
	$__finalCompiled .= '
            </ol>
            ' . $__templater->callMacro('tlg_group_macros', 'join_button', array(
		'group' => $__vars['group'],
	), $__vars) . '
        </div>
    </div>
</div>';
	return $__finalCompiled;
}
);