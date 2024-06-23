<?php
// FROM HASH: 69962f74613cc7b93bc6203783d286b3
return array(
'macros' => array('forum_mod_actions' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'forum' => '!',
		'group' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
';
	if (!$__templater->test($__vars['group'], 'empty', array())) {
		$__finalCompiled .= '
    ';
		if ($__templater->method($__vars['forum'], 'canCreateThread', array())) {
			$__finalCompiled .= '
        ' . $__templater->button('
            ' . 'Post thread' . '
        ', array(
				'href' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], ), false),
				'class' => 'button--cta',
				'icon' => 'write',
			), '', array(
			)) . '
    ';
		}
		$__finalCompiled .= '

    ';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
                    ';
		if ($__templater->method($__vars['group'], 'canEditForum', array())) {
			$__compilerTemp1 .= '
                        <a href="' . $__templater->func('link', array('group-forums/edit', $__vars['forum'], ), true) . '"
                           class="menu-linkRow"
                           data-xf-click="overlay">' . 'Edit forum' . '</a>
                    ';
		}
		$__compilerTemp1 .= '

                    ';
		if ($__templater->method($__vars['group'], 'canDeleteForum', array())) {
			$__compilerTemp1 .= '
                        <a href="' . $__templater->func('link', array('group-forums/delete', $__vars['forum'], ), true) . '"
                           class="menu-linkRow"
                           data-xf-click="overlay">' . 'Delete forum' . '</a>
                    ';
		}
		$__compilerTemp1 .= '
                ';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
        ' . $__templater->button('&#8226;&#8226;&#8226;', array(
				'class' => 'button--link menuTrigger',
				'data-xf-click' => 'menu',
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
				'title' => 'More options',
			), '', array(
			)) . '
        <div class="menu" data-menu="menu" aria-hidden="true">
            <div class="menu-content">
                <h4 class="menu-header">' . 'More options' . '</h4>
                ' . $__compilerTemp1 . '
            </div>
        </div>
    ';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('discussions');
	$__compilerTemp1['noBreadcrumbs'] = $__templater->preEscaped('1');
	$__compilerTemp1['noPageOptions'] = $__templater->preEscaped('1');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title"><h2 class="p-title-value">' . $__templater->escape($__vars['forum']['title']) . '</h2></div>
    <div class="p-description">' . $__templater->escape($__vars['forum']['description']) . '</div>
</div>

' . $__templater->includeTemplate('forum_view', $__vars) . '

';
	return $__finalCompiled;
}
);