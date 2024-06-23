<?php
// FROM HASH: 5b57c7fc82379befcc65970a5ad7576d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__templater->func('xfmg_watermark', array('url', ), false)) {
		$__compilerTemp1 .= '
				<div class="formRow-explain">' . 'To change the watermark on existing images you need to run the <a href="' . $__templater->func('link', array('tools/rebuild', ), true) . '">XFMG: Update media watermarks</a> job.' . '</div>
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->func('xfmg_watermark', array('url', ), false)) {
		$__compilerTemp2 .= '
				<div class="inputChoices-spacer"></div>
				<div>' . 'Current watermark:' . '</div>
				<div class="currentWatermark">
					<img src="' . $__templater->func('xfmg_watermark', array('url', ), true) . '" />
				</div>
			';
	}
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['inputName'] . '[enabled]',
		'selected' => $__vars['option']['option_value']['enabled'],
		'data-hide' => 'true',
		'label' => $__templater->escape($__vars['option']['title']),
		'_dependent' => array('
			' . $__templater->formUpload(array(
		'name' => 'watermark',
	)) . '
			' . $__compilerTemp1 . '
			' . $__compilerTemp2 . '
		'),
		'_type' => 'option',
	)), array(
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['option']['explain']),
		'html' => '
		' . $__templater->escape($__vars['listedHtml']) . '
		' . $__templater->formHiddenVal($__vars['inputName'] . '[watermark_hash]', $__vars['option']['option_value']['watermark_hash'], array(
	)) . '
	',
	)) . '

';
	$__templater->inlineCss('
	.currentWatermark
	{
		background-color: ' . $__templater->func('property', array('paletteColor5', ), false) . ';
		border-radius: ' . $__templater->func('property', array('borderRadiusMedium', ), false) . ';
		padding: ' . $__templater->func('property', array('paddingMedium', ), false) . ';
		display: flex;
		justify-content: center;
		align-items: center;
		max-width: 340px;
	}

	.currentImage img
	{
		width: 100%;
		opacity: 0.8;
	}
');
	return $__finalCompiled;
}
);