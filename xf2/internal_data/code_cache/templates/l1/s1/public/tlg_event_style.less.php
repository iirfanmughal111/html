<?php
// FROM HASH: c338a78ce5638b3a01aff479921ebc71
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.eventItem-date {
  display: flex;
  flex-direction: column;

  .eventItem-date--day {
    font-size: @xf-fontSizeLarger;
    color: @xf-textColorAttention;
  }
}


.countdown-wrapper {
  display: flex;
  justify-content: center;

  .countdown-tick {
    flex: 0 1 25%;
    min-width: 25%;
    padding: 5px;
    box-sizing: border-box;

    .countdown-tick--wrapper {
      border: 1px solid @xf-borderColor;
      border-radius: 6px;
      padding: @xf-paddingLarge;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: #FFF;
    }

    &--number {
      font-size: @xf-fontSizeLargest * 1.4;
      color: @xf-textColorEmphasized;
    }

    &--text {
      font-size: @xf-fontSizeSmall;
      color: @xf-textColorMuted;
    }
  }
}

.blockMessage--eventNotice {
  margin-bottom: 10px;
}

.event-input-group {
  display: flex;
  flex-wrap: nowrap;

  .date-input--field {
    margin-left: @xf-paddingMedium;

    &[type="number"] {
      max-width: 80px;
    }
  }

  .inputGroup--date {
    min-width: 150px;
    .date-input--field {
      margin-left: 0;
    }
  }
}

.block-eventDescription {
  .message--simple {
    .message-body {
      margin-top: 0;
    }
  }
  .actionBar-set {
    &.actionBar-set--internal {
      margin-left: 0;
    }
  }
  .message-responses {
    display: none;
  }
}

// widget events
.p-body-sidebar {
  .widget-events {
    .eventItem-date {
      display: flex;
      flex-direction: column;
      flex-wrap: wrap;
    }
  }
}
';
	return $__finalCompiled;
}
);