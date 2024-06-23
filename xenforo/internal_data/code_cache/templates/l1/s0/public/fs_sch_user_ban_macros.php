<?php
// FROM HASH: cbd52732289948ad38f964bdebd76546
return array(
'macros' => array('banInfo' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="blockMessage blockMessage--error">
		<p class="formRow-explain">
			<strong>' . $__templater->fontAwesome('fa-user-times fa-fw', array(
		'title' => $__templater->filter('Ban Information', array(array('for_attr', array()),), false),
	)) . ' ' . 'Ban Information' . '</strong>
		</p>	
		<p class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
			' . 'If you are planning a transaction with his participation, we strongly recommend that you do not make it before the end of the blockage. If the user has already deceived you in any way, please contact our arbitration office so that we can resolve the problem as soon as possible.' . ' 
		</p>
		<p class="formRow-explain">
			<strong>' . $__templater->fontAwesome('fa-user-circle fa-fw', array(
		'title' => $__templater->filter('Banned by', array(array('for_attr', array()),), false),
	)) . ' ' . 'Banned by' . $__vars['xf']['language']['label_separator'] . '</strong> ' . $__templater->func('username_link', array($__vars['user']['Ban']['BanUser'], true, array(
		'notooltip' => 'true',
	))) . ' <br />
			<strong>' . $__templater->fontAwesome('fa-calendar fa-fw', array(
		'title' => $__templater->filter('Ban started', array(array('for_attr', array()),), false),
	)) . ' ' . 'Ban started' . $__vars['xf']['language']['label_separator'] . '</strong> ' . $__templater->func('date_dynamic', array($__vars['user']['Ban']['ban_date'], array(
		'itemprop' => 'datePublished',
	))) . '<br />
			<strong>' . $__templater->fontAwesome('fa-flag fa-fw', array(
		'title' => $__templater->filter('Ban ends', array(array('for_attr', array()),), false),
	)) . ' ' . 'Ban ends' . $__vars['xf']['language']['label_separator'] . '</strong> ' . ($__vars['user']['Ban']['end_date'] ? $__templater->func('date', array($__vars['user']['Ban']['end_date'], ), true) : 'Never') . ' <br>
			<strong>' . $__templater->fontAwesome('fa-comment fa-fw', array(
		'title' => $__templater->filter('Reason for the ban', array(array('for_attr', array()),), false),
	)) . ' ' . 'Reason for the ban' . $__vars['xf']['language']['label_separator'] . '</strong> ' . ($__templater->func('structured_text', array($__vars['user']['Ban']['user_reason'], ), true) ?: 'N/A') . ' <br />
			<strong>' . $__templater->fontAwesome('fa-fire fa-fw', array(
		'title' => $__templater->filter('Automatically triggered', array(array('for_attr', array()),), false),
	)) . ' ' . 'Automatically triggered' . $__vars['xf']['language']['label_separator'] . '</strong> ' . ($__vars['user']['Ban']['triggered'] ? 'Yes' : 'No') . '
		</p>
	</div>
	<hr class="memberHeader-separator" />
';
	return $__finalCompiled;
}
),
'banInfoBeforeBanProfile' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="blockMessage blockMessage--error">
		<p class="formRow-explain">
			<strong>' . $__templater->fontAwesome('fa-user-times fa-fw', array(
		'title' => $__templater->filter('Ban Information', array(array('for_attr', array()),), false),
	)) . ' ' . 'Ban Information' . '</strong>
		</p>	
		<p class="formRow-explain">
			<strong>' . $__templater->fontAwesome('fa-user-circle fa-fw', array(
		'title' => $__templater->filter('Banned by', array(array('for_attr', array()),), false),
	)) . ' ' . 'Banned by' . $__vars['xf']['language']['label_separator'] . '</strong> ' . $__templater->func('username_link', array($__vars['user']['ScheduleBan']['BanByUser'], true, array(
		'notooltip' => 'true',
	))) . ' <br />
			<strong>' . $__templater->fontAwesome('fa-calendar fa-fw', array(
		'title' => $__templater->filter('Ban started', array(array('for_attr', array()),), false),
	)) . ' ' . 'Ban on' . $__vars['xf']['language']['label_separator'] . '</strong> ' . $__templater->func('date', array($__vars['user']['ScheduleBan']['ban_date'], 'Y-m-d', ), true) . ', ' . $__templater->escape($__templater->method($__vars['user']['ScheduleBan'], 'getbanTime', array())) . ' <br />
			<strong>' . $__templater->fontAwesome('fa-comment fa-fw', array(
		'title' => $__templater->filter('Reason for the ban', array(array('for_attr', array()),), false),
	)) . ' ' . 'Reason for the ban' . $__vars['xf']['language']['label_separator'] . '</strong> ' . $__templater->func('structured_text', array($__vars['user']['ScheduleBan']['ban_reason'], ), true) . ' <br />
		</p>
	</div>
	<hr class="memberHeader-separator" />
';
	return $__finalCompiled;
}
),
'messageInfo' => array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="blockMessage blockMessage--error blockMessage--iconic">
		' . 'Please note, if you want to make a deal with this user, that it is blocked.' . '
	</div>
';
	return $__finalCompiled;
}
),
'banInfoBeforeBan' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'banDate' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="blockMessage blockMessage--error">
		' . '<i class="fas fa-exclamation-triangle"></i> Please note, this user will ban on : ' . ' ' . $__templater->func('date', array($__vars['banDate']['ban_date'], 'Y-m-d', ), true) . ', ' . $__templater->escape($__templater->method($__vars['banDate'], 'getbanTime', array())) . '
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);