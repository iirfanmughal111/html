<?php
// FROM HASH: 9b0cd1fb4446efa0893cd8ccbc4633eb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])) . ' - ' . 'Updates');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/updates', $__vars['item'], array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'updates';
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => $__templater->method($__vars['item'], 'canViewUpdateImages', array()),
	), $__vars) . '

';
	if ($__vars['canInlineModUpdates']) {
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

<div class="block block--messages"
	data-xf-init="' . ($__vars['canInlineModUpdates'] ? 'inline-mod' : '') . '"
	data-type="sc_update"
	data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

	<div class="block-outer">';
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
					' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
		'item' => $__vars['item'],
		'showPostAnUpdateButton' => true,
		'canInlineMod' => $__vars['canInlineModUpdates'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '			
			<div class="block-outer-opposite">
				' . $__compilerTemp3 . '
			</div>
		';
	}
	$__finalCompiled .= $__templater->func('trim', array('
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/updates',
		'data' => $__vars['item'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		
		' . $__compilerTemp2 . '			
	'), false) . '</div>

	<div class="block-container"
		data-xf-init="lightbox"
		data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
		data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

		<div class="block-body">
			';
	if (!$__templater->test($__vars['updates'], 'empty', array())) {
		$__finalCompiled .= '
				';
		if ($__templater->isTraversable($__vars['updates'])) {
			foreach ($__vars['updates'] AS $__vars['update']) {
				$__finalCompiled .= '
					' . $__templater->callMacro('xa_sc_update_macros', 'update', array(
					'update' => $__vars['update'],
					'item' => $__vars['item'],
				), $__vars) . '
				';
			}
		}
		$__finalCompiled .= '
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no updates matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No updates have been posted yet' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
	';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
				' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/updates',
		'data' => $__vars['item'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
				' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
			';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			' . $__compilerTemp4 . '
		</div>
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);