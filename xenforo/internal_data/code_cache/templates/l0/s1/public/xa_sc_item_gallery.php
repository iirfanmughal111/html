<?php
// FROM HASH: a450a81d9bc0581a9d4ee7c151be83b9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])) . ' - ' . 'Gallery');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'gallery';
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
	), $__vars) . '

<div class="block block--messages">
	';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
				' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
		'item' => $__vars['item'],
		'showRateButton' => 'true',
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer">
			<div class="block-outer-opposite">
			' . $__compilerTemp2 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	<div class="block-container">
		<div class="block-body lbContainer js-itemBody"
			data-xf-init="lightbox"
			data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
			data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">

			<div class="itemBody">
				<article class="itemBody-main js-lbContainer">
					';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
								';
	if ($__templater->isTraversable($__vars['item']['Attachments'])) {
		foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
			if ($__vars['attachment']['has_thumbnail']) {
				$__compilerTemp3 .= '
									' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
					'attachment' => $__vars['attachment'],
					'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
				), $__vars) . '
								';
			}
		}
	}
	$__compilerTemp3 .= '

								';
	if ($__templater->isTraversable($__vars['updatesImages'])) {
		foreach ($__vars['updatesImages'] AS $__vars['attachment']) {
			if ($__vars['attachment']['has_thumbnail']) {
				$__compilerTemp3 .= '
									' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
					'attachment' => $__vars['attachment'],
					'canView' => $__templater->method($__vars['item'], 'canViewUpdateImages', array()),
				), $__vars) . '
								';
			}
		}
	}
	$__compilerTemp3 .= '

								';
	if ($__vars['xf']['options']['xaScGalleryDisplayType'] == 'single_block') {
		$__compilerTemp3 .= '
									';
		if ($__templater->isTraversable($__vars['reviewsImages'])) {
			foreach ($__vars['reviewsImages'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail']) {
					$__compilerTemp3 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewReviewImages', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp3 .= '

									';
		if ($__templater->isTraversable($__vars['commentsImages'])) {
			foreach ($__vars['commentsImages'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail']) {
					$__compilerTemp3 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewCommentImages', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp3 .= '

									';
		if ($__templater->isTraversable($__vars['postsImages'])) {
			foreach ($__vars['postsImages'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail']) {
					$__compilerTemp3 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item']['Discussion'], 'canViewAttachments', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp3 .= '
								';
	}
	$__compilerTemp3 .= '
							';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
						';
		$__templater->includeCss('attachments.less');
		$__finalCompiled .= '
						<ul class="attachmentList itemBody-attachments">
							' . $__compilerTemp3 . '
						</ul>
					';
	}
	$__finalCompiled .= '
				</article>
			</div>
		</div>
	</div>
</div>

';
	if ($__vars['xf']['options']['xaScGalleryDisplayType'] == 'multiple_blocks') {
		$__finalCompiled .= '
	';
		$__compilerTemp4 = '';
		$__compilerTemp4 .= '
									';
		if ($__templater->isTraversable($__vars['reviewsImages'])) {
			foreach ($__vars['reviewsImages'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail']) {
					$__compilerTemp4 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewReviewImages', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp4 .= '
								';
		if (strlen(trim($__compilerTemp4)) > 0) {
			$__finalCompiled .= '
		<div class="block block--messages">
			<div class="block-container">
				<h3 class="block-header">' . 'Member submitted images via reviews' . '</h3>
				<div class="block-body lbContainer js-itemBody"
					data-xf-init="lightbox"
					data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
					data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">

					<div class="itemBody">
						<article class="itemBody-main js-lbContainer">
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp4 . '
							</ul>
						</article>
					</div>
				</div>
			</div>
		</div>
	';
		}
		$__finalCompiled .= '

	';
		$__compilerTemp5 = '';
		$__compilerTemp5 .= '
									';
		if ($__templater->isTraversable($__vars['commentsImages'])) {
			foreach ($__vars['commentsImages'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail']) {
					$__compilerTemp5 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewCommentImages', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp5 .= '
								';
		if (strlen(trim($__compilerTemp5)) > 0) {
			$__finalCompiled .= '
		<div class="block block--messages">
			<div class="block-container">
				<h3 class="block-header">' . 'Member submitted images via comments' . '</h3>
				<div class="block-body lbContainer js-itemBody"
					data-xf-init="lightbox"
					data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
					data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">

					<div class="itemBody">
						<article class="itemBody-main js-lbContainer">
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp5 . '
							</ul>
						</article>
					</div>
				</div>
			</div>
		</div>
	';
		}
		$__finalCompiled .= '

	';
		$__compilerTemp6 = '';
		$__compilerTemp6 .= '
									';
		if ($__templater->isTraversable($__vars['postsImages'])) {
			foreach ($__vars['postsImages'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail']) {
					$__compilerTemp6 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item']['Discussion'], 'canViewAttachments', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp6 .= '
								';
		if (strlen(trim($__compilerTemp6)) > 0) {
			$__finalCompiled .= '
		<div class="block block--messages">
			<div class="block-container">
				<h3 class="block-header">' . 'Member submitted images via discussion thread posts' . '</h3>
				<div class="block-body lbContainer js-itemBody"
					data-xf-init="lightbox"
					data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
					data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">

					<div class="itemBody">
						<article class="itemBody-main js-lbContainer">
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp6 . '
							</ul>
						</article>
					</div>
				</div>
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