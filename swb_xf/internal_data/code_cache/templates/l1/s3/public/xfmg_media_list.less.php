<?php
// FROM HASH: 32f2feb8501f0a851b0f2ad121d9badd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.itemList-itemTypeIcon
{
	&.itemList-itemTypeIcon--image
	{
		&::after
		{
			.m-faContent(@fa-var-image);
		}

		display: none;
	}

	&.itemList-itemTypeIcon--embed
	{
		&::after
		{
			.m-faContent(@fa-var-photo-video);
		}
	}

	&.itemList-itemTypeIcon--video
	{
		&::after
		{
			.m-faContent(@fa-var-video);
		}
	}

	&.itemList-itemTypeIcon--audio
	{
		&::after
		{
			.m-faContent(@fa-var-music);
		}
	}

	&.itemList-itemTypeIcon--embed
	{
		&--applemusic
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-apple); }
		}

		&--facebook
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-facebook); }
		}

		&--flickr
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-flickr); }
		}

		&--instagram
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-instagram); }
		}

		&--pinterest
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-pinterest-square); }
		}

		&--reddit
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-reddit-alien); }
		}

		&--soundcloud
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-soundcloud); }
		}

		&--spotify
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-spotify); }
		}

		&--tumblr
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-tumblr-square); }
		}

		&--twitch
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-twitch); }
		}

		&--twitter
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-twitter); }
		}

		&--vimeo
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-vimeo); }
		}

		&--youtube
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-youtube); }
		}
	}
}

' . $__templater->includeTemplate('xfmg_item_list.less', $__vars);
	return $__finalCompiled;
}
);