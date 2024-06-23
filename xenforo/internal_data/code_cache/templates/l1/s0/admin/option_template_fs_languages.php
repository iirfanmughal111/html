<?php
// FROM HASH: 9a7caebf470692bac4c15dcc55e23966
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array();
	$__vars['languages'] = array('af' => 'Afrikaans', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'hy' => 'Armenian', 'az' => 'Azerbaijani', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali', 'bs' => 'Bosnian', 'bg' => 'Bulgarian', 'ca' => 'Catalan', 'ceb' => 'Cebuano', 'ny' => 'Chichewa', 'zh-CN' => 'Chinese (Simplified)', 'zh-TW' => 'Chinese (Traditional)', 'co' => 'Corsican', 'hr' => 'Croatian', 'cs' => 'Czech', 'da' => 'Danish', 'nl' => 'Dutch', 'en' => 'English', 'eo' => 'Esperanto', 'et' => 'Estonian', 'tl' => 'Filipino', 'fi' => 'Finnish', 'fr' => 'French', 'fy' => 'Frisian', 'gl' => 'Galician', 'ka' => 'Georgian', 'de' => 'German', 'el' => 'Greek', 'gu' => 'Gujarati', 'ht' => 'Haitian Creole', 'ha' => 'Hausa', 'haw' => 'Hawaiian', 'iw' => 'Hebrew', 'hi' => 'Hindi', 'hmn' => 'Hmong', 'hu' => 'Hungarian', 'is' => 'Icelandic', 'ig' => 'Igbo', 'id' => 'Indonesian', 'ga' => 'Irish', 'it' => 'Italian', 'ja' => 'Japanese', 'jw' => 'Javanese', 'kn' => 'Kannada', 'kk' => 'Kazakh', 'km' => 'Khmer', 'ko' => 'Korean', 'ku' => 'Kurdish (Kurmanji)', 'ky' => 'Kyrgyz', 'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'lt' => 'Lithuanian', 'lb' => 'Luxembourgish', 'mk' => 'Macedonian', 'mg' => 'Malagasy', 'ms' => 'Malay', 'ml' => 'Malayalam', 'mt' => 'Maltese', 'mi' => 'Maori', 'mr' => 'Marathi', 'mn' => 'Mongolian', 'my' => 'Myanmar (Burmese)', 'ne' => 'Nepali', 'no' => 'Norwegian', 'ps' => 'Pashto', 'fa' => 'Persian', 'pl' => 'Polish', 'pt' => 'Portuguese', 'pa' => 'Punjabi', 'ro' => 'Romanian', 'ru' => 'Russian', 'sm' => 'Samoan', 'gd' => 'Scottish Gaelic', 'sr' => 'Serbian', 'st' => 'Sesotho', 'sn' => 'Shona', 'sd' => 'Sindhi', 'si' => 'Sinhala', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'so' => 'Somali', 'es' => 'Spanish', 'su' => 'Sundanese', 'sw' => 'Swahili', 'sv' => 'Swedish', 'tg' => 'Tajik', 'ta' => 'Tamil', 'te' => 'Telugu', 'th' => 'Thai', 'tr' => 'Turkish', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek', 'vi' => 'Vietnamese', 'cy' => 'Welsh', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'zu' => 'Zulu', );
	if ($__templater->isTraversable($__vars['languages'])) {
		foreach ($__vars['languages'] AS $__vars['langCode'] => $__vars['langTitle']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['langCode'],
				'name' => $__vars['inputName'] . '[include_languages][]',
				'selected' => $__templater->func('in_array', array($__vars['langCode'], $__vars['option']['option_value']['include_languages'], ), false),
				'label' => '
			' . $__templater->escape($__vars['langTitle']) . '
		',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
		'listclass' => 'listColumns',
	), $__compilerTemp1, array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
		'rowclass' => $__vars['rowClass'],
	));
	return $__finalCompiled;
}
);