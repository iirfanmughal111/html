<?php
// FROM HASH: b7782af8b042539eccaebdf5f0a01eb8
return array(
'macros' => array('fs_auction_stats' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'stats' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Auction statistics' . '</h3>
			<div class="block-body">
				';
	if ($__vars['stats']) {
		$__finalCompiled .= '
							
					<ol class="categoryList toggleTarget is-active">

		';
		if ($__templater->isTraversable($__vars['stats'])) {
			foreach ($__vars['stats'] AS $__vars['id'] => $__vars['category']) {
				$__finalCompiled .= '
			
			<li class="categoryList-item">
		<div class="categoryList-itemRow">
			<div class="categoryList-link" style="color:#2577b1;">
				' . $__templater->escape($__vars['category']['title']) . '
			</div>
			<span class="categoryList-label">
				<span class="label label--subtle label--smallest">' . $__templater->escape($__vars['category']['count']) . '</span>
			</span>
		</div>
	</li>
		
		';
			}
		}
		$__finalCompiled .= '
	</ol>
				';
	} else {
		$__finalCompiled .= '
					<div class="block-row">' . 'N/A' . '</div>
				';
	}
	$__finalCompiled .= '
				
			</div>
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