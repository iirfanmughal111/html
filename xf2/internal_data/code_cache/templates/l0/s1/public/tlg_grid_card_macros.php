<?php
// FROM HASH: 65d50fc05cc4bee704d37c2d967860e5
return array(
'macros' => array('card' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'avatarHtml' => '!',
		'cardTitle' => '!',
		'extraHeaderHtml' => null,
		'bodyHtml' => null,
		'footerHtml' => null,
		'actionHtml' => null,
		'coverHtml' => null,
		'config' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

    ';
	$__templater->includeCss('tlg_grid_card.less');
	$__finalCompiled .= '
    <div class="gridCard js-inlineModContainer' . ((!$__templater->func('empty', array($__vars['config']['cardClass']))) ? (' ' . $__templater->escape($__vars['config']['cardClass'])) : '') . '" id="' . $__templater->func('unique_id', array(), true) . '">
        <div class="gridCard--container">
            ';
	if (!$__templater->test($__vars['coverHtml'], 'empty', array())) {
		$__finalCompiled .= '<div class="gridCard--cover">' . $__templater->filter($__vars['coverHtml'], array(array('raw', array()),), true) . '</div>';
	}
	$__finalCompiled .= '

            <div class="gridCard--header">
                <div class="gridCard--header--avatar">' . $__templater->filter($__vars['avatarHtml'], array(array('raw', array()),), true) . '</div>

                <div class="gridCard--header--main">
                    ' . $__templater->filter($__vars['cardTitle'], array(array('raw', array()),), true) . '
                    ';
	if ($__vars['extraHeaderHtml']) {
		$__finalCompiled .= $__templater->filter($__vars['extraHeaderHtml'], array(array('raw', array()),), true);
	}
	$__finalCompiled .= '
                </div>

                ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= $__templater->filter($__vars['actionHtml'], array(array('raw', array()),), true);
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
                    <div class="gridCard--header--actions">' . $__compilerTemp1 . '</div>
                ';
	}
	$__finalCompiled .= '
            </div>

            ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= $__templater->filter($__vars['bodyHtml'], array(array('raw', array()),), true);
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
                <div class="gridCard--body' . ((!$__templater->func('empty', array($__vars['config']['bodyClass']))) ? (' ' . $__templater->escape($__vars['config']['bodyClass'])) : '') . '">
                    ' . $__compilerTemp2 . '
                </div>
            ';
	}
	$__finalCompiled .= '

            ';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= $__templater->filter($__vars['footerHtml'], array(array('raw', array()),), true);
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
                <div class="gridCard--footer">' . $__compilerTemp3 . '</div>
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);