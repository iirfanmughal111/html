<?php
// FROM HASH: e9ae73ec0c76ca405b6550cdecd23697
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.sv-quick-filter
{
	.userList
	{
		.block,
		.blockMessage
		{
			&.is-hidden
			{
				display: none;
			}
		}
	}

	.userList-row
	{
		&.is-disabled
		{
			.contentRow-figure,
			.contentRow-header,
			.contentRow-lesser
			{
				opacity: 0.5;
			}

			.contentRow-header
			{
				text-decoration: line-through;
			}

			.contentRow-extra
			{
				// this is needed to move this above the semi-transparent layer that\'s created above
				position: relative;
				z-index: 2;
			}
		}

		&.is-hidden
		{
			display: none;
		}

		.contentRow-lesser
		{
			&.no-description
			{
				visibility: hidden;
			}
		}

		.is-match
		{
			text-decoration: underline;
			color: red;
		}
	}
}';
	return $__finalCompiled;
}
);