<?php

return function($__templater, $__selectedNav, array $__vars)
{
	$__tree = [];
	$__flat = [];


	$__navTemp = [
		'title' => \XF::phrase('nav._default'),
		'href' => '',
		'attributes' => [],
	];
	if ($__navTemp) {
		$__tree['_default'] = $__navTemp;
		$__flat['_default'] =& $__tree['_default'];
		if (empty($__tree['_default']['children'])) { $__tree['_default']['children'] = []; }

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultNewsFeed'),
		'href' => $__templater->func('link', array('whats-new/news-feed', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultNewsFeed'] = $__navTemp;
				$__flat['defaultNewsFeed'] =& $__tree['_default']['children']['defaultNewsFeed'];
			}
		}

		$__navTemp = [
		'title' => \XF::phrase('nav.defaultLatestActivity'),
		'href' => $__templater->func('link', array('whats-new/latest-activity', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['_default']['children']['defaultLatestActivity'] = $__navTemp;
			$__flat['defaultLatestActivity'] =& $__tree['_default']['children']['defaultLatestActivity'];
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultYourProfile'),
		'href' => $__templater->func('link', array('members', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultYourProfile'] = $__navTemp;
				$__flat['defaultYourProfile'] =& $__tree['_default']['children']['defaultYourProfile'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultYourAccount'),
		'href' => $__templater->func('link', array('account', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultYourAccount'] = $__navTemp;
				$__flat['defaultYourAccount'] =& $__tree['_default']['children']['defaultYourAccount'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultLogOut'),
		'href' => $__templater->func('link', array('logout', null, array('t' => $__templater->func('csrf_token', array(), false), ), ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultLogOut'] = $__navTemp;
				$__flat['defaultLogOut'] =& $__tree['_default']['children']['defaultLogOut'];
			}
		}

		if (((!$__vars['xf']['visitor']['user_id']) AND $__vars['xf']['options']['registrationSetup']['enabled'])) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultRegister'),
		'href' => $__templater->func('link', array('register', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultRegister'] = $__navTemp;
				$__flat['defaultRegister'] =& $__tree['_default']['children']['defaultRegister'];
			}
		}

	}

	if ($__vars['xf']['homePageUrl']) {
		$__navTemp = [
		'title' => \XF::phrase('nav.home'),
		'href' => $__vars['xf']['homePageUrl'],
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['home'] = $__navTemp;
			$__flat['home'] =& $__tree['home'];
		}
	}

	$__navTemp = [
		'title' => \XF::phrase('nav.forums'),
		'href' => $__templater->func('link', array('forums', ), false),
		'attributes' => [],
	];
	if ($__navTemp) {
		$__tree['forums'] = $__navTemp;
		$__flat['forums'] =& $__tree['forums'];
		if (empty($__tree['forums']['children'])) { $__tree['forums']['children'] = []; }

		if (($__vars['xf']['options']['forumsDefaultPage'] != 'new_posts')) {
			$__navTemp = [
		'title' => \XF::phrase('nav.newPosts'),
		'href' => $__templater->func('link', array('whats-new/posts', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['newPosts'] = $__navTemp;
				$__flat['newPosts'] =& $__tree['forums']['children']['newPosts'];
			}
		}

		if (($__vars['xf']['options']['forumsDefaultPage'] != 'forums')) {
			$__navTemp = [
		'title' => \XF::phrase('nav.forumList'),
		'href' => $__templater->func('link', array('forums/list', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['forumList'] = $__navTemp;
				$__flat['forumList'] =& $__tree['forums']['children']['forumList'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.findThreads'),
		'href' => $__templater->func('link', array('find-threads/started', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['findThreads'] = $__navTemp;
				$__flat['findThreads'] =& $__tree['forums']['children']['findThreads'];
				if (empty($__tree['forums']['children']['findThreads']['children'])) { $__tree['forums']['children']['findThreads']['children'] = []; }

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.yourThreads'),
		'href' => $__templater->func('link', array('find-threads/started', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['findThreads']['children']['yourThreads'] = $__navTemp;
						$__flat['yourThreads'] =& $__tree['forums']['children']['findThreads']['children']['yourThreads'];
					}
				}

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.contributedThreads'),
		'href' => $__templater->func('link', array('find-threads/contributed', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['findThreads']['children']['contributedThreads'] = $__navTemp;
						$__flat['contributedThreads'] =& $__tree['forums']['children']['findThreads']['children']['contributedThreads'];
					}
				}

				$__navTemp = [
		'title' => \XF::phrase('nav.unansweredThreads'),
		'href' => $__templater->func('link', array('find-threads/unanswered', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['forums']['children']['findThreads']['children']['unansweredThreads'] = $__navTemp;
					$__flat['unansweredThreads'] =& $__tree['forums']['children']['findThreads']['children']['unansweredThreads'];
				}

			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.watched'),
		'href' => $__templater->func('link', array('watched/threads', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['watched'] = $__navTemp;
				$__flat['watched'] =& $__tree['forums']['children']['watched'];
				if (empty($__tree['forums']['children']['watched']['children'])) { $__tree['forums']['children']['watched']['children'] = []; }

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.watchedThreads'),
		'href' => $__templater->func('link', array('watched/threads', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['watched']['children']['watchedThreads'] = $__navTemp;
						$__flat['watchedThreads'] =& $__tree['forums']['children']['watched']['children']['watchedThreads'];
					}
				}

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.watchedForums'),
		'href' => $__templater->func('link', array('watched/forums', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['watched']['children']['watchedForums'] = $__navTemp;
						$__flat['watchedForums'] =& $__tree['forums']['children']['watched']['children']['watchedForums'];
					}
				}

			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canSearch', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.searchForums'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'post', ), ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['searchForums'] = $__navTemp;
				$__flat['searchForums'] =& $__tree['forums']['children']['searchForums'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.markForumsRead'),
		'href' => $__templater->func('link', array('forums/mark-read', '-', array('date' => $__vars['xf']['time'], ), ), false),
		'attributes' => [
			'data-xf-click' => 'overlay',
		],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['markForumsRead'] = $__navTemp;
				$__flat['markForumsRead'] =& $__tree['forums']['children']['markForumsRead'];
			}
		}

	}

	$__navTemp = [
		'title' => \XF::phrase('nav.whatsNew'),
		'href' => $__templater->func('link', array('whats-new', ), false),
		'attributes' => [],
	];
	if ($__navTemp) {
		$__tree['whatsNew'] = $__navTemp;
		$__flat['whatsNew'] =& $__tree['whatsNew'];
		if (empty($__tree['whatsNew']['children'])) { $__tree['whatsNew']['children'] = []; }

		$__navTemp = [
		'title' => \XF::phrase('nav.whatsNewPosts'),
		'href' => $__templater->func('link', array('whats-new/posts', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
		if ($__navTemp) {
			$__tree['whatsNew']['children']['whatsNewPosts'] = $__navTemp;
			$__flat['whatsNewPosts'] =& $__tree['whatsNew']['children']['whatsNewPosts'];
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewMedia', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWhatsNewNewMedia'),
		'href' => $__templater->func('link', array('whats-new/media', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['xfmgWhatsNewNewMedia'] = $__navTemp;
				$__flat['xfmgWhatsNewNewMedia'] =& $__tree['whatsNew']['children']['xfmgWhatsNewNewMedia'];
			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewMedia', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWhatsNewMediaComments'),
		'href' => $__templater->func('link', array('whats-new/media-comments', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['xfmgWhatsNewMediaComments'] = $__navTemp;
				$__flat['xfmgWhatsNewMediaComments'] =& $__tree['whatsNew']['children']['xfmgWhatsNewMediaComments'];
			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewProfilePosts', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.whatsNewProfilePosts'),
		'href' => $__templater->func('link', array('whats-new/profile-posts', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['whatsNewProfilePosts'] = $__navTemp;
				$__flat['whatsNewProfilePosts'] =& $__tree['whatsNew']['children']['whatsNewProfilePosts'];
			}
		}

		if (($__vars['xf']['options']['enableNewsFeed'] AND $__vars['xf']['visitor']['user_id'])) {
			$__navTemp = [
		'title' => \XF::phrase('nav.whatsNewNewsFeed'),
		'href' => $__templater->func('link', array('whats-new/news-feed', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['whatsNewNewsFeed'] = $__navTemp;
				$__flat['whatsNewNewsFeed'] =& $__tree['whatsNew']['children']['whatsNewNewsFeed'];
			}
		}

		if ($__vars['xf']['options']['enableNewsFeed']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.latestActivity'),
		'href' => $__templater->func('link', array('whats-new/latest-activity', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['latestActivity'] = $__navTemp;
				$__flat['latestActivity'] =& $__tree['whatsNew']['children']['latestActivity'];
			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseItems', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.xaScWhatsNewNewShowcaseItems'),
		'href' => $__templater->func('link', array('whats-new/showcase-items', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['xaScWhatsNewNewShowcaseItems'] = $__navTemp;
				$__flat['xaScWhatsNewNewShowcaseItems'] =& $__tree['whatsNew']['children']['xaScWhatsNewNewShowcaseItems'];
			}
		}

		if (($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseItems', array()) AND $__templater->method($__vars['xf']['visitor'], 'canViewShowcaseComments', array()))) {
			$__navTemp = [
		'title' => \XF::phrase('nav.xaScWhatsNewShowcaseComments'),
		'href' => $__templater->func('link', array('whats-new/showcase-comments', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['xaScWhatsNewShowcaseComments'] = $__navTemp;
				$__flat['xaScWhatsNewShowcaseComments'] =& $__tree['whatsNew']['children']['xaScWhatsNewShowcaseComments'];
			}
		}

	}

	if ($__templater->method($__vars['xf']['visitor'], 'canViewMedia', array())) {
		$__navTemp = [
		'title' => \XF::phrase('nav.xfmg'),
		'href' => $__templater->func('link', array('media/albums/', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['xfmg'] = $__navTemp;
			$__flat['xfmg'] =& $__tree['xfmg'];
			if (empty($__tree['xfmg']['children'])) { $__tree['xfmg']['children'] = []; }

			$__navTemp = [
		'title' => \XF::phrase('nav.xfmgNewComments'),
		'href' => $__templater->func('link', array('whats-new/media-comments', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['xfmg']['children']['xfmgNewComments'] = $__navTemp;
				$__flat['xfmgNewComments'] =& $__tree['xfmg']['children']['xfmgNewComments'];
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgYourContent'),
		'href' => $__templater->func('link', array('media/users', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgYourContent'] = $__navTemp;
					$__flat['xfmgYourContent'] =& $__tree['xfmg']['children']['xfmgYourContent'];
					if (empty($__tree['xfmg']['children']['xfmgYourContent']['children'])) { $__tree['xfmg']['children']['xfmgYourContent']['children'] = []; }

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgYourMedia'),
		'href' => $__templater->func('link', array('media/users', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourMedia'] = $__navTemp;
							$__flat['xfmgYourMedia'] =& $__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourMedia'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgYourAlbums'),
		'href' => $__templater->func('link', array('media/albums/users', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourAlbums'] = $__navTemp;
							$__flat['xfmgYourAlbums'] =& $__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourAlbums'];
						}
					}

				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedContent'),
		'href' => $__templater->func('link', array('watched/media', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgWatchedContent'] = $__navTemp;
					$__flat['xfmgWatchedContent'] =& $__tree['xfmg']['children']['xfmgWatchedContent'];
					if (empty($__tree['xfmg']['children']['xfmgWatchedContent']['children'])) { $__tree['xfmg']['children']['xfmgWatchedContent']['children'] = []; }

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedMedia'),
		'href' => $__templater->func('link', array('watched/media', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedMedia'] = $__navTemp;
							$__flat['xfmgWatchedMedia'] =& $__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedMedia'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedAlbums'),
		'href' => $__templater->func('link', array('watched/media-albums', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedAlbums'] = $__navTemp;
							$__flat['xfmgWatchedAlbums'] =& $__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedAlbums'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedCategories'),
		'href' => $__templater->func('link', array('watched/media-categories', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedCategories'] = $__navTemp;
							$__flat['xfmgWatchedCategories'] =& $__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedCategories'];
						}
					}

				}
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canSearch', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgSearchMedia'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'xfmg_media', ), ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgSearchMedia'] = $__navTemp;
					$__flat['xfmgSearchMedia'] =& $__tree['xfmg']['children']['xfmgSearchMedia'];
				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgMarkViewed'),
		'href' => $__templater->func('link', array('media/mark-viewed', null, array('date' => $__vars['xf']['time'], ), ), false),
		'attributes' => [
			'data-xf-click' => 'overlay',
		],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgMarkViewed'] = $__navTemp;
					$__flat['xfmgMarkViewed'] =& $__tree['xfmg']['children']['xfmgMarkViewed'];
				}
			}

		}
	}

	if ($__templater->method($__vars['xf']['visitor'], 'canViewMemberList', array())) {
		$__navTemp = [
		'title' => \XF::phrase('nav.members'),
		'href' => $__templater->func('link', array('members', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['members'] = $__navTemp;
			$__flat['members'] =& $__tree['members'];
			if (empty($__tree['members']['children'])) { $__tree['members']['children'] = []; }

			if ($__vars['xf']['options']['enableMemberList']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.registeredMembers'),
		'href' => $__templater->func('link', array('members/list', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['members']['children']['registeredMembers'] = $__navTemp;
					$__flat['registeredMembers'] =& $__tree['members']['children']['registeredMembers'];
				}
			}

			$__navTemp = [
		'title' => \XF::phrase('nav.currentVisitors'),
		'href' => $__templater->func('link', array('online', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['members']['children']['currentVisitors'] = $__navTemp;
				$__flat['currentVisitors'] =& $__tree['members']['children']['currentVisitors'];
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canViewProfilePosts', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.newProfilePosts'),
		'href' => $__templater->func('link', array('whats-new/profile-posts', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
				if ($__navTemp) {
					$__tree['members']['children']['newProfilePosts'] = $__navTemp;
					$__flat['newProfilePosts'] =& $__tree['members']['children']['newProfilePosts'];
				}
			}

			if (($__templater->method($__vars['xf']['visitor'], 'canSearch', array()) AND $__templater->method($__vars['xf']['visitor'], 'canViewProfilePosts', array()))) {
				$__navTemp = [
		'title' => \XF::phrase('nav.searchProfilePosts'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'profile_post', ), ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['members']['children']['searchProfilePosts'] = $__navTemp;
					$__flat['searchProfilePosts'] =& $__tree['members']['children']['searchProfilePosts'];
				}
			}

		}
	}

	if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseItems', array())) {
		$__navTemp = [
		'title' => \XF::phrase('nav.xa_showcase'),
		'href' => $__templater->func('link', array('showcase', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['xa_showcase'] = $__navTemp;
			$__flat['xa_showcase'] =& $__tree['xa_showcase'];
			if (empty($__tree['xa_showcase']['children'])) { $__tree['xa_showcase']['children'] = []; }

			$__navTemp = [
		'title' => \XF::phrase('nav.xa_scNewItems'),
		'href' => $__templater->func('link', array('whats-new/showcase-items', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['xa_showcase']['children']['xa_scNewItems'] = $__navTemp;
				$__flat['xa_scNewItems'] =& $__tree['xa_showcase']['children']['xa_scNewItems'];
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseComments', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scNewComments'),
		'href' => $__templater->func('link', array('whats-new/showcase-comments', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scNewComments'] = $__navTemp;
					$__flat['xa_scNewComments'] =& $__tree['xa_showcase']['children']['xa_scNewComments'];
				}
			}

			$__navTemp = [
		'title' => \XF::phrase('nav.xa_scLatestContent'),
		'href' => $__templater->func('link', array('showcase/latest-reviews', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['xa_showcase']['children']['xa_scLatestContent'] = $__navTemp;
				$__flat['xa_scLatestContent'] =& $__tree['xa_showcase']['children']['xa_scLatestContent'];
				if (empty($__tree['xa_showcase']['children']['xa_scLatestContent']['children'])) { $__tree['xa_showcase']['children']['xa_scLatestContent']['children'] = []; }

				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scLlatestUpdates'),
		'href' => $__templater->func('link', array('showcase/latest-updates', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scLatestContent']['children']['xa_scLlatestUpdates'] = $__navTemp;
					$__flat['xa_scLlatestUpdates'] =& $__tree['xa_showcase']['children']['xa_scLatestContent']['children']['xa_scLlatestUpdates'];
				}

				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scLlatestReviews'),
		'href' => $__templater->func('link', array('showcase/latest-reviews', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scLatestContent']['children']['xa_scLlatestReviews'] = $__navTemp;
					$__flat['xa_scLlatestReviews'] =& $__tree['xa_showcase']['children']['xa_scLatestContent']['children']['xa_scLlatestReviews'];
				}

			}

			if ($__vars['xf']['options']['xaScEnableAuthorList']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scAuthorList'),
		'href' => $__templater->func('link', array('showcase/authors', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scAuthorList'] = $__navTemp;
					$__flat['xa_scAuthorList'] =& $__tree['xa_showcase']['children']['xa_scAuthorList'];
				}
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scSeries'),
		'href' => $__templater->func('link', array('showcase/series', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scSeries'] = $__navTemp;
					$__flat['xa_scSeries'] =& $__tree['xa_showcase']['children']['xa_scSeries'];
				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scYourContent'),
		'href' => $__templater->func('link', array('showcase/authors', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scYourContent'] = $__navTemp;
					$__flat['xa_scYourContent'] =& $__tree['xa_showcase']['children']['xa_scYourContent'];
					if (empty($__tree['xa_showcase']['children']['xa_scYourContent']['children'])) { $__tree['xa_showcase']['children']['xa_scYourContent']['children'] = []; }

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scYourPublishedItems'),
		'href' => $__templater->func('link', array('showcase/authors', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourPublishedItems'] = $__navTemp;
							$__flat['xa_scYourPublishedItems'] =& $__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourPublishedItems'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scYourItemsAwaitingPublishing'),
		'href' => $__templater->func('link', array('showcase/authors/awaiting-publishing', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourItemsAwaitingPublishing'] = $__navTemp;
							$__flat['xa_scYourItemsAwaitingPublishing'] =& $__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourItemsAwaitingPublishing'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scYourDrafts'),
		'href' => $__templater->func('link', array('showcase/authors/drafts', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourDrafts'] = $__navTemp;
							$__flat['xa_scYourDrafts'] =& $__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourDrafts'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scYourReviews'),
		'href' => $__templater->func('link', array('showcase/authors/reviews', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourReviews'] = $__navTemp;
							$__flat['xa_scYourReviews'] =& $__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourReviews'];
						}
					}

					if (($__vars['xf']['visitor']['user_id'] AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseSeriesPermission', array('createSeries', )))) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scYourSeries'),
		'href' => $__templater->func('link', array('showcase/series', $__vars['xf']['visitor'], array('creator_id' => $__vars['xf']['visitor']['user_id'], ), ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourSeries'] = $__navTemp;
							$__flat['xa_scYourSeries'] =& $__tree['xa_showcase']['children']['xa_scYourContent']['children']['xa_scYourSeries'];
						}
					}

				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scWatchedContent'),
		'href' => $__templater->func('link', array('watched/showcase-items', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scWatchedContent'] = $__navTemp;
					$__flat['xa_scWatchedContent'] =& $__tree['xa_showcase']['children']['xa_scWatchedContent'];
					if (empty($__tree['xa_showcase']['children']['xa_scWatchedContent']['children'])) { $__tree['xa_showcase']['children']['xa_scWatchedContent']['children'] = []; }

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scWatchedItems'),
		'href' => $__templater->func('link', array('watched/showcase-items', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scWatchedContent']['children']['xa_scWatchedItems'] = $__navTemp;
							$__flat['xa_scWatchedItems'] =& $__tree['xa_showcase']['children']['xa_scWatchedContent']['children']['xa_scWatchedItems'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scWatchedCategories'),
		'href' => $__templater->func('link', array('watched/showcase-categories', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scWatchedContent']['children']['xa_scWatchedCategories'] = $__navTemp;
							$__flat['xa_scWatchedCategories'] =& $__tree['xa_showcase']['children']['xa_scWatchedContent']['children']['xa_scWatchedCategories'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xa_scWatchedSeries'),
		'href' => $__templater->func('link', array('watched/showcase-series', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xa_showcase']['children']['xa_scWatchedContent']['children']['xa_scWatchedSeries'] = $__navTemp;
							$__flat['xa_scWatchedSeries'] =& $__tree['xa_showcase']['children']['xa_scWatchedContent']['children']['xa_scWatchedSeries'];
						}
					}

				}
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canSearch', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scSearchItems'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'sc_item', ), ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scSearchItems'] = $__navTemp;
					$__flat['xa_scSearchItems'] =& $__tree['xa_showcase']['children']['xa_scSearchItems'];
				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xa_scMarkRead'),
		'href' => $__templater->func('link', array('showcase/mark-read', null, array('date' => $__vars['xf']['time'], ), ), false),
		'attributes' => [
			'data-xf-click' => 'overlay',
		],
	];
				if ($__navTemp) {
					$__tree['xa_showcase']['children']['xa_scMarkRead'] = $__navTemp;
					$__flat['xa_scMarkRead'] =& $__tree['xa_showcase']['children']['xa_scMarkRead'];
				}
			}

		}
	}



	return [
		'tree' => $__tree,
		'flat' => $__flat
	];
};