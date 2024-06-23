<?php
// FROM HASH: 63281d07ef51fe8b86aa226a7bf70f27
return array(
'extends' => function($__templater, array $__vars) { return 'thread_view'; },
'extensions' => array('content_top' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	if ($__vars['scItem']) {
		$__finalCompiled .= '
		';
		$__vars['originalH1'] = $__templater->preEscaped($__templater->func('page_h1', array('')));
		$__finalCompiled .= '
		';
		$__vars['originalDescription'] = $__templater->preEscaped($__templater->func('page_description'));
		$__finalCompiled .= '

		';
		$__templater->pageParams['noH1'] = true;
		$__finalCompiled .= '
		';
		$__templater->pageParams['pageDescription'] = $__templater->preEscaped('');
		$__templater->pageParams['pageDescriptionMeta'] = true;
		$__finalCompiled .= '

		';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '
		
		' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'header', array(
			'item' => $__vars['scItem'],
			'titleHtml' => $__vars['originalH1'],
			'metaHtml' => $__vars['originalDescription'],
		), $__vars) . '	

		' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'tabs', array(
			'item' => $__vars['scItem'],
			'selected' => 'discussion',
		), $__vars) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
},
'above_messages' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	if ($__vars['scItem'] AND $__vars['xf']['options']['xaScDisplaySectionsOnThread']) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-container">
				<div class="block-body">
					<div class="message-expandWrapper js-expandWatch">
						<div class="message-expandContent js-expandContent">
							<div class="itemBody">
								<article class="itemBody-main js-lbContainer">
										 
									';
		if (($__vars['scItem']['description'] != '') AND $__vars['xf']['options']['xaScDisplayDescriptionItemDetails']) {
			$__finalCompiled .= '
										<div class="bbWrapper itemBody-description">
											' . $__templater->func('snippet', array($__vars['scItem']['description'], 255, array('stripBbCode' => true, ), ), true) . '
										</div>
									';
		}
		$__finalCompiled .= '

									';
		if (!$__templater->method($__vars['scItem'], 'canViewFullItem', array())) {
			$__finalCompiled .= '
										<h3>' . $__templater->escape($__vars['scItem']['Category']['title_s1']) . '</h3>

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
				'type' => 'sc_items',
				'group' => 'section_1_above',
				'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
				'set' => $__vars['scItem']['custom_fields'],
				'wrapperClass' => 'itemBody-fields itemBody-fields--before',
			), $__vars) . '

										' . $__templater->func('bb_code', array($__vars['scTrimmedItem'], 'sc_item', $__vars['scItem'], ), true) . '

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
				'type' => 'sc_items',
				'group' => 'section_1_below',
				'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
				'set' => $__vars['scItem']['custom_fields'],
				'wrapperClass' => 'itemBody-fields itemBody-fields--after',
			), $__vars) . '

										<div class="block-rowMessage block-rowMessage--important">
											' . 'You do not have permission to view the full content of this item.' . '
											';
			if (!$__vars['xf']['visitor']['user_id']) {
				$__finalCompiled .= '
												<a href="' . $__templater->func('link', array('login', ), true) . '" data-xf-click="overlay">' . 'Log in or register now.' . '</a>
											';
			}
			$__finalCompiled .= '
										</div>
									';
		} else {
			$__finalCompiled .= '
										<h3>' . $__templater->escape($__vars['scItem']['Category']['title_s1']) . '</h3>

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
				'type' => 'sc_items',
				'group' => 'section_1_above',
				'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
				'set' => $__vars['scItem']['custom_fields'],
				'wrapperClass' => 'itemBody-fields itemBody-fields--before',
			), $__vars) . '

										' . $__templater->func('bb_code', array($__vars['scItem']['message'], 'sc_item', $__vars['scItem'], ), true) . '

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
				'type' => 'sc_items',
				'group' => 'section_1_below',
				'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
				'set' => $__vars['scItem']['custom_fields'],
				'wrapperClass' => 'itemBody-fields itemBody-fields--after',
			), $__vars) . '

										';
			if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
				$__finalCompiled .= '
											';
				if ($__vars['scItem']['Category']['title_s2']) {
					$__finalCompiled .= '
												';
					$__compilerTemp1 = '';
					$__compilerTemp1 .= '
														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_2_above',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--before',
					), $__vars) . '

														';
					if ($__vars['scItem']['Category']['editor_s2'] AND ($__vars['scItem']['message_s2'] != '')) {
						$__compilerTemp1 .= '
															' . $__templater->func('bb_code', array($__vars['scItem']['message_s2'], 'sc_item', $__vars['scItem'], ), true) . '
														';
					}
					$__compilerTemp1 .= '

														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_2_below',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--after',
					), $__vars) . '
													';
					if (strlen(trim($__compilerTemp1)) > 0) {
						$__finalCompiled .= '
													<h3>' . $__templater->escape($__vars['scItem']['Category']['title_s2']) . '</h3>
													' . $__compilerTemp1 . '
												';
					}
					$__finalCompiled .= '
											';
				}
				$__finalCompiled .= '

											';
				if ($__vars['scItem']['Category']['title_s3']) {
					$__finalCompiled .= '
												';
					$__compilerTemp2 = '';
					$__compilerTemp2 .= '
														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_3_above',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--before',
					), $__vars) . '

														';
					if ($__vars['scItem']['Category']['editor_s3'] AND ($__vars['scItem']['message_s3'] != '')) {
						$__compilerTemp2 .= '
															' . $__templater->func('bb_code', array($__vars['scItem']['message_s3'], 'sc_item', $__vars['scItem'], ), true) . '
														';
					}
					$__compilerTemp2 .= '

														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_3_below',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--after',
					), $__vars) . '
													';
					if (strlen(trim($__compilerTemp2)) > 0) {
						$__finalCompiled .= '
													<h3>' . $__templater->escape($__vars['scItem']['Category']['title_s3']) . '</h3>
													' . $__compilerTemp2 . '
												';
					}
					$__finalCompiled .= '
											';
				}
				$__finalCompiled .= '

											';
				if ($__vars['scItem']['Category']['title_s4']) {
					$__finalCompiled .= '
												';
					$__compilerTemp3 = '';
					$__compilerTemp3 .= '
														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_4_above',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--before',
					), $__vars) . '

														';
					if ($__vars['scItem']['Category']['editor_s4'] AND ($__vars['scItem']['message_s4'] != '')) {
						$__compilerTemp3 .= '
															' . $__templater->func('bb_code', array($__vars['scItem']['message_s4'], 'sc_item', $__vars['scItem'], ), true) . '
														';
					}
					$__compilerTemp3 .= '

														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_4_below',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--after',
					), $__vars) . '
													';
					if (strlen(trim($__compilerTemp3)) > 0) {
						$__finalCompiled .= '
													<h3>' . $__templater->escape($__vars['scItem']['Category']['title_s4']) . '</h3>
													' . $__compilerTemp3 . '
												';
					}
					$__finalCompiled .= '
											';
				}
				$__finalCompiled .= '

											';
				if ($__vars['scItem']['Category']['title_s5']) {
					$__finalCompiled .= '
												';
					$__compilerTemp4 = '';
					$__compilerTemp4 .= '
														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_5_above',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--before',
					), $__vars) . '

														';
					if ($__vars['scItem']['Category']['editor_s5'] AND ($__vars['scItem']['message_s5'] != '')) {
						$__compilerTemp4 .= '
															' . $__templater->func('bb_code', array($__vars['scItem']['message_s5'], 'sc_item', $__vars['scItem'], ), true) . '
														';
					}
					$__compilerTemp4 .= '

														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_5_below',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--after',
					), $__vars) . '
													';
					if (strlen(trim($__compilerTemp4)) > 0) {
						$__finalCompiled .= '
													<h3>' . $__templater->escape($__vars['scItem']['Category']['title_s5']) . '</h3>
													' . $__compilerTemp4 . '
												';
					}
					$__finalCompiled .= '
											';
				}
				$__finalCompiled .= '

											';
				if ($__vars['scItem']['Category']['title_s6']) {
					$__finalCompiled .= '
												';
					$__compilerTemp5 = '';
					$__compilerTemp5 .= '
														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_6_above',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--before',
					), $__vars) . '

														';
					if ($__vars['scItem']['Category']['editor_s6'] AND ($__vars['scItem']['message_s6'] != '')) {
						$__compilerTemp5 .= '
															' . $__templater->func('bb_code', array($__vars['scItem']['message_s6'], 'sc_item', $__vars['scItem'], ), true) . '
														';
					}
					$__compilerTemp5 .= '

														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
						'type' => 'sc_items',
						'group' => 'section_6_below',
						'onlyInclude' => $__vars['scItem']['Category']['field_cache'],
						'set' => $__vars['scItem']['custom_fields'],
						'wrapperClass' => 'itemBody-fields itemBody-fields--after',
					), $__vars) . '
													';
					if (strlen(trim($__compilerTemp5)) > 0) {
						$__finalCompiled .= '
													<h3>' . $__templater->escape($__vars['scItem']['Category']['title_s6']) . '</h3>
													' . $__compilerTemp5 . '
												';
					}
					$__finalCompiled .= '
											';
				}
				$__finalCompiled .= '
										';
			}
			$__finalCompiled .= '
									';
		}
		$__finalCompiled .= '
								</article>
							</div>	
						</div>

						<div class="message-expandLink js-expandLink"><a role="button" tabindex="0">' . 'Click to expand...' . '</a></div>
					</div>
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '	
';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->renderExtension('content_top', $__vars, $__extensions) . '

' . $__templater->renderExtension('above_messages', $__vars, $__extensions);
	return $__finalCompiled;
}
);