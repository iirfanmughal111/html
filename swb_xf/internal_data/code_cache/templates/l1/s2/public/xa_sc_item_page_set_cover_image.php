<?php
// FROM HASH: b34a7cb929f921d00abbb5fd414ae5d2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Set cover image');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['page'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__templater->includeCss('attachments.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['page']['Attachments'])) {
		foreach ($__vars['page']['Attachments'] AS $__vars['attachment']) {
			$__compilerTemp1 .= '
						';
			if ($__vars['attachment']['has_thumbnail']) {
				$__compilerTemp1 .= '
							<li class="attachment">
								<div class="attachment-icon attachment-icon--img">
									<a class="avatar NoOverlay"><img width="48" height="48" border="0" src="' . $__templater->escape($__vars['attachment']['thumbnail_url']) . '" alt="' . $__templater->escape($__vars['attachment']['filename']) . '" /></a>
								</div>
								<div class="attachment-name" style="padding-top: 5px;">
									<span class="attachment-select"><input type="radio" name="attachment_id" value="' . $__templater->escape($__vars['attachment']['attachment_id']) . '" ' . (($__vars['page']['cover_image_id'] == $__vars['attachment']['attachment_id']) ? 'checked' : '') . ' /></span>
								</div>
							</li>
						';
			}
			$__compilerTemp1 .= '
					';
		}
	}
	$__compilerTemp2 = '';
	if ($__vars['from_page_management']) {
		$__compilerTemp2 .= '
				' . $__templater->formHiddenVal('mp', true, array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<div class="block-row">			
				<ul class="attachmentList scSetCoverImage-attachments">
					' . $__compilerTemp1 . '
				</ul>
			</div>	
			
			<div class="block-row">
				' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'cover_image_above_page',
		'data-hide' => 'true',
		'label' => 'Display cover image above item page',
		'hint' => 'When enabled, will display the item page cover image (if set), above the item page.',
		'checked' => $__vars['page']['cover_image_above_page'],
		'_dependent' => array('
							' . $__templater->formTextAreaRow(array(
		'name' => 'cover_image_caption',
		'value' => $__vars['page']['cover_image_caption_'],
		'maxlength' => $__templater->func('max_length', array($__vars['page'], 'cover_image_caption', ), false),
	), array(
		'label' => 'Cover image caption',
		'rowtype' => 'fullWidth',
		'hint' => 'Optional',
		'explain' => 'Will display the item page cover image caption (if set), below the item page cover image that displays above the item page.',
	)) . '
						'),
		'_type' => 'option',
	))) . '
			</div>	
			
			' . $__compilerTemp2 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/page/set-cover-image', $__vars['page'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);