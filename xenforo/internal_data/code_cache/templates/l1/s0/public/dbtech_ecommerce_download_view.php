<?php
// FROM HASH: 0aaa6c84542e14ec9e93d570f1dc3a97
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumbs($__templater->method($__vars['product']['Category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['product']['title'])), $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Releases'), $__templater->func('link', array('dbtech-ecommerce/releases', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['download']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['download']['title']));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_product_page_macros', 'product_page_options', array(
		'category' => $__vars['product']['Category'],
		'product' => $__vars['product'],
	), $__vars) . '

<div class="block">
	<div class="block-container">
		<h3 class="block-minorHeader">' . 'Change log' . '</h3>
		<div class="block-body">
			<div class="block-row">
				' . $__templater->func('bb_code', array($__vars['download']['change_log'], 'dbtech_ecommerce_download_change_log', $__vars['download'], ), true) . '
			</div>
		</div>

		';
	if (!$__templater->test($__vars['download']['release_notes'], 'empty', array())) {
		$__finalCompiled .= '
			<h3 class="block-minorHeader">' . 'Release notes' . '</h3>
			<div class="block-body">
				<div class="block-row">
					' . $__templater->func('bb_code', array($__vars['download']['release_notes'], 'dbtech_ecommerce_download_release_notes', $__vars['download'], ), true) . '
				</div>
			</div>
		';
	}
	$__finalCompiled .= '

		<div class="block-body js-downloadBody">
			<div class="downloadBody">
				<div class="downloadBody--main">
					';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
								';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
											' . $__templater->callMacro('bookmark_macros', 'link', array(
		'content' => $__vars['download'],
		'confirmUrl' => $__templater->func('link', array('dbtech-ecommerce/release/bookmark', $__vars['download'], ), false),
	), $__vars) . '
											' . $__templater->func('react', array(array(
		'content' => $__vars['download'],
		'link' => 'dbtech-ecommerce/release/react',
		'list' => '< .js-downloadBody | .js-reactionsList',
	))) . '
										';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
									<div class="actionBar-set actionBar-set--external">
										' . $__compilerTemp2 . '
									</div>
								';
	}
	$__compilerTemp1 .= '

								';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
											';
	if (!$__templater->test($__vars['license'], 'empty', array())) {
		$__compilerTemp3 .= '
												';
		if ($__templater->method($__vars['download'], 'canDownload', array($__vars['license'], ))) {
			$__compilerTemp3 .= '
													<a href="' . $__templater->func('link', array('dbtech-ecommerce/release/download', $__vars['download'], array('license_key' => $__vars['license']['license_key'], ), ), true) . '"
													   class="actionBar-action actionBar-action--download">' . 'Download' . '</a>
												';
		}
		$__compilerTemp3 .= '
											';
	} else {
		$__compilerTemp3 .= '
												';
		if ($__templater->method($__vars['download'], 'canDownload', array())) {
			$__compilerTemp3 .= '
													<a href="' . $__templater->func('link', array('dbtech-ecommerce/release/download', $__vars['download'], ), true) . '"
													   class="actionBar-action actionBar-action--download">' . 'Download demo' . '</a>
												';
		}
		$__compilerTemp3 .= '
											';
	}
	$__compilerTemp3 .= '

											';
	if ($__templater->method($__vars['download'], 'canReport', array())) {
		$__compilerTemp3 .= '
												<a href="' . $__templater->func('link', array('dbtech-ecommerce/release/report', $__vars['download'], ), true) . '"
												   class="actionBar-action actionBar-action--report" data-xf-click="overlay">' . 'Report' . '</a>
											';
	}
	$__compilerTemp3 .= '

											';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp3 .= '
											';
	if ($__templater->method($__vars['download'], 'canEdit', array())) {
		$__compilerTemp3 .= '
												<a href="' . $__templater->func('link', array('dbtech-ecommerce/release/edit', $__vars['download'], ), true) . '"
												   class="actionBar-action actionBar-action--edit actionBar-action--menuItem">' . 'Edit' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
											';
	}
	$__compilerTemp3 .= '
											';
	if ($__templater->method($__vars['download'], 'canDelete', array('soft', ))) {
		$__compilerTemp3 .= '
												<a href="' . $__templater->func('link', array('dbtech-ecommerce/release/delete', $__vars['download'], ), true) . '"
												   class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
												   data-xf-click="overlay">' . 'Delete' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
											';
	}
	$__compilerTemp3 .= '
											';
	if ($__templater->method($__vars['download'], 'canWarn', array())) {
		$__compilerTemp3 .= '
												<a href="' . $__templater->func('link', array('dbtech-ecommerce/release/warn', $__vars['download'], ), true) . '"
												   class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
												';
	} else if ($__vars['download']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp3 .= '
												<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['download']['warning_id'], ), ), true) . '"
												   class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
												   data-xf-click="overlay">' . 'View warning' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
											';
	}
	$__compilerTemp3 .= '

											';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp3 .= '
												<a class="actionBar-action actionBar-action--menuTrigger"
												   data-xf-click="menu"
												   title="' . 'More options' . '"
												   role="button"
												   tabindex="0"
												   aria-expanded="false"
												   aria-haspopup="true">&#8226;&#8226;&#8226;</a>

												<div class="menu" data-menu="menu" aria-hidden="true" data-menu-builder="actionBar">
													<div class="menu-content">
														<h4 class="menu-header">' . 'More options' . '</h4>
														<div class="js-menuBuilderTarget"></div>
													</div>
												</div>
											';
	}
	$__compilerTemp3 .= '
										';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp1 .= '
									<div class="actionBar-set actionBar-set--internal">
										' . $__compilerTemp3 . '
									</div>
								';
	}
	$__compilerTemp1 .= '
							';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
						<div class="actionBar">
							' . $__compilerTemp1 . '
						</div>
					';
	}
	$__finalCompiled .= '

					<div class="reactionsBar js-reactionsList ' . ($__vars['download']['reactions'] ? 'is-active' : '') . '">
						' . $__templater->func('reactions', array($__vars['download'], 'dbtech-ecommerce/release/reactions', array())) . '
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);