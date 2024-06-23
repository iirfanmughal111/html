<?php
// FROM HASH: 5bbbc85dd983491c30a836a7559d6b8b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Item content find / replace');
	$__finalCompiled .= '

';
	if ($__vars['items']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__compilerTemp1 .= '
						';
				$__vars['i'] = 0;
				if ($__templater->isTraversable($__vars['item']['found'])) {
					foreach ($__vars['item']['found'] AS $__vars['key'] => $__vars['found']) {
						$__vars['i']++;
						$__compilerTemp1 .= '
							';
						$__compilerTemp2 = array();
						if ($__vars['i'] == 1) {
							$__compilerTemp2[] = array(
								'class' => 'dataList-cell--min dataList-cell--alt',
								'rowspan' => $__templater->func('count', array($__vars['item']['found'], ), false),
								'style' => (($__templater->func('count', array($__vars['item']['found'], ), false) > 1) ? 'border-bottom: none;' : ''),
								'href' => $__templater->func('link_type', array('public', 'showcase', $__vars['item'], ), false),
								'target' => '_blank',
								'label' => $__templater->filter($__vars['item']['item_id'], array(array('number', array()),), true),
								'_type' => 'main',
								'html' => '',
							);
						}
						$__compilerTemp2[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['found']) . '
								',
						);
						$__compilerTemp2[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['item']['replaced'][$__vars['key']]) . '
								',
						);
						$__compilerTemp1 .= $__templater->dataRow(array(
							'rowclass' => 'dataList-row--noHover',
						), $__compilerTemp2) . '
						';
					}
				}
				$__compilerTemp1 .= '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Item Id',
		),
		array(
			'_type' => 'cell',
			'html' => 'Matched text',
		),
		array(
			'_type' => 'cell',
			'html' => 'Replacement text',
		))) . '
					' . $__compilerTemp1 . '
				', array(
			'class' => 'dataList--contained',
			'data-xf-init' => 'responsive-data-list',
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->func('count', array($__vars['items'], ), false), ), true) . '</span>
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['itemUpdates']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp3 = '';
		if ($__templater->isTraversable($__vars['itemUpdates'])) {
			foreach ($__vars['itemUpdates'] AS $__vars['itemUpdate']) {
				$__compilerTemp3 .= '
						';
				$__vars['i'] = 0;
				if ($__templater->isTraversable($__vars['itemUpdate']['found'])) {
					foreach ($__vars['itemUpdate']['found'] AS $__vars['key'] => $__vars['found']) {
						$__vars['i']++;
						$__compilerTemp3 .= '
							';
						$__compilerTemp4 = array();
						if ($__vars['i'] == 1) {
							$__compilerTemp4[] = array(
								'class' => 'dataList-cell--min dataList-cell--alt',
								'rowspan' => $__templater->func('count', array($__vars['itemUpdate']['found'], ), false),
								'style' => (($__templater->func('count', array($__vars['itemUpdate']['found'], ), false) > 1) ? 'border-bottom: none;' : ''),
								'href' => $__templater->func('link_type', array('public', 'showcase/update', $__vars['itemUpdate'], ), false),
								'target' => '_blank',
								'label' => $__templater->filter($__vars['itemUpdate']['item_update_id'], array(array('number', array()),), true),
								'_type' => 'main',
								'html' => '',
							);
						}
						$__compilerTemp4[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['found']) . '
								',
						);
						$__compilerTemp4[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['itemUpdate']['replaced'][$__vars['key']]) . '
								',
						);
						$__compilerTemp3 .= $__templater->dataRow(array(
							'rowclass' => 'dataList-row--noHover',
						), $__compilerTemp4) . '
						';
					}
				}
				$__compilerTemp3 .= '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Item Update Id',
		),
		array(
			'_type' => 'cell',
			'html' => 'Matched text',
		),
		array(
			'_type' => 'cell',
			'html' => 'Replacement text',
		))) . '
					' . $__compilerTemp3 . '
				', array(
			'class' => 'dataList--contained',
			'data-xf-init' => 'responsive-data-list',
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->func('count', array($__vars['itemUpdates'], ), false), ), true) . '</span>
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['itemUpdateReplies']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp5 = '';
		if ($__templater->isTraversable($__vars['itemUpdateReplies'])) {
			foreach ($__vars['itemUpdateReplies'] AS $__vars['itemUpdateReply']) {
				$__compilerTemp5 .= '
						';
				$__vars['i'] = 0;
				if ($__templater->isTraversable($__vars['itemUpdateReply']['found'])) {
					foreach ($__vars['itemUpdateReply']['found'] AS $__vars['key'] => $__vars['found']) {
						$__vars['i']++;
						$__compilerTemp5 .= '
							';
						$__compilerTemp6 = array();
						if ($__vars['i'] == 1) {
							$__compilerTemp6[] = array(
								'class' => 'dataList-cell--min dataList-cell--alt',
								'rowspan' => $__templater->func('count', array($__vars['itemUpdateReply']['found'], ), false),
								'style' => (($__templater->func('count', array($__vars['itemUpdateReply']['found'], ), false) > 1) ? 'border-bottom: none;' : ''),
								'href' => $__templater->func('link_type', array('public', 'showcase/update-reply', $__vars['itemUpdateReply'], ), false),
								'target' => '_blank',
								'label' => $__templater->filter($__vars['itemUpdateReply']['reply_id'], array(array('number', array()),), true),
								'_type' => 'main',
								'html' => '',
							);
						}
						$__compilerTemp6[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['found']) . '
								',
						);
						$__compilerTemp6[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['itemUpdateReply']['replaced'][$__vars['key']]) . '
								',
						);
						$__compilerTemp5 .= $__templater->dataRow(array(
							'rowclass' => 'dataList-row--noHover',
						), $__compilerTemp6) . '
						';
					}
				}
				$__compilerTemp5 .= '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Item Update Reply Id',
		),
		array(
			'_type' => 'cell',
			'html' => 'Matched text',
		),
		array(
			'_type' => 'cell',
			'html' => 'Replacement text',
		))) . '
					' . $__compilerTemp5 . '
				', array(
			'class' => 'dataList--contained',
			'data-xf-init' => 'responsive-data-list',
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->func('count', array($__vars['itemUpdateReplies'], ), false), ), true) . '</span>
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['itemComments']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp7 = '';
		if ($__templater->isTraversable($__vars['itemComments'])) {
			foreach ($__vars['itemComments'] AS $__vars['itemComment']) {
				$__compilerTemp7 .= '
						';
				$__vars['i'] = 0;
				if ($__templater->isTraversable($__vars['itemComment']['found'])) {
					foreach ($__vars['itemComment']['found'] AS $__vars['key'] => $__vars['found']) {
						$__vars['i']++;
						$__compilerTemp7 .= '
							';
						$__compilerTemp8 = array();
						if ($__vars['i'] == 1) {
							$__compilerTemp8[] = array(
								'class' => 'dataList-cell--min dataList-cell--alt',
								'rowspan' => $__templater->func('count', array($__vars['itemComment']['found'], ), false),
								'style' => (($__templater->func('count', array($__vars['itemComment']['found'], ), false) > 1) ? 'border-bottom: none;' : ''),
								'href' => $__templater->func('link_type', array('public', 'showcase/comments', $__vars['itemComment'], ), false),
								'target' => '_blank',
								'label' => $__templater->filter($__vars['itemComment']['comment_id'], array(array('number', array()),), true),
								'_type' => 'main',
								'html' => '',
							);
						}
						$__compilerTemp8[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['found']) . '
								',
						);
						$__compilerTemp8[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['itemComment']['replaced'][$__vars['key']]) . '
								',
						);
						$__compilerTemp7 .= $__templater->dataRow(array(
							'rowclass' => 'dataList-row--noHover',
						), $__compilerTemp8) . '
						';
					}
				}
				$__compilerTemp7 .= '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Item Comment Id',
		),
		array(
			'_type' => 'cell',
			'html' => 'Matched text',
		),
		array(
			'_type' => 'cell',
			'html' => 'Replacement text',
		))) . '
					' . $__compilerTemp7 . '
				', array(
			'class' => 'dataList--contained',
			'data-xf-init' => 'responsive-data-list',
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->func('count', array($__vars['itemComments'], ), false), ), true) . '</span>
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['itemReviews']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp9 = '';
		if ($__templater->isTraversable($__vars['itemReviews'])) {
			foreach ($__vars['itemReviews'] AS $__vars['itemReview']) {
				$__compilerTemp9 .= '
						';
				$__vars['i'] = 0;
				if ($__templater->isTraversable($__vars['itemReview']['found'])) {
					foreach ($__vars['itemReview']['found'] AS $__vars['key'] => $__vars['found']) {
						$__vars['i']++;
						$__compilerTemp9 .= '
							';
						$__compilerTemp10 = array();
						if ($__vars['i'] == 1) {
							$__compilerTemp10[] = array(
								'class' => 'dataList-cell--min dataList-cell--alt',
								'rowspan' => $__templater->func('count', array($__vars['itemReview']['found'], ), false),
								'style' => (($__templater->func('count', array($__vars['itemReview']['found'], ), false) > 1) ? 'border-bottom: none;' : ''),
								'href' => $__templater->func('link_type', array('public', 'showcase/review', $__vars['itemReview'], ), false),
								'target' => '_blank',
								'label' => $__templater->filter($__vars['itemReview']['rating_id'], array(array('number', array()),), true),
								'_type' => 'main',
								'html' => '',
							);
						}
						$__compilerTemp10[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['found']) . '
								',
						);
						$__compilerTemp10[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['itemReview']['replaced'][$__vars['key']]) . '
								',
						);
						$__compilerTemp9 .= $__templater->dataRow(array(
							'rowclass' => 'dataList-row--noHover',
						), $__compilerTemp10) . '
						';
					}
				}
				$__compilerTemp9 .= '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Item Review Id',
		),
		array(
			'_type' => 'cell',
			'html' => 'Matched text',
		),
		array(
			'_type' => 'cell',
			'html' => 'Replacement text',
		))) . '
					' . $__compilerTemp9 . '
				', array(
			'class' => 'dataList--contained',
			'data-xf-init' => 'responsive-data-list',
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->func('count', array($__vars['itemReviews'], ), false), ), true) . '</span>
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['itemReviewReplies']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp11 = '';
		if ($__templater->isTraversable($__vars['itemReviewReplies'])) {
			foreach ($__vars['itemReviewReplies'] AS $__vars['itemReviewReply']) {
				$__compilerTemp11 .= '
						';
				$__vars['i'] = 0;
				if ($__templater->isTraversable($__vars['itemReviewReply']['found'])) {
					foreach ($__vars['itemReviewReply']['found'] AS $__vars['key'] => $__vars['found']) {
						$__vars['i']++;
						$__compilerTemp11 .= '
							';
						$__compilerTemp12 = array();
						if ($__vars['i'] == 1) {
							$__compilerTemp12[] = array(
								'class' => 'dataList-cell--min dataList-cell--alt',
								'rowspan' => $__templater->func('count', array($__vars['itemReviewReply']['found'], ), false),
								'style' => (($__templater->func('count', array($__vars['itemReviewReply']['found'], ), false) > 1) ? 'border-bottom: none;' : ''),
								'href' => $__templater->func('link_type', array('public', 'showcase/review-reply', $__vars['itemReviewReply'], ), false),
								'target' => '_blank',
								'label' => $__templater->filter($__vars['itemReviewReply']['reply_id'], array(array('number', array()),), true),
								'_type' => 'main',
								'html' => '',
							);
						}
						$__compilerTemp12[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['found']) . '
								',
						);
						$__compilerTemp12[] = array(
							'class' => 'dataList-cell--separated',
							'_type' => 'cell',
							'html' => '
									' . $__templater->escape($__vars['itemReviewReply']['replaced'][$__vars['key']]) . '
								',
						);
						$__compilerTemp11 .= $__templater->dataRow(array(
							'rowclass' => 'dataList-row--noHover',
						), $__compilerTemp12) . '
						';
					}
				}
				$__compilerTemp11 .= '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Review Reply Id',
		),
		array(
			'_type' => 'cell',
			'html' => 'Matched text',
		),
		array(
			'_type' => 'cell',
			'html' => 'Replacement text',
		))) . '
					' . $__compilerTemp11 . '
				', array(
			'class' => 'dataList--contained',
			'data-xf-init' => 'responsive-data-list',
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->func('count', array($__vars['itemReviewReply'], ), false), ), true) . '</span>
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'quick_find',
		'value' => $__vars['input']['quick_find'],
	), array(
		'label' => 'Quick find',
		'explain' => 'Perform the replacement only in items, item updates, item update replies, comments, reviews and review replies whose message contains this exact text.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'regex',
		'value' => $__vars['input']['regex'],
	), array(
		'label' => 'Regular expression',
		'explain' => 'Enter a full, valid PCRE regular expression.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'replace',
		'value' => $__vars['input']['replace'],
	), array(
		'label' => 'Replacement string',
		'explain' => 'Enter the string with which text that matches the regular expression will be replaced.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'commit',
		'label' => 'Save changes',
		'explain' => 'If unchecked, this tool will just test the replacement.',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Proceed',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('xa-sc-item-replace/replace', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);