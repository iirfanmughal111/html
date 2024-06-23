<?php
// FROM HASH: a616c0f645d51a1af1ccf2a0e4867501
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.postItem {
  .postItem-inner {
    padding: @xf-paddingLarge;
  }

  .postItem-header {
    display: flex;
    flex-wrap: nowrap;

    .postItem-header--user {
      margin-left: @xf-paddingLarge;
      flex: 1;

      .attribution {
        margin: 0;
        padding: 0;
      }

      .message-attribution {
        border-bottom: none;
        padding-bottom: 0;
      }
    }
  }

  .postItem-footer {
    .postItem--comments {
      &.is-hidden {
        display: none;
      }

      position: relative;
      &:before {
        content: \'.\';
        border-top: 1px solid @xf-borderColor;
        display: block;
        margin-left: -@xf-paddingLarge;
        margin-right: -@xf-paddingLarge;
        font-size: 0;
        padding-top: @xf-paddingLarge;
      }
    }
  }

  .postItem-footer--actionBar {
    margin-top: @xf-paddingLarge;
    .actionBar-set--external,
    .actionBar-set--internal {
      margin-top: 0;
    }
  }
}

.postItem--comments {
  .comment {
    .comment-contentWrapper {
      margin-bottom: 0;
    }

    .comment-actionBar {
      .actionBar-set {
        margin-top: 0;
      }
    }
  }
}';
	return $__finalCompiled;
}
);