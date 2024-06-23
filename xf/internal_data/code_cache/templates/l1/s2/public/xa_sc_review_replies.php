<?php
// FROM HASH: 2c4b957164f0d68cdfabbaf2968c7d59
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	if ($__vars['loadMore']) {
		$__finalCompiled .= '
	<div class="message-responseRow js-commentLoader">
		<a href="' . $__templater->func('link', array('showcase/review/load-previous', $__vars['review'], array('before' => $__vars['firstReplyDate'], ), ), true) . '"
			data-xf-click="comment-loader"
			data-container=".js-commentLoader"
			rel="nofollow">' . 'View previous replies' . $__vars['xf']['language']['ellipsis'] . '</a>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->isTraversable($__vars['replies'])) {
		foreach ($__vars['replies'] AS $__vars['reply']) {
			$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_review_macros', 'reply', array(
				'reply' => $__vars['reply'],
				'review' => $__vars['review'],
			), $__vars) . '
';
		}
	}
	return $__finalCompiled;
}
);