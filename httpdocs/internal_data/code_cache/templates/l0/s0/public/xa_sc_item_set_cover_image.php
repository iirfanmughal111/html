<?php
// FROM HASH: 56ab82117311900a74d44fa8e506bdca
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Set cover image');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__templater->includeCss('attachments.less');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['item']['Attachments'])) {
		foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
			$__compilerTemp1 .= '
						';
			if ($__vars['attachment']['has_thumbnail']) {
				$__compilerTemp1 .= '
							<li class="attachment">
								<div class="attachment-icon attachment-icon--img">
									<a class="avatar NoOverlay"><img width="48" height="48" border="0" src="' . $__templater->escape($__vars['attachment']['thumbnail_url']) . '" alt="' . $__templater->escape($__vars['attachment']['filename']) . '" /></a>
								</div>
								<div class="attachment-name" style="padding-top: 5px;">
									<span class="attachment-select"><input type="radio" name="attachment_id" value="' . $__templater->escape($__vars['attachment']['attachment_id']) . '" ' . (($__vars['item']['cover_image_id'] == $__vars['attachment']['attachment_id']) ? 'checked' : '') . ' /></span>
								</div>
							</li>
						';
			}
			$__compilerTemp1 .= '
					';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<div class="block-row">			
				<ul class="attachmentList scSetCoverImage-attachments">
					' . $__compilerTemp1 . '
				</ul>
			</div>
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/set-cover-image', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);