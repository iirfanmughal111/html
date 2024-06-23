<?php
// FROM HASH: ea4ae806b9aa32cc06aa47bf2cadcdf2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['template']['title']) . ' - ' . $__templater->filter($__templater->func('count', array($__vars['activeMods'], ), false), array(array('number', array()),), true) . '/' . $__templater->filter($__templater->func('count', array($__vars['mods'], ), false), array(array('number', array()),), true) . ' - ' . 'Template modifications');
	$__finalCompiled .= '

';
	$__templater->setPageParam('breadcrumbPath', 'styles');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['style']['title']) . ' - ' . 'Templates'), $__templater->func('link', array('styles/templates', $__vars['style'], array('type' => $__vars['template']['type'], ), ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['template']['title'])), $__templater->func('link', array('templates/edit', $__vars['template'], array('type' => $__vars['template']['type'], 'style_id' => $__vars['style']['style_id'], ), ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->includeCss('public:diff.less');
	$__finalCompiled .= '
' . $__templater->callMacro('public:prism_macros', 'setup', array(), $__vars) . '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['mods'])) {
		foreach ($__vars['mods'] AS $__vars['mod']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['mod']['modification_id'],
				'checked' => ($__vars['activeMods'][$__vars['mod']['modification_id']] ? 'checked' : ''),
				'label' => $__templater->func('trim', array('
							' . ($__vars['mod']['addon_id'] ? ($__templater->escape($__vars['mod']['addon_id']) . ' - ') : '') . $__templater->escape($__vars['mod']['modification_key']) . ' - ' . $__templater->escape($__vars['mod']['description']) . ' (<a href=\'' . $__templater->func('link', array('template-modifications/edit', $__vars['mod'], ), true) . '\'>' . 'Edit' . '</a>)
						'), false),
				'hint' => $__templater->escape($__vars['status'][$__vars['mod']->{'modification_id'}]),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formCheckBoxRow(array(
		'name' => 'active_mod_ids[]',
	), $__compilerTemp1, array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
		</div>
		
		' . $__templater->formHiddenVal('reload', '1', array(
	)) . '
		' . $__templater->formSubmitRow(array(
		'submit' => 'Reload',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('templates/view-modifications', $__vars['template'], array('type' => $__vars['template']['type'], 'style_id' => $__vars['style']['style_id'], 'tab' => $__vars['tab'], ), ), false),
		'class' => 'block',
		'ajax' => $__vars['_xfWithData'],
	)) . '

<div class="block">
	<div class="block-container">
		<h2 class="block-tabHeader tabs hScroller" data-xf-init="h-scroller" role="tablist">
			<span class="hScroller-scroll">
				' . '
				<a class="tabs-tab ' . (($__vars['tab'] === 'diffs') ? 'is-active' : '') . '" role="tab" tabindex="0"
				   href="' . $__templater->func('link', array('templates/view-modifications', $__vars['template'], array('type' => $__vars['template']['type'], 'style_id' => $__vars['style']['style_id'], 'tab' => 'diffs', 'active_mod_ids' => $__vars['activeModIds'], ), ), true) . '"
				   aria-controls="template-contents">' . 'Template contents' . '</a>
				
				<a class="tabs-tab ' . (($__vars['tab'] === 'compiled') ? 'is-active' : '') . '" role="tab" tabindex="0"
				   href="' . $__templater->func('link', array('templates/view-modifications', $__vars['template'], array('type' => $__vars['template']['type'], 'style_id' => $__vars['style']['style_id'], 'tab' => 'compiled', 'active_mod_ids' => $__vars['activeModIds'], ), ), true) . '"
				   aria-controls="compiled-template-code">' . 'Compiled template code' . '</a>
				' . '
			</span>
		</h2>
		
		<ul class="tabPanes">
			' . '
			<li class="' . (($__vars['tab'] === 'diffs') ? 'is-active' : '') . '" role="tabpanel" aria-labelledby="template-contents">
				<div class="block-body block-row block-body--contained">
					<ol class="diffList diffList--code">
						';
	if ($__templater->isTraversable($__vars['diffs'])) {
		foreach ($__vars['diffs'] AS $__vars['diff']) {
			$__finalCompiled .= '
							';
			$__vars['diffHtml'] = $__templater->preEscaped($__templater->filter($__vars['diff']['1'], array(array('join', array('<br />', )),), true));
			$__finalCompiled .= '
							<li class="diffList-line diffList-line--' . $__templater->escape($__vars['diff']['0']) . '">' . (($__templater->func('trim', array($__vars['diffHtml'], ), false) !== '') ? $__templater->escape($__vars['diffHtml']) : '&nbsp;') . '</li>
						';
		}
	}
	$__finalCompiled .= '
					</ol>
				</div>
			</li>

			<li class="' . (($__vars['tab'] === 'compiled') ? 'is-active' : '') . '" role="tabpanel" aria-labelledby="compiled-template-code">
				<div class="block-body block-row block-body--contained"><pre class="bbCodeCode" dir="ltr" data-xf-init="code-block" data-lang="php"><code>' . $__templater->escape($__vars['compiledTemplate']) . '</code></pre></div>
				
				';
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['compilerErrors'])) {
		foreach ($__vars['compilerErrors'] AS $__vars['compilerError']) {
			$__compilerTemp3 .= '
								<li class="block-row block-row--separated">' . $__templater->escape($__vars['compilerError']) . '</li>
							';
		}
	}
	$__compilerTemp2 .= $__templater->func('trim', array('
							' . $__compilerTemp3 . '
						'), false);
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
					<h3 class="block-minorHeader">' . 'Compiler errors' . $__vars['xf']['language']['label_separator'] . '</h3>
					<ol class="block-body block-body--contained">
						' . $__compilerTemp2 . '
					</ol>
				';
	}
	$__finalCompiled .= '
			</li>
			' . '
		</ul>
	</div>
</div>';
	return $__finalCompiled;
}
);