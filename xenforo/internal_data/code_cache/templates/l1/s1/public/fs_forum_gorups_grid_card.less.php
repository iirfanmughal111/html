<?php
// FROM HASH: cef170c9835862c028f38b1e31a2e75e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('fs_forum_gorups_css_helper.less', $__vars) . '

.gridCard {
  flex: 0 0 33.33%;
  padding: @xf-paddingMedium;
  min-width: 33.33%;
  align-self: stretch;
  position: relative;
  .m-transition();

  .fa {
    margin-right: 4px;
  }

  &.is-mod-selected {
    opacity: 0.5;
  }

  .groupCover--wrapper {
    position: relative;
    .badge {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 12px;
      .m-dropShadow();
    }
  }
}

// header
.gridCard--header {
  display: flex;
  background-color: @xf-contentHighlightBg;
  border-bottom: 1px solid @xf-borderColorLight;

  .gridCard--header--avatar {
    >* {
      .m-avatarSize(60px);
    }
  }

  .gridCard--header--main {
    flex: 1;
    margin-left: @xf-paddingMedium;
    margin-right: @xf-paddingMedium;
  }

  .gridCard--header--actions {
    min-width: 50px;
  }
}

.gridCard--container {
  height: 100%;
  background: @xf-contentBg;
  border-radius: @xf-borderRadiusSmall;
  display: flex;
  flex-direction: column;

  .m-dropShadow();

  .gridCard--cover {
    height: 100px;
    overflow: hidden;

    .m-borderTopRadius(@xf-borderRadiusSmall);

    >* {
      height: 100px;
      font-size: 30px;
    }
  }

  .groupAvatar--img {
    width: 100%;
    height: 100%;
  }

  > * {
    &:not(.gridCard--cover) {
      padding: @xf-paddingMedium;
    }
  }
}

.gridCard--body {
  display: flex;
  flex-grow: 1;
  flex-direction: column;
}

.gridCard--footer {
  border-top: 1px solid @xf-borderColorLight;
  display: flex;
  flex-direction: row;

  .groupItem--members {
    flex: 1;
    margin: 0 !important;
    overflow-x: hidden;
    .groups-displayFlex();

    li {
      + li {
        margin-left: -10px;
      }

      .avatar {
        border:2px solid #fff;
      }
    }
  }

  .button--groupJoin {
    align-self: flex-start;
  }
}

.gridCard--header--title {
  font-weight: normal;
  font-size: @xf-fontSizeLarge;
  display: flex;
  align-items: center;
  flex-direction: row;
  .badge {
    font-size: 0;
    width: 10px;
    height: 10px;
    box-sizing: border-box;
    border-radius: 5px;
    margin-left: @xf-paddingMedium;
  }
}

.groupItem--meta {
  font-size: @xf-fontSizeSmaller;
}

.gridCardFlexColLoop(5);

// 900px
@media(max-width: @xf-responsiveWide) {
  .gridCardList--flex--3-col {
    .gridCard {
      flex-basis: 50%;
      min-width: 50%;
    }
  }
}

// 650px
@media(max-width: @xf-responsiveMedium) {
  .gridCardList--flex--3-col,
  .gridCardList--flex--2-col {
    .gridCard {
      flex-basis: 100%;
      min-width: 100%;
    }
  }
}
// 480px
@media(max-width: @xf-responsiveNarrow) {
  .gridCardList--flex{
    .gridCard {
      flex-basis: 100%;
      min-width: 100%;
    }
  }
}

// Auto generate grid card columns class
.gridCardFlexColLoop(@n) when (@n > 0) {
  .gridCardList--flex--@{n}-col {
    .gridCard {
      flex-basis: (100% / @n);
      min-width: (100% / @n);
    }
  }

  .gridCardFlexColLoop((@n - 1));
}';
	return $__finalCompiled;
}
);