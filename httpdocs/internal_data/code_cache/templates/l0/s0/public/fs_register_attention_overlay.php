<?php
// FROM HASH: 47843642d8ba791e89bab13e66dabdc3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Attention' . ' ');
	$__finalCompiled .= '
  ';
	$__templater->includeCss('regsitration_steps.less');
	$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				<div class="block-row">
						<p>
							' . 'The promotion or solication of sex is not permitted within the ads placed on this board. Violations of the PPR1 rule will have your ad deleted. Multiple violations will have tour account either temporarily or permanentle suspended.' . '
						</p>
					<p class="text-center">
							<a href="' . $__templater->func('link', array('threads/read-companion-ad-guidelines.4/', ), true) . '" target="_blank">' . 'Provider Posting Rules' . '</a>
						</p>
					<div style="display: flex;justify-content: center;">
						' . $__templater->button('
							' . 'I Agree' . '
						', array(
		'href' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], ), false),
		'style' => 'margin-right:5px',
		'rel' => 'nofollow',
	), '', array(
	)) . '

						' . $__templater->button('
							' . 'Cancel Ad' . '
						', array(
		'class' => ' button--cta js-overlayClose',
	), '', array(
	)) . '
					</div>
				
				</div>	
			</div>
		</div>
	</div>';
	return $__finalCompiled;
}
);