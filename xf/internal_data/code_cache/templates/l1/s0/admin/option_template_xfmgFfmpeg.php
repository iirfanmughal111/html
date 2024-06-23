<?php
// FROM HASH: 7ae916742674e0bdf7886c659cadbd8f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['inputName'] . '[enabled]',
		'selected' => $__vars['option']['option_value']['enabled'],
		'data-hide' => 'true',
		'label' => $__templater->escape($__vars['option']['title']),
		'_dependent' => array('
			<div>' . 'FFmpeg binary path' . '</div>
			' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[ffmpegPath]',
		'value' => $__vars['option']['option_value']['ffmpegPath'],
	)) . '
			<p class="formRow-explain">' . 'Enter the path to the FFmpeg binary on this server. You can sometimes find this with the command <code>which ffmpeg</code>.' . '</p>
		', '
			' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[thumbnail]',
		'selected' => $__vars['option']['option_value']['thumbnail'],
		'label' => 'Generate thumbnails',
		'hint' => 'If enabled, thumbnails will be automatically generated where possible.',
		'_type' => 'option',
	))) . '
		', '
			' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[poster]',
		'selected' => $__vars['option']['option_value']['poster'],
		'label' => 'Generate posters',
		'hint' => 'If enabled, a higher resolution thumbnail will be automatically generated where possible. This is displayed before audio or video starts playing.',
		'_type' => 'option',
	))) . '
		', '
			' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[transcode]',
		'selected' => $__vars['option']['option_value']['transcode'],
		'label' => 'Transcode media',
		'hint' => 'If enabled, this will allow you to support more formats by transcoding media to H.264 / AAC / MP3 where required.',
		'_dependent' => array('
						<div>' . 'PHP binary path' . '</div>
						' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[phpPath]',
		'value' => $__vars['option']['option_value']['phpPath'],
	)) . '
						<p class="formRow-explain">' . 'To transcode media we need to defer the processing to a command line script which will be executed by the PHP binary specified. You can sometimes find this with the command <code>which php</code>.' . '</p>
					', '
						<div>' . 'Transcode limit' . '</div>
						' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[limit]',
		'value' => ($__vars['option']['option_value']['limit'] ?: 1),
		'min' => '1',
		'max' => '20',
	)) . '
						<p class="formRow-explain">' . 'This is the maximum number of transcode processes that will be allowed to run simultaneously. When the limit is reached, subsequent transcode processes will be queued.' . '</p>
					', '
						' . $__templater->formCheckBox(array(
	), array(array(
		'label' => 'Force transcoding of all videos',
		'name' => $__vars['inputName'] . '[forceTranscode]',
		'value' => '1',
		'selected' => ($__vars['option']['option_value']['forceTranscode'] ?: false),
		'hint' => 'Transcoding a video will also compress and optimize the resulting file for streaming. This will reduce load times and file size. With this option checked, all uploaded videos will be transcoded.',
		'_type' => 'option',
	))) . '
					'),
		'_type' => 'option',
	))) . '
		'),
		'_type' => 'option',
	)), array(
		'hint' => $__templater->escape($__vars['hintHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);