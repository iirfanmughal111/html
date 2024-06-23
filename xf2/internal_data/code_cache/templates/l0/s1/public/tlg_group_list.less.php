<?php
// FROM HASH: 5544657179e20e556bcd93ec8376a3cc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('tlg_css_helper.less', $__vars) . '

.group--counterList {
  justify-content: space-between;
  .groups-displayFlex();
}

.groupListBlock {
  .groupList {
    margin-right: -@xf-paddingMedium + 1;
    margin-left: -@xf-paddingMedium + 1;
    flex-wrap: wrap;
  }

  &.groupListBlock--empty {
    .block-container {
        margin-bottom: @xf-paddingMedium;
      }
  }

  .block-filterBar {
    border-bottom-width: 0;
  }
}

.groupCover--wrapper {
  position: relative;

  .groupCoverFrame {
    height: 100%;
    font-size: 30px;
  }

  .groupCover--inlineMod {
    .m-dropShadow();
    background: rgba(0,0,0,.6);
    color: #ecf0ff;
    border-radius: 50%;

    position: absolute;
    top: @xf-paddingMedium;
    left: @xf-paddingMedium;
    width: 24px;
    height: 24px;
    font-size: 15px;
    padding-top: 2px;
    padding-left: 2px;
    text-align: center;
  }

  .iconic--noLabel {
    &:hover {
      cursor: pointer;
      input {
        + i:before {
          color: #FFFFFF;
        }
      }
    }
  }
}
.widget-groups {
  .contentRow-figure {
    .groupAvatar {
      .m-avatarSize(24px);
    }
  }
}

// 650px
@media(max-width: @xf-responsiveMedium) {
  .groupListBlock {
    border-radius: 0;
    border-left: none;
    border-right: none;

    .groupList {
      margin-right: -@xf-paddingLarge;
      margin-left: -@xf-paddingLarge;

      .gridCard {
        padding-left: 0;
        padding-right: 0;
      }
    }

    .gridCard--container {
      border-radius: 0;
      .gridCard--cover {
        .m-borderTopRadius(0);
      }
    }
  }
}
// 480px
@media(max-width: @xf-responsiveNarrow) {
}
';
	return $__finalCompiled;
}
);