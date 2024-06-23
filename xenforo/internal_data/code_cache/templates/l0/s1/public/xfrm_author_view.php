<?php
// FROM HASH: 63a65864170eac3941524a643f185c88
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['team']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Team resources for ' . $__templater->escape($__vars['user']['username']) . '');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Resources by ' . $__templater->escape($__vars['user']['username']) . '');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if (($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) AND $__templater->method($__vars['xf']['visitor'], 'canAddResource', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add resource' . $__vars['xf']['language']['ellipsis'], array(
			'href' => $__templater->func('link', array('resources/add', ), false),
			'class' => 'button--cta',
			'icon' => 'write',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	if (($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) AND $__vars['xf']['options']['xfrmEnableTeams']) {
		$__finalCompiled .= '
	<div class="tabs tabs--standalone">
		<div class="hScroller" data-xf-init="h-scroller">
			<span class="hScroller-scroll">
				<a class="tabs-tab ' . ((!$__vars['team']) ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources/authors', $__vars['user'], ), true) . '">
					' . 'Your resources' . '
				</a>

				<a class="tabs-tab ' . ($__vars['team'] ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources/authors', $__vars['user'], array('team' => true, ), ), true) . '">
					' . 'Team resources' . '
				</a>
			</span>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['resources'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="resource" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
		<div class="block-outer">';
		$__compilerTemp1 = '';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
							';
		if ($__vars['canInlineMod']) {
			$__compilerTemp2 .= '
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							';
		}
		$__compilerTemp2 .= '
						';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__compilerTemp1 .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp2 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= $__templater->func('trim', array('

			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'resources/authors',
			'data' => $__vars['user'],
			'params' => ($__vars['team'] ? array('team' => true, ) : null),
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			' . $__compilerTemp1 . '

		'), false) . '</div>

		<div class="block-container">
			<div class="block-body">
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['resources'])) {
			foreach ($__vars['resources'] AS $__vars['resource']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xfrm_resource_list_macros', 'resource', array(
					'resource' => $__vars['resource'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			</div>
		</div>

		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'resources/authors',
			'data' => $__vars['user'],
			'params' => ($__vars['team'] ? array('team' => true, ) : null),
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">
		';
		if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
			';
			if ($__vars['team']) {
				$__finalCompiled .= '
				' . 'You are not a member of any resource teams.' . '
			';
			} else {
				$__finalCompiled .= '
				' . 'You have not posted any resources yet.' . '
			';
			}
			$__finalCompiled .= '
		';
		} else {
			$__finalCompiled .= '
			' . '' . $__templater->escape($__vars['user']['username']) . ' has not posted any resources yet.' . '
		';
		}
		$__finalCompiled .= '
	</div>
';
	}
	return $__finalCompiled;
}
);