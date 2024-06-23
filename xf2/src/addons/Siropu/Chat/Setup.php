<?php

namespace Siropu\Chat;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
	{
		$this->schemaManager()->alterTable('xf_user', function(\XF\Db\Schema\Alter $table)
		{
			$table->addColumn('siropu_chat_room_id', 'int')->setDefault(1);
			$table->addColumn('siropu_chat_conv_id', 'int')->setDefault(0);
			$table->addColumn('siropu_chat_rooms', 'blob')->nullable();
			$table->addColumn('siropu_chat_conversations', 'blob')->nullable();
			$table->addColumn('siropu_chat_settings', 'blob')->nullable();
               $table->addColumn('siropu_chat_room_join_time', 'blob')->nullable();
			$table->addColumn('siropu_chat_status', 'varchar', 255)->setDefault('');
			$table->addColumn('siropu_chat_is_sanctioned', 'tinyint', 1)->setDefault(0);
			$table->addColumn('siropu_chat_message_count', 'int')->setDefault(0);
			$table->addColumn('siropu_chat_last_activity', 'int')->unsigned(false)->setDefault(-1);

			$table->addKey('siropu_chat_room_id');
			$table->addKey('siropu_chat_message_count');
			$table->addKey('siropu_chat_last_activity');
		});
	}
	public function installStep2()
	{
		$this->schemaManager()->createTable('xf_siropu_chat_room', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('room_id', 'int')->autoIncrement();
			$table->addColumn('room_user_id', 'int');
			$table->addColumn('room_name', 'varchar', 100);
			$table->addColumn('room_description', 'varchar', 255);
			$table->addColumn('room_password', 'varchar', 15);
               $table->addColumn('room_users',  'mediumblob');
			$table->addColumn('room_user_groups', 'blob');
               $table->addColumn('room_language_id', 'int')->setDefault(0);
               $table->addColumn('room_leave', 'int')->setDefault(1);
			$table->addColumn('room_readonly', 'tinyint', 1)->setDefault(0);
			$table->addColumn('room_locked', 'int')->setDefault(0);
               $table->addColumn('room_rss', 'tinyint', 1)->setDefault(0);
               $table->addColumn('room_max_users', 'int')->setDefault(0);
			$table->addColumn('room_prune', 'int')->setDefault(0);
               $table->addColumn('room_flood', 'int')->setDefault(0);
			$table->addColumn('room_thread_id', 'int')->setDefault(0);
               $table->addColumn('room_thread_reply', 'tinyint', 1)->setDefault(0);
               $table->addColumn('room_child_ids',  'blob');
			$table->addColumn('room_state', 'enum')->values(['visible', 'deleted'])->setDefault('visible');
			$table->addColumn('room_date', 'int');
			$table->addColumn('room_last_prune', 'int')->setDefault(0);
			$table->addColumn('room_user_count', 'int')->setDefault(0);
			$table->addColumn('room_last_activity', 'int')->setDefault(0);
               $table->addColumn('room_export_last_id', 'int')->setDefault(0);
			$table->addKey('room_user_id');
			$table->addKey('room_name');
			$table->addKey('room_prune');
			$table->addKey('room_user_count');
		});

		$this->schemaManager()->createTable('xf_siropu_chat_message', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('message_id', 'int')->autoIncrement();
			$table->addColumn('message_room_id', 'int')->setDefault(0);
			$table->addColumn('message_user_id', 'int');
			$table->addColumn('message_username', 'varchar', 50);
			$table->addColumn('message_bot_name', 'varchar', 50);
			$table->addColumn('message_type', 'varchar', 25)->setDefault('chat');
			$table->addColumn('message_type_id', 'int')->setDefault(0);
               $table->addColumn('message_type_category_id', 'int')->setDefault(0);
			$table->addColumn('message_is_ignored', 'int')->setDefault(0);
			$table->addColumn('message_text', 'text');
			$table->addColumn('message_recipients', 'blob');
			$table->addColumn('message_mentions', 'blob');
			$table->addColumn('message_like_users', 'blob');
			$table->addColumn('message_like_count', 'int')->setDefault(0);
			$table->addColumn('message_date', 'int');
			$table->addColumn('message_edit_count', 'int')->setDefault(0);
               $table->addColumn('reaction_score', 'int')->unsigned(false)->setDefault(0);
			$table->addColumn('reactions', 'blob')->nullable();
			$table->addColumn('reaction_users', 'blob');
			$table->addKey('message_room_id');
			$table->addKey('message_user_id');
			$table->addKey('message_type');
			$table->addKey('message_is_ignored');
			$table->addKey('message_like_count');
			$table->addKey('message_date');
		});

		$this->schemaManager()->createTable('xf_siropu_chat_conversation', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('conversation_id', 'int')->autoIncrement();
			$table->addColumn('user_1', 'int');
			$table->addColumn('user_2', 'int');
			$table->addColumn('start_date', 'int');
			$table->addColumn('user_left', 'int');
			$table->addKey(['user_1', 'user_2'], 'users');
		});

		$this->schemaManager()->createTable('xf_siropu_chat_conversation_message', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('message_id', 'int')->autoIncrement();
			$table->addColumn('message_conversation_id', 'int');
			$table->addColumn('message_user_id', 'int');
			$table->addColumn('message_username', 'varchar', 50);
			$table->addColumn('message_text', 'text');
			$table->addColumn('message_type', 'varchar', 25)->setDefault('chat');
			$table->addColumn('message_read', 'tinyint', 1)->setDefault(0);
			$table->addColumn('message_liked', 'tinyint', 1)->setDefault(0);
			$table->addColumn('message_date', 'int');
               $table->addColumn('reaction_score', 'int')->unsigned(false)->setDefault(0);
			$table->addColumn('reactions', 'blob')->nullable();
			$table->addColumn('reaction_users', 'blob');
			$table->addKey('message_conversation_id');
			$table->addKey('message_user_id');
			$table->addKey('message_read');
			$table->addKey('message_date');
		});

		$this->schemaManager()->createTable('xf_siropu_chat_bot_message', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('message_id', 'int')->autoIncrement();
			$table->addColumn('message_bot_name', 'varchar', 50);
			$table->addColumn('message_title', 'varchar', 100);
			$table->addColumn('message_text', 'text');
			$table->addColumn('message_rooms', 'blob');
			$table->addColumn('message_rules', 'blob');
			$table->addColumn('message_enabled', 'tinyint', 1)->setDefault(0);
		});

		$this->schemaManager()->createTable('xf_siropu_chat_bot_response', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('response_id', 'int')->autoIncrement();
			$table->addColumn('response_bot_name', 'varchar', 50);
			$table->addColumn('response_keyword', 'text');
			$table->addColumn('response_message', 'mediumtext');
			$table->addColumn('response_rooms', 'blob');
			$table->addColumn('response_user_groups', 'blob');
			$table->addColumn('response_settings', 'blob');
			$table->addColumn('response_last', 'blob');
			$table->addColumn('response_enabled', 'tinyint', 1)->setDefault(0);
		});

		$this->schemaManager()->createTable('xf_siropu_chat_command', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('command_name', 'varchar', 25);
			$table->addColumn('command_name_default', 'varchar', 25);
			$table->addColumn('command_description', 'text');
			$table->addColumn('command_callback_class', 'varchar', 100);
			$table->addColumn('command_callback_method', 'varchar', 75);
			$table->addColumn('command_rooms', 'blob');
			$table->addColumn('command_user_groups', 'blob');
			$table->addColumn('command_options_template', 'varchar', 50);
			$table->addColumn('command_options', 'blob');
			$table->addColumn('command_enabled', 'tinyint', 1)->setDefault(1);
			$table->addPrimaryKey('command_name');
		});

		$this->schemaManager()->createTable('xf_siropu_chat_user_command', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('command_id', 'int')->autoIncrement();
			$table->addColumn('command_user_id', 'int');
			$table->addColumn('command_name', 'varchar', 50);
			$table->addColumn('command_value', 'text');
			$table->addKey('command_user_id');
			$table->addKey('command_name');
		});

		$this->schemaManager()->createTable('xf_siropu_chat_sanction', function(\XF\Db\Schema\Create $table)
		{
			$table->addColumn('sanction_id', 'int')->autoIncrement();
			$table->addColumn('sanction_user_id', 'int');
			$table->addColumn('sanction_room_id', 'int');
			$table->addColumn('sanction_type', 'varchar', 5);
			$table->addColumn('sanction_start', 'int');
			$table->addColumn('sanction_end', 'int');
			$table->addColumn('sanction_author', 'int');
			$table->addColumn('sanction_reason', 'text');
			$table->addKey('sanction_user_id');
			$table->addKey('sanction_room_id');
			$table->addKey('sanction_type');
		});
	}
	public function installStep3()
	{
		$room = \XF::em()->create('Siropu\Chat:Room');
		$room->bulkSet([
			'room_name'        => 'General chit-chat',
			'room_description' => 'The place where you can chat about everything.'
		]);
		$room->save(false);
	}
	public function installStep4()
	{
		$commands = [
			'help' => [
                    'class'       => 'Help',
                    'description' => 'phrase:siropu_chat_command_help_explain',
                    'perms'       => []
               ],
			'join' => [
                    'class'       => 'Join',
                    'description' => 'phrase:siropu_chat_command_join_explain',
                    'perms'       => [2]
               ],
			'leave' => [
                    'class'       => 'Leave',
                    'description' => 'phrase:siropu_chat_command_leave_explain',
                    'perms'       => []
               ],
			'logout' => [
                    'class'       => 'Logout',
                    'description' => 'phrase:siropu_chat_command_logout_explain',
                    'perms'       => [2]
               ],
			'mute' => [
                    'class'       => 'Mute',
                    'description' => 'phrase:siropu_chat_command_mute_explain',
                    'perms'       => [3]
               ],
			'unmute' => [
                    'class'       => 'Unmute',
                    'description' => 'phrase:siropu_chat_command_unmute_explain',
                    'perms'       => [3]
               ],
			'kick' => [
                    'class'       => 'Kick',
                    'description' => 'phrase:siropu_chat_command_kick_explain',
                    'perms'       => [3]
               ],
			'unkick' => [
                    'class'       => 'Unkick',
                    'description' => 'phrase:siropu_chat_command_unkick_explain',
                    'perms'       => [3]
               ],
			'ban' => [
                    'class'       => 'Ban',
                    'description' => 'phrase:siropu_chat_command_ban_explain',
                    'perms'       => [3]
               ],
			'unban' => [
                    'class'       => 'Unban',
                    'description' => 'phrase:siropu_chat_command_unban_explain',
                    'perms'       => [3]
               ],
			'roll' => [
                    'class'       => 'Roll',
                    'description' => 'phrase:siropu_chat_command_roll_explain',
                    'template'    => 'siropu_chat_command_options_roll',
                    'perms'       => [],
               ],
			'giphy' => [
                    'class'       => 'Giphy',
                    'description' => 'phrase:siropu_chat_command_giphy_explain',
                    'template'    => 'siropu_chat_command_options_giphy',
                    'options'     => ['giphy_api_key' => '', 'rating' => ''],
                    'perms'       => [],
               ],
               'gif'    => [
                    'class'       => 'Gif',
                    'description' => 'phrase:siropu_chat_command_gif_explain',
                    'template'    => 'siropu_chat_command_options_gif',
                    'options'     => ['giphy_api_key' => '', 'rating' => '', 'tenor_api_key' => ''],
                    'perms'       => [],
               ],
			'me' => [
                    'class'       => 'Me',
                    'description' => 'phrase:siropu_chat_command_me_explain',
                    'perms'       => []
               ],
			'status' => [
                    'class'       => 'Status',
                    'description' => 'phrase:siropu_chat_command_status_explain',
                    'perms'       => [2]
               ],
			'whisper' => [
                    'class'       => 'Whisper',
                    'description' => 'phrase:siropu_chat_command_whisper_explain',
                    'perms'       => [2]
               ],
			'msg' => [
                    'class'       => 'Msg',
                    'description' => 'phrase:siropu_chat_command_msg_explain',
                    'perms'       => [2]
               ],
			'find' => [
                    'class'       => 'Find',
                    'description' => 'phrase:siropu_chat_command_find_explain',
                    'perms'       => []
               ],
			'prune'   => [
                    'class'       => 'Prune',
                    'description' => 'phrase:siropu_chat_command_prune_explain',
                    'perms'       => [3]
               ],
			'nick' => [
                    'class'       => 'Nick',
                    'description' => 'phrase:siropu_chat_command_nick_explain',
                    'perms'       => [1]
               ],
			'my' => [
                    'class'       => 'My',
                    'description' => 'phrase:siropu_chat_command_my_explain',
                    'perms'       => [2]
               ],
			'invite' => [
                    'class'       => 'Invite',
                    'description' => 'phrase:siropu_chat_command_invite_explain',
                    'perms'       => [2]
               ],
               'idle' => [
                    'class'       => 'Idle',
                    'description' => 'phrase:siropu_chat_command_idle_explain',
                    'perms'       => [3]
               ],
               'export' => [
                    'class'       => 'Export',
                    'description' => 'phrase:siropu_chat_command_export_explain',
                    'perms'       => [3]
               ],
               'slap' => [
                    'class'       => 'Slap',
                    'description' => 'phrase:siropu_chat_command_slap_explain',
                    'template'    => 'siropu_chat_command_options_slap',
                    'options'     => ['slap_objects' => ''],
                    'perms'       => [2]
               ],
               'hug' => [
                    'class'       => 'Hug',
                    'description' => 'phrase:siropu_chat_command_hug_explain',
                    'perms'       => [2]
               ],
		];

		foreach ($commands as $name => $data)
		{
			$command = \XF::em()->create('Siropu\Chat:Command');
			$command->bulkSet([
				'command_name'             => $name,
				'command_name_default'     => $name,
				'command_description'      => $data['description'],
				'command_callback_class'   => 'Siropu\Chat\Command\\' . $data['class'],
				'command_callback_method'  => 'run',
				'command_options_template' => $data['template'] ?? '',
                    'command_options'          => $data['options'] ?? [],
				'command_user_groups'      => $data['perms']
			]);
			$command->save(false);
		}
	}
	public function installStep5()
	{
		$this->createWidget('siropu_chat', 'siropu_chat', [
			'positions' => [
                    'siropu_chat_below_breadcrumb' => 10,
				'siropu_chat_above_forum_list' => 10,
				'siropu_chat_below_forum_list' => 10,
				'siropu_chat_above_content'    => 10,
				'siropu_chat_below_content'    => 10,
				'siropu_chat_sidebar_top'      => 10,
				'siropu_chat_sidebar_bottom'   => 10,
				'siropu_chat_all_pages'        => 10,
				'siropu_chat_page'             => 10
			]
		]);

		$this->createWidget('siropu_chat_rooms', 'siropu_chat_rooms', [
			'positions' => []
		]);

		$this->createWidget('siropu_chat_top_chatters', 'siropu_chat_top_chatters', [
			'positions' => []
		]);

		$this->createWidget('siropu_chat_users', 'siropu_chat_users', [
			'positions' => []
		]);
	}
     public function installStep6()
	{
		$this->app->repository('Siropu\Chat:Room')->rebuildRoomCache();
		$this->app->repository('Siropu\Chat:Command')->rebuildCommandCache();
	}
     public function installStep7()
	{
		$this->setDefaultActionSimpleCache();
	}
     public function upgrade2000970Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_flood', 'int')->setDefault(0);
          });
     }
     public function upgrade2001170Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_message', function(\XF\Db\Schema\Alter $table)
		{
               $table->changeColumn('message_type', 'varchar', 25)->setDefault('chat');
               $table->addKey('message_type_id');
          });
     }
     public function upgrade2001270Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_message', function(\XF\Db\Schema\Alter $table)
		{
               $table->changeColumn('message_type_id', 'int')->setDefault(0);
          });
     }
     public function upgrade2001670Step1()
	{
          $command = \XF::em()->create('Siropu\Chat:Command');
          $command->bulkSet([
               'command_name'             => 'idle',
               'command_name_default'     => 'idle',
               'command_description'      => 'phrase:siropu_chat_command_idle_explain',
               'command_callback_class'   => 'Siropu\Chat\Command\Idle',
               'command_callback_method'  => 'run',
               'command_options_template' => '',
               'command_user_groups'      => [3]
          ]);
          $command->save(false);
     }
     public function upgrade2001770Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_language_id', 'int')->setDefault(0);
          });
     }
     public function upgrade2001970Step1(array $stepParams)
     {
          $position = empty($stepParams[0]) ? 0 : $stepParams[0];

          $columns  = [
               'siropu_chat_rooms',
               'siropu_chat_conversations',
               'siropu_chat_settings'
          ];

          return $this->entityColumnsToJson('XF:User', $columns, $position, $stepParams);
     }
     public function upgrade2001970Step2(array $stepParams)
     {
          $position = empty($stepParams[0]) ? 0 : $stepParams[0];

          $columns  = [
               'message_recipients',
               'message_mentions',
               'message_like_users'
          ];

          return $this->entityColumnsToJson('Siropu\Chat:Message', $columns, $position, $stepParams);
     }
     public function upgrade2001970Step3(array $stepParams)
     {
          $serializedFields = [
               'Siropu\Chat:BotMessage'  => ['message_rooms', 'message_rules'],
               'Siropu\Chat:BotResponse' => ['response_rooms', 'response_user_groups', 'response_settings', 'response_last'],
               'Siropu\Chat:Command'     => ['command_rooms', 'command_user_groups', 'command_options'],
               'Siropu\Chat:Room'        => ['room_user_groups']
          ];

          foreach ($serializedFields as $entityName => $columns)
          {
               $this->entityColumnsToJson($entityName, $columns, 0, [], true);
          }
     }
     public function upgrade2002070Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_leave', 'int')->setDefault(1);
          });
     }
     public function upgrade2002370Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_thread_reply', 'tinyint', 1)->setDefault(0);
          });
     }
     public function upgrade2002370Step2()
	{
          $this->app->repository('Siropu\Chat:Room')->rebuildRoomCache();
     }
     public function upgrade2010070Step1()
	{
          $this->schemaManager()->alterTable('xf_user', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('siropu_chat_room_join_time', 'blob')->nullable();
          });
     }
     public function upgrade2010070Step2()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_message', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('reaction_score', 'int')->unsigned(false)->setDefault(0);
			$table->addColumn('reactions', 'blob')->nullable();
			$table->addColumn('reaction_users', 'blob');
          });
     }
     public function upgrade2010070Step3()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_conversation_message', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('reaction_score', 'int')->unsigned(false)->setDefault(0);
			$table->addColumn('reactions', 'blob')->nullable();
			$table->addColumn('reaction_users', 'blob');
          });
     }
     public function upgrade2010070Step4()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_users',  'mediumblob');
               $table->addColumn('room_child_ids',  'blob');
          });

          $this->app->repository('Siropu\Chat:Room')->rebuildRoomCache();
     }
     public function upgrade2010070Step5()
	{
          $this->setDefaultActionSimpleCache();
     }
     public function upgrade2010370Step1()
	{
          $this->db()->update('xf_content_type_field', ['field_value' => 'Siropu\Chat\Alert\Room'], 'content_type = ? AND field_name = ?', ['siropu_chat_room', 'alert_handler_class']);

          $this->db()->update('xf_content_type_field', ['field_value' => 'Siropu\Chat\Alert\RoomMessage'], 'content_type = ? AND field_name = ?', ['siropu_chat_room_message', 'alert_handler_class']);

          $this->db()->update('xf_content_type_field', ['field_value' => 'Siropu\Chat\Alert\ConversationMessage'], 'content_type = ? AND field_name = ?', ['siropu_chat_conv_message', 'alert_handler_class']);
     }
     public function upgrade2010370Step2()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_message', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('message_type_category_id', 'int')->setDefault(0);
          });
     }
     public function upgrade2011370Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_rss', 'tinyint', 1)->setDefault(0);
          });
     }
     public function upgrade2011870Step1()
	{
          $giphy = \XF::em()->find('Siropu\Chat:Command', 'giphy');
          $giphy->command_options_template = 'siropu_chat_command_options_giphy';
          $giphy->save(false);
     }
     public function upgrade2012070Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_export_last_id', 'int')->setDefault(0);
          });
     }
     public function upgrade2012070Step2()
	{
          $command = \XF::em()->create('Siropu\Chat:Command');
          $command->bulkSet([
               'command_name'             => 'export',
               'command_name_default'     => 'export',
               'command_description'      => 'phrase:siropu_chat_command_export_explain',
               'command_callback_class'   => 'Siropu\Chat\Command\Export',
               'command_callback_method'  => 'run',
               'command_user_groups'      => [3]
          ]);
          $command->save(false);
     }

     public function upgrade2012270Step1()
	{
          $command = \XF::em()->create('Siropu\Chat:Command');
          $command->bulkSet([
               'command_name'             => 'slap',
               'command_name_default'     => 'slap',
               'command_description'      => 'phrase:siropu_chat_command_slap_explain',
               'command_options_template' => 'siropu_chat_command_options_slap',
               'command_options'          => ['slap_objects' => ''],
               'command_callback_class'   => 'Siropu\Chat\Command\Slap',
               'command_callback_method'  => 'run'
          ]);
          $command->save(false);

          $command = \XF::em()->create('Siropu\Chat:Command');
          $command->bulkSet([
               'command_name'             => 'hug',
               'command_name_default'     => 'hug',
               'command_description'      => 'phrase:siropu_chat_command_hug_explain',
               'command_callback_class'   => 'Siropu\Chat\Command\Hug',
               'command_callback_method'  => 'run'
          ]);
          $command->save(false);
     }
     public function upgrade2012370Step1()
	{
          $this->schemaManager()->alterTable('xf_siropu_chat_room', function(\XF\Db\Schema\Alter $table)
		{
               $table->addColumn('room_max_users', 'int')->setDefault(0);
          });
     }
     public function upgrade2020570Step1()
	{
          $command = \XF::em()->create('Siropu\Chat:Command');
          $command->bulkSet([
               'command_name'             => 'gif',
               'command_name_default'     => 'gif',
               'command_description'      => 'phrase:siropu_chat_command_gif_explain',
               'command_options_template' => 'siropu_chat_command_options_gif',
               'command_options'          => ['giphy_api_key' => '', 'rating' => '', 'tenor_api_key' => ''],
               'command_callback_class'   => 'Siropu\Chat\Command\Gif',
               'command_callback_method'  => 'run'
          ]);
          $command->save(false);
     }
     public function postUpgrade($previousVersion, array &$stateChanges)
	{
		if ($this->applyDefaultPermissions($previousVersion))
		{
			$this->app->jobManager()->enqueueUnique(
				'permissionRebuild',
				'XF:PermissionRebuild',
				[],
				false
			);
		}

          if ($previousVersion < 2010670)
		{
               $job      = 'Siropu\Chat:Reaction';
               $uniqueId = 'PostUpgrade' . $job;
               $id       = \XF::app()->jobManager()->enqueueUnique($uniqueId, $job);
               $router   = \XF::app()->router('admin');

               return \XF::app()->response()->redirect(
                    $router->buildLink('tools/run-job', null, ['only_id' => $id, '_xfRedirect' => $router->buildLink('add-ons')])
               );
          }
	}
	public function uninstallStep1(array $stepParams = [])
	{
		$this->schemaManager()->alterTable('xf_user', function(\XF\Db\Schema\Alter $table)
		{
			$table->dropColumns([
				'siropu_chat_room_id',
				'siropu_chat_conv_id',
				'siropu_chat_rooms',
				'siropu_chat_conversations',
				'siropu_chat_settings',
                    'siropu_chat_room_join_time',
				'siropu_chat_status',
				'siropu_chat_is_sanctioned',
				'siropu_chat_message_count',
				'siropu_chat_last_activity'
			]);
		});
	}
	public function uninstallStep2(array $stepParams = [])
	{
          $sm = $this->schemaManager();

		$sm->dropTable('xf_siropu_chat_room');
		$sm->dropTable('xf_siropu_chat_message');
		$sm->dropTable('xf_siropu_chat_conversation');
		$sm->dropTable('xf_siropu_chat_conversation_message');
		$sm->dropTable('xf_siropu_chat_bot_message');
		$sm->dropTable('xf_siropu_chat_bot_response');
		$sm->dropTable('xf_siropu_chat_command');
		$sm->dropTable('xf_siropu_chat_user_command');
		$sm->dropTable('xf_siropu_chat_sanction');
	}
	public function uninstallStep3()
	{
		$this->db()->delete('xf_attachment', 'content_type = ?', 'siropu_chat');
          $this->db()->delete('xf_reaction_content', 'content_type = ?', 'siropu_chat_room_message');
          $this->db()->delete('xf_reaction_content', 'content_type = ?', 'siropu_chat_conv_message');
	}
     protected function applyDefaultPermissions($previousVersion = null)
	{
		$applied = false;

		if ($previousVersion && $previousVersion < 2010570)
		{
			$this->applyGlobalPermission('siropuChat', 'browseRooms');

			$applied = true;
		}

		return $applied;
	}
     protected function setDefaultActionSimpleCache()
     {
          $simpleCache = \XF::app()->simpleCache();
          $simpleCache['Siropu/Chat']['actions'] = [
               'rooms'         => [],
               'conversations' => []
          ];
     }
}
