<?php
// FROM HASH: ad565c8316dc40bf274ca69193362e1c
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