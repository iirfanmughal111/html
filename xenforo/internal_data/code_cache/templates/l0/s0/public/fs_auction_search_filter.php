<?php
// FROM HASH: 52fdaf1b3bc435eca033032e854299b6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'selected' => true,
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['categories'])) {
		foreach ($__vars['categories'] AS $__vars['cat']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['cat']['category_id'],
				'label' => $__templater->escape($__vars['cat']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="menu-row">
    ' . 'Username' . $__vars['xf']['language']['label_separator'] . '
    <div class="u-inputSpacer">
      	' . $__templater->formTextBox(array(
		'name' => 'fs_auction_username',
		'value' => $__vars['conditions']['fs_auction_username'],
		'ac' => 'single',
	)) . '
    </div>
  </div>
	
	<div class="menu-row menu-row--separated">
			' . 'Auction Status' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				' . $__templater->formSelect(array(
		'name' => 'fs_auction_status',
		'value' => $__vars['conditions']['fs_auction_status'],
	), array(array(
		'value' => 'all',
		'selected' => true,
		'label' => 'All',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Active',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => 'Expired',
		'_type' => 'option',
	))) . '
			</div>
		</div>
	
	<div class="menu-row menu-row--separated">
			' . 'By Category' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				' . $__templater->formSelect(array(
		'name' => 'fs_auction_cat',
		'value' => $__vars['conditions']['fs_auction_cat'],
	), $__compilerTemp1) . '
			</div>
		</div>
	
	
	
	  <div class="menu-footer">
    <span class="menu-footer-controls">
      ' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
    </span>
  </div>

  ' . $__templater->formHiddenVal('search', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('auction', ), false),
	));
	return $__finalCompiled;
}
);