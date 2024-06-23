<?php
// FROM HASH: 7f3b7a5d4bfe6796090c83fdc7932253
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('fs_t_google_translate.less');
	$__finalCompiled .= '


<script type="text/javascript">
	
	let includeLanguages = ' . $__templater->filter($__vars['xf']['options']['fs_includeLanguages']['include_languages'], array(array('json', array()),array('raw', array()),), true) . ';
	
	let alternativeFlags = ' . $__templater->filter($__vars['xf']['options']['fs_t_alternativeFlags'], array(array('json', array()),array('raw', array()),), true) . ';
	
	let fs_lang_positions = "' . $__templater->escape($__vars['xf']['options']['fs_t_showLanguage']) . '".split(\'_\');
	
	let	widget_look ="' . $__templater->escape($__vars['xf']['options']['fs_t_widgetLook']) . '";	
	if(widget_look == \'globe\')
		flags_location = "' . $__templater->func('base_url', array('styles/FS/Translator/flags/svg/', ), true) . '";
	else
		flags_location = "' . $__templater->func('base_url', array('styles/FS/Translator/flags/', ), true) . '";
	
	
	var fs_gtranslateSettings = {
	
       	default_language: "' . $__templater->escape($__vars['xf']['options']['fs_t_defaultLanguage']) . '",
      	switcher_horizontal_position: fs_lang_positions[1],
		switcher_vertical_position: fs_lang_positions[0],
        horizontal_position: fs_lang_positions[1],
		vertical_position: fs_lang_positions[0],
        float_switcher_open_direction: "' . $__templater->escape($__vars['xf']['options']['fs_t_openDirection']) . '",
        switcher_open_direction: "' . $__templater->escape($__vars['xf']['options']['fs_t_openDirection']) . '",
		detect_browser_language: ' . $__templater->escape($__vars['xf']['options']['fs_t_autoSwitch']) . ',
		flags_location : flags_location,
      	native_language_names: ' . $__templater->escape($__vars['xf']['options']['fs_t_NativeLanguage']) . ',
        select_language_label: \'Select Language\',
        flag_style:  "' . $__templater->escape($__vars['xf']['options']['fs_t_FlagStyle']) . '",
       	languages: includeLanguages,
       	dropdown_languages: includeLanguages,
   	  	alt_flags: alternativeFlags,
		
    };
	
</script>


<div id="google_translate_element2"></div>
<div class="fs_translate_wrapper"></div>


';
	if ($__vars['xf']['options']['fs_t_widgetLook']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'FS/Translator/' . $__vars['xf']['options']['fs_t_widgetLook'] . '.js',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<script type="text/javascript">
	function googleTranslateElementInit() {
		new google.translate.TranslateElement({pageLanguage: \'' . $__templater->escape($__vars['xf']['options']['fs_t_defaultLanguage']) . '\'}, \'google_translate_element2\');
	}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>';
	return $__finalCompiled;
}
);