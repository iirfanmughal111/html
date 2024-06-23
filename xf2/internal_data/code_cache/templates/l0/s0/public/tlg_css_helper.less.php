<?php
// FROM HASH: 1f22da9b6835e44e72b7a3671f70654c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.m-groupFlexCenter() {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.flex-row {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
}

// helper display flex.
.h-dFlex {
  .groups-displayFlex();
}

.h-dFlex--wrap {
  flex-wrap: wrap;
}

.groups-displayFlex() {
  display: flex;
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  flex-direction: row;
  flex-wrap: nowrap;
  flex-shrink: 1;
}

// loaders
/**
 * Lines
 */
@-webkit-keyframes line-scale {
  0% {
    -webkit-transform: scaley(1);
    transform: scaley(1); }
  50% {
    -webkit-transform: scaley(0.4);
    transform: scaley(0.4); }
  100% {
    -webkit-transform: scaley(1);
    transform: scaley(1); } }
@keyframes line-scale {
  0% {
    -webkit-transform: scaley(1);
    transform: scaley(1); }
  50% {
    -webkit-transform: scaley(0.4);
    transform: scaley(0.4); }
  100% {
    -webkit-transform: scaley(1);
    transform: scaley(1); } }

.loader--line-scale {
  > div {
    background-color: #fff;
    width: 4px;
    height: 35px;
    border-radius: 2px;
    margin: 2px;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    display: inline-block;

    &:nth-child(1) {
      -webkit-animation: line-scale 1s -0.4s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
      animation: line-scale 1s -0.4s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
    }

    &:nth-child(2) {
      -webkit-animation: line-scale 1s -0.3s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
      animation: line-scale 1s -0.3s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
    }

    &:nth-child(3) {
      -webkit-animation: line-scale 1s -0.2s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
      animation: line-scale 1s -0.2s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

    &:nth-child(4) {
      -webkit-animation: line-scale 1s -0.1s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
      animation: line-scale 1s -0.1s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

    &:nth-child(5) {
      -webkit-animation: line-scale 1s 0s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
      animation: line-scale 1s 0s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }
  }
}';
	return $__finalCompiled;
}
);