<?php
// FROM HASH: b81e6ed4b390cea070cbd313c33b3eaa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('tlg_css_helper.less', $__vars) . '

// declare variables.
@groupNavItemHeight: 42px;

.groupViewNav--item {
  &:hover, &.is-active {
    background-color: @xf-paletteColor1;
    a {
      text-decoration: none;
      .xf-link();
    }
  }
}

.groupViewNav {
  .groupViewNav--item {
    a {
      padding: @xf-paddingLarge;
      display: flex;

      .groupViewNav--itemText {
        flex: 1;
      }
    }

    &:last-child {
      a {
        .m-borderBottomRadius(@xf-borderRadiusMedium - 1);
      }
    }

    &.is-active {
      a {
        border-left: 2px solid @xf-borderColorFeature;
      }
    }

    .badge {
      align-self: center;
    }
  }
}

.groupCover-header {
  .groupCover {
    .m-borderTopRadius(@xf-borderRadiusSmall + 1);
  }
}

.groupCoverFrame {
  width: 100%;
  height: 205px;
  overflow: hidden;
  position: relative;

  .m-transition(height, 200ms, ease-in);

  &.groupCoverFrame--setup {
    background-color: @xf-contentAccentBg;
    .loader--line-scale {
      > div {
        background-color: @xf-textColorAccentContent;
      }
    }
  }

  a {
    &:hover {
      text-decoration: none;
    }
  }

  .groupCover--img {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;

    .m-transition(top);
  }

  &.groupCoverFrame--setup {
    .groupCover--img {
      .m-transition(none);
    }
  }

  .groupCover--guide {
    position: absolute;
    font-size: @xf-fontSizeSmall;
    padding: @xf-paddingMedium @xf-paddingLarge;
    background-color: rgba(0,0,0,.4);
    border-radius: @xf-borderRadiusMedium;
    color: @xf-paletteNeutral1;

    .m-dropShadow();

    &:before {
      .m-faBase();
      .m-faContent("\\f047");
      margin-right: @xf-paddingSmall;
    }
  }

  // fixed flash text while cover editor
  .cropControls {
    display: none;
  }
}

.timePicker--wrap {
  display: block;
  padding: @xf-paddingLarge;
  box-sizing: border-box;

  .timePicker--wrap--text {
    font-size: @xf-fontSizeNormal;
  }
}

.flex--row {
  display: flex;
  flex-direction: row;
}

.flex--grow {
  flex-grow: 1;
}

// Group Prevew Customized
.tooltip--preview {
  .tooltip-content {
    .tooltip-expanded {
      max-height: 255px;
      margin: -@xf-paddingMedium;

      .memberTooltip-avatar {
        padding-right: 10px;
      }

      .groupCoverFrame {
        height: 90px;
        font-size: 25px;
      }

      .memberTooltip-actions {
        display: flex;
      }
    }

    .memberTooltip-name {
      font-size: @xf-fontSizeLarge;
    }

    .groupAvatar {
      .m-avatarSize(50px);
    }
  }
}

.groupCoverFrame,
.groupAvatar--default {
  .m-groupFlexCenter();

  font-size: 50px;
}

// Group avatar default (text dynamic)
.groupAvatar {
  display: flex;
  border-radius: @xf-tlg_avatarBorderRadius;
  overflow: hidden;

  &:hover {
    text-decoration: none;
  }

  img {
    width: 100%;
  }
}

// SIDEBAR GROUP AVATAR
.contentRow-figure {
  .groupAvatar {
    .m-avatarSize(24px);
    border-radius: @xf-tlg_avatarBorderRadius;
  }
}

.groupAvatar--default {
  text-decoration: none;
}

.p-body-sideNav {
  .groupAvatar {
    .m-avatarSize(@xf-sidebarWidth);
  }
}

.p-body-sideNavInner {
  &.is-active,
  &.is-transitioning {
    .groupAvatar-block {
      .groupAvatar {
        width: 100%;
        height: 100%;
        border-radius: 0;
      }
    }
  }
}

.groupHeader-navList {
  .groupViewNav--item, .groupHeader-navList--user {
    padding: @xf-paddingMedium;
  }

  .hScroller-action {
    .m-hScrollerActionColorVariation(@xf-contentHighlightBg, @xf-textColorFeature, @xf-textColorEmphasized);
  }

  .groupViewNav--item {
    height: @groupNavItemHeight;
    box-sizing: border-box;
    line-height: (@groupNavItemHeight + @xf-paddingMedium * 2)/2;
    padding-left: @xf-paddingLargest;
    padding-right: @xf-paddingLargest;

    &.is-active {
      border-bottom: 2px solid @xf-borderColorFeature;
      background-color: @xf-paletteColor1;

      a {
        background-color: transparent;
      }
    }
  }

  .groupHeader-navList--user {
    min-width: 200px;
    justify-content: flex-end;
  }
}

// template: tl_groups_bb_code_group.html
.groupBbCode--wrapper {
  max-width: 320px;

  .listInline {
    margin: 0 !important;
  }
}

.groupMembers {
  .contentRow {
    .avatar {
      .m-avatarSize(66px);
    }
  }
}

// 900px
@media (min-width: @xf-responsiveWide) {
  .groupHeader-navList {
    .hScroller {
      display: none !important;
    }

    .groupHeader-navList--user {
      justify-content: flex-start;
    }
  }
}

// 900px
@media (max-width: @xf-responsiveWide) {
  .groupHeader-navList {
    .hScroller {
      display: block !important;
    }
  }

  .groupCover-header {
    position: relative;
    .groupCover {
      .m-borderTopRadius(0);
    }

    .groupHeader-navList--user {
      position: absolute;
      bottom: @groupNavItemHeight;
      right: 0;

      .button--groupJoin,
      .button--groupAlerts {
        display: none;
      }

      .button {
        &.menuTrigger {
          border: 1px solid @xf-borderColor;
          border-top-left-radius: @xf-borderRadiusMedium !important;
          border-bottom-left-radius: @xf-borderRadiusMedium !important;
        }
      }
    }
  }
}

.tooltip-group--inner {
  .memberTooltip-avatar {
    width: 60px;
  }
}

.groupBadge {
  display: flex;
  align-items: center;
  padding: @xf-paddingMedium;
  background-color: @xf-inlineModHighlightColor;
  border-radius: @xf-borderRadiusMedium;

  &.has-cover {
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    position: relative;
  }

  .groupBadge-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,.3);
    border-radius: @xf-borderRadiusMedium;
  }

  .groupAvatar {
    width: 28px;
    height: 28px;
    min-width: 28px;
    z-index: 1;
  }

  .groupBadge-name {
    margin-left: @xf-paddingMedium;
    z-index: 1;
    color: #fff;
    text-overflow: ellipsis;
    overflow: hidden;
    width: ~"calc(100% - 28px)";
    white-space: nowrap;
    &:hover {
      text-decoration: none;
    }
  }
}

.group-qrcode--block {
  .tlg-group--qrcode {
    text-align: center;
  }
}

// tlg_media_album_add_chooser.html
.linkAlbums-fields {
  .input {
    + .input {
      margin-top: @xf-paddingLarge;
    }
  }
}';
	return $__finalCompiled;
}
);