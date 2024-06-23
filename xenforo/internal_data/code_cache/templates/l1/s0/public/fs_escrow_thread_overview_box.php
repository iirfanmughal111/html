<?php
// FROM HASH: b063333f8307276af4315dbc11d44993
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-body block-row">

		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Status' . '
					</dt>
				<dd>
					' . $__templater->callMacro('fs_escrow_list_macro', 'status', array(
		'status' => $__vars['thread']['Escrow']['escrow_status'],
	), $__vars) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Amount' . '
					</dt>
				<dd>
					' . '$' . $__templater->escape($__templater->method($__vars['thread']['Escrow'], 'getOrignolAmount', array())) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Starter' . '
					</dt>
				<dd>
					' . $__templater->escape($__vars['thread']['User']['username']) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Mentioned' . '
					</dt>
				<dd>
					' . $__templater->escape($__vars['thread']['Escrow']['User']['username']) . '
				</dd>
			</dl>
		<dl  class="pairs pairs--justified ">
			<dt>
				';
	if ($__vars['thread']['Escrow']['escrow_status'] == 0) {
		$__finalCompiled .= '
				' . 'Created Date' . '
					
				';
	} else if ($__vars['thread']['Escrow']['escrow_status'] == 1) {
		$__finalCompiled .= '
				' . 'Approving Date' . '
					';
	} else if ($__vars['thread']['Escrow']['escrow_status'] == 2) {
		$__finalCompiled .= '
				' . 'Cancelled Date' . '
						';
	} else if ($__vars['thread']['Escrow']['escrow_status'] == 3) {
		$__finalCompiled .= '
				' . 'Canceled Date' . '
							';
	} else if ($__vars['thread']['Escrow']['escrow_status'] == 4) {
		$__finalCompiled .= '
				' . 'Completed Date' . '
					
				';
	}
	$__finalCompiled .= '
			</dt>
			<dd>
				' . $__templater->func('date_dynamic', array($__vars['thread']['Escrow']['last_update'], array(
	))) . '
			</dd>
		</dl>
		
	</div>';
	return $__finalCompiled;
}
);