<?php
// FROM HASH: 66258d344e96f8909bf7ea452b670da9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['comments'], 'empty', array())) {
		$__finalCompiled .= '
	';
		if ($__vars['loadMore']) {
			$__finalCompiled .= '
		<div class="message-responseRow js-commentLoader">
			<a href="' . $__templater->func('link', array('media/load-previous-comments', $__vars['mediaItem'], array('before' => $__vars['firstCommentDate'], ), ), true) . '"
				data-xf-click="comment-loader"
				data-container=".js-commentLoader"
				rel="nofollow">' . 'View previous comments' . $__vars['xf']['language']['ellipsis'] . '</a>
		</div>
	';
		}
		$__finalCompiled .= '
	';
		if ($__templater->isTraversable($__vars['comments'])) {
			foreach ($__vars['comments'] AS $__vars['comment']) {
				$__finalCompiled .= '
		';
				if ($__vars['comment']['comment_state'] == 'deleted') {
					$__finalCompiled .= '
			' . $__templater->callMacro(null, 'xfmg_comment_macros::comment_deleted_lightbox', array(
						'comment' => $__vars['comment'],
						'content' => $__vars['mediaItem'],
						'linkPrefix' => 'media/media-comments',
					), $__vars) . '
		';
				} else {
					$__finalCompiled .= '
			' . $__templater->callMacro(null, 'xfmg_comment_macros::comment_lightbox', array(
						'comment' => $__vars['comment'],
						'content' => $__vars['mediaItem'],
						'linkPrefix' => 'media/media-comments',
					), $__vars) . '
		';
				}
				$__finalCompiled .= '
	';
			}
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);