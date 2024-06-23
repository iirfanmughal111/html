<?php
// FROM HASH: ef9736f6116fee5aa0ea1aadb45c657a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['itemRatingReplies'])) {
		foreach ($__vars['itemRatingReplies'] AS $__vars['itemRatingReply']) {
			$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_review_macros', 'reply', array(
				'review' => $__vars['review'],
				'reply' => $__vars['itemRatingReply'],
			), $__vars) . '
';
		}
	}
	return $__finalCompiled;
}
);