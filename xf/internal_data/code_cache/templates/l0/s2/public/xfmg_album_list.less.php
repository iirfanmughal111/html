<?php
// FROM HASH: 07b24a4486cb53a36204d26d37017024
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.itemList-itemTypeIcon
{
	&.itemList-itemTypeIcon--album
	{
		&::after
		{
			.m-faContent(@fa-var-folder-open);
		}
	}
}

' . $__templater->includeTemplate('xfmg_item_list.less', $__vars);
	return $__finalCompiled;
}
);