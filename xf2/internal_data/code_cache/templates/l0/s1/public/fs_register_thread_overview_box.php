<?php
// FROM HASH: bb69c16cf5bbd42adf24fd1dbe8111f1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block" style="margin-top:5px;">

		  ' . $__templater->button('PM me', array(
		'href' => $__templater->func('link', array('conversations/add', null, array('to' => $__vars['thread']['User']['username'], ), ), false),
		'class' => 'button',
	), '', array(
	)) . '
	  ' . $__templater->button('My Profile', array(
		'href' => $__templater->func('link', array('members', $__vars['thread']['User'], ), false),
		'class' => 'button',
	), '', array(
	)) . '
	';
	if (true) {
		$__finalCompiled .= '		
		' . $__templater->button('fs_register_emmai_me', array(
			'href' => $__templater->func('link', array('members/Mailpopup', $__vars['thread']['User'], ), false),
			'class' => 'button',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '

		<dl  class="pairs pairs--columns pairs--fixedSmall pairs--customField">
				<dt>
				' . 'Hair Color' . '
					</dt>
				<dd>
					
			' . $__templater->escape($__vars['thread']['User']['Profile']['custom_fields']['haircolor']) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--columns pairs--fixedSmall pairs--customField">
				<dt>
				' . 'Eyes' . '
					</dt>
				<dd>
					' . $__templater->escape($__vars['thread']['User']['Profile']['custom_fields']['eyes']) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--columns pairs--fixedSmall pairs--customField">
				<dt>
				' . 'Age' . '
					</dt>
				<dd>
					' . '' . $__templater->escape($__vars['thread']['User']['Profile']['custom_fields']['age']) . ' years' . '
					
				</dd>
			</dl>
		
		<dl  class="pairs pairs--columns pairs--fixedSmall pairs--customField">
				<dt>
				' . 'Height' . '
					</dt>
				<dd>
					' . $__templater->escape($__vars['thread']['User']['Profile']['custom_fields']['height']) . '
				</dd>
			</dl>
		<dl  class="pairs pairs--columns pairs--fixedSmall pairs--customField">
			<dt>
				' . 'Ethinicity' . '
			</dt>
			<dd>
				' . $__templater->escape($__vars['thread']['User']['Profile']['custom_fields']['ethnicity']) . '
			</dd>
		</dl>
	
		

	
</div>';
	return $__finalCompiled;
}
);