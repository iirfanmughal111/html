<?php

namespace Siropu\Chat\Command;

class Roll
{
     protected static $defaultOptions = [
          'default_dice_count' => 1,
          'default_dice_sides' => 6,
          'max_dice_count'     => 1,
          'max_dice_sides'     => 6,
          'dice_sum'           => 0
     ];

     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if ($controller->isRoomChannel() && $controller->getChatSettings()['hide_bot'])
          {
               return $controller->message(\XF::phrase('siropu_chat_bot_hidden_error'));
          }

          $inputClean   = str_replace(['+', '-'], [' +', ' -'], $input);
          $roll         = $input ? array_map('trim', preg_split('/[\s,]/', $inputClean, null, PREG_SPLIT_NO_EMPTY)) : [];

          $diceCount    = $command->getOption('default_dice_count', self::$defaultOptions['default_dice_count']);
          $diceSides    = $command->getOption('default_dice_sides', self::$defaultOptions['default_dice_sides']);

          $diceMaxCount = $command->getOption('max_dice_count', self::$defaultOptions['max_dice_count']);
          $diceMaxSides = $command->getOption('max_dice_sides', self::$defaultOptions['max_dice_sides']);

          $diceSum      = $command->getOption('dice_sum', self::$defaultOptions['dice_sum']);

          $dice         = $diceCount . 'd' . $diceSides;
          $result       = [];

          if (isset($roll[0]) && preg_match('/([0-9]+)d([0-9]+)/i', $roll[0], $match))
          {
               $dice      = $match[0];
               $diceCount = (int) $match[1];
               $diceSides = (int) $match[2];

               if ($diceCount > $diceMaxCount || $diceSides > $diceMaxSides)
               {
                    return $controller->error(\XF::phrase('siropu_chat_roll_command_limit_error',
                         ['dice' => "{$diceMaxCount}d{$diceMaxSides}"]));
               }

               if ($diceCount < 1 || $diceSides < 1)
               {
                    return $controller->error(\XF::phrase('siropu_chat_roll_command_minimum_error'));
               }
          }

          for ($i = 0; $i < $diceCount; $i++)
          {
               $result[] = rand(1, $diceSides);
          }

          $diceTotalSum = array_sum($result);

          if (count($roll) >= 1)
          {
               $modPlus  = 0;
               $modMinus = 0;

               foreach ($roll as $modifier)
               {
                    if (strpos($modifier, '+') !== false)
                    {
                         $modPlus = (int) $modifier;
                    }
                    if (strpos($modifier, '-') !== false)
                    {
                         $modMinus = (int) $modifier;
                    }
               }

               $diceTotalSum += $modPlus - $modMinus;
          }

          if (!empty($modPlus))
          {
               $dice .= ' +' . $modPlus;
          }

          if (!empty($modMinus))
          {
               $dice .= ' -' . $modMinus;
          }

          $userId   = $messageEntity->message_user_id;
          $username = $messageEntity->message_username;

          if ($userId)
          {
               $user = '[USER=' . $userId . ']' . $username . '[/USER]';
          }
          else
          {
               $user = '(' . \XF::phrase('guest') . ') [B]' . $username . '[/B]';
          }

          $messageEntity->message_type = 'bot';
          $messageEntity->message_text = \XF::phrase('siropu_chat_roll_command_result', [
               'user'   => new \XF\PreEscaped($user),
               'dice'   => $dice,
               'result' => $diceSum ? $diceTotalSum : (implode(',', $result) . ' (' . $diceTotalSum . ')')
          ]);
     }
}
