<?php

namespace Z61\Classifieds;

use SV\Utils\InstallerHelper;
use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;
use XF\Finder\Phrase;
use Z61\Classifieds\Install\Data\MySql;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	use InstallerHelper;

    public function installStep1()
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() as $tableName => $callback)
        {
            $sm->createTable($tableName, $callback);
            $sm->alterTable($tableName, $callback);
        }

        $this->insertDefaultData();
    }

    public function installStep2()
    {
        $sm = $this->schemaManager();

        $sm->alterTable('xf_user', function(Alter $table)
        {
            $table->addColumn('z61_classifieds_listing_count', 'int')->setDefault(0);
            $table->addKey('z61_classifieds_listing_count', 'listing_count');
        });
        $sm->alterTable('xf_forum', function($table) {
            $this->addOrChangeColumn($table, 'z61c_replace_action_btn', 'tinyint', 3)->setDefault(1);
        });
    }

    public function installStep3()
    {
        $this->createWidget(
            'z61_classifieds_latest_listings_overview',
            'classifieds_new_listings',
            [
                'positions' => [
                    'whats_new_overview' => 150
                ],
                'options' => [
                    'style' => 'full',
                    'limit' => 10
                ]
            ],
            'Latest classifieds listings'
        );
    }

    public function installStep4()
    {
        foreach ($this->getPhrases() AS $title => $text)
        {
            /** @var \XF\Entity\Phrase $phrase */
            $phrase = \XF::finder('XF:Phrase')
                ->where('title', '=', $title)
                ->where('language_id', '=', 0)
                ->fetchOne();
            if (!$phrase)
            {
                $phrase = \XF::em()->create('XF:Phrase');
                $phrase->title = $title;
                $phrase->phrase_text = $text;
                $phrase->language_id = 0;
                $phrase->save();
            }
        }
    }

    public function upgrade1000370Step1()
    {
        $sm = $this->schemaManager();

        $sm->alterTable('xf_z61_classifieds_category', function(Alter $table)
        {
            $this->addOrChangeColumn($table, 'require_listing_image', 'tinyint');
            $this->addOrChangeColumn($table, 'layout_type', 'varchar', 20)->setDefault('list_view');
        });

        $sm->alterTable('xf_z61_classifieds_listing', function(Alter $table)
        {
            $this->addOrChangeColumn($table, 'cover_image_id', 'int')->nullable();
        });
    }

    public function upgrade1000530Step1()
    {
        $sm = $this->schemaManager();
        $sm->alterTable('xf_z61_classifieds_listing', function(Alter $table) {
            $this->addOrChangeColumn($table, 'sold_user_id', 'int')->nullable();
            $this->addOrChangeColumn($table, 'sold_username', 'varchar', 50)->nullable();
        });

        $sm->alterTable('xf_z61_classifieds_category', function(Alter $table) {
            $this->addOrChangeColumn($table, 'require_sold_user', 'tinyint')->setDefault(1);
        });

        $sm->createTable('xf_z61_classifieds_feedback', function(Create $table) {
            $this->addOrChangeColumn($table, 'feedback_id', 'int')->autoIncrement();
            $this->addOrChangeColumn($table, 'from_user_id', 'int');
            $this->addOrChangeColumn($table, 'from_username', 'varchar', 50);
            $this->addOrChangeColumn($table,'to_user_id', 'int');
            $this->addOrChangeColumn($table,'to_username', 'varchar', 50);
            $this->addOrChangeColumn($table,'listing_id', 'int')->nullable();
            $this->addOrChangeColumn($table,'feedback', 'varchar', 80);
            $this->addOrChangeColumn($table,'rating', 'enum')->values([
                'positive', 'neutral', 'negative'
            ]);
            $this->addOrChangeColumn($table, 'role', 'enum')->values([
                'buyer', 'seller', 'trader'
            ]);
            $this->addOrChangeColumn($table,'feedback_date', 'int');
        });

        $sm->createTable('xf_z61_classifieds_user_feedback', function(Create $table) {
            $this->addOrChangeColumn($table,'user_id', 'int');
            $this->addOrChangeColumn($table,'positive', 'int')->setDefault(0);
            $this->addOrChangeColumn($table,'neutral', 'int')->setDefault(0);
            $this->addOrChangeColumn($table,'negative', 'int')->setDefault(0);
            $this->addOrChangeColumn($table,'total', 'int')->setDefault(0);
            $this->addOrChangeColumn($table,'last_feedback_date', 'int');
        });
    }

    /**
     * Removes broken purchasable and adds new one
     */
    public function upgrade1000530Step2()
    {
        $this->db()->insert('xf_purchasable', [
            'purchasable_type_id' => 'z61_classifieds_listing',
            'purchasable_class' => "Z61\\Classifieds:Listing",
            'addon_id' => 'Z61/Classifieds'
        ], true);
    }

    public function upgrade1000630Step1()
    {
        $sm = $this->schemaManager();
        $sm->alterTable('xf_forum', function($table) {
            $this->addOrChangeColumn($table, 'z61c_replace_action_btn', 'tinyint', 3)->setDefault(1);
        });

        if (\XF::$versionId >= 2010000 && $this->addOn->getInstalledAddOn()->version_id == 1000530)
        {
            $sm->alterTable('xf_z61_classifieds_listing', function(Alter $table)
            {
                $table->dropColumns('reactions');
                $table->changeColumn('reaction_score')->type('int')->unsigned(false)->renameTo('likes');
                $table->renameColumn('reaction_users', 'like_users');
            });         
        }
    }

    public function upgrade1000730Step1()
    {
        $sm = $this->schemaManager();
        $sm->alterTable('xf_z61_classifieds_feedback', function($table) {
            $this->addOrChangeColumn($table, 'last_edit_date', 'int')->setDefault(0);
            $this->addOrChangeColumn($table, 'last_edit_user_id', 'int')->setDefault(0);
        });
        $sm->alterTable('xf_z61_classifieds_category', function($table) {
            $this->addOrChangeColumn($table, 'replace_forum_action_button', 'tinyint', 3)->setDefault(1);
        });
    }

    public function upgrade1000830Step1()
    {
        $this->migrateTableToReactions('xf_z61_classifieds_listing');
    }

    public function upgrade1000830Step2()
    {
        $this->renameLikeAlertOptionsToReactions(['classifieds_listing']);
    }

    public function upgrade1000830Step3()
    {
        $this->renameLikeAlertsToReactions(['classifieds_listing']);
    }

    public function upgrade1000830Step4()
    {
        $this->renameLikePermissionsToReactions([
            'classifieds' => true
        ], 'like');

        $this->renameLikeStatsToReactions(['classifieds_listing']);
    }

    public function upgrade1000930Step1()
    {
        $this->schemaManager()->alterTable('xf_z61_classifieds_category', function($table) {
            $this->addOrChangeColumn($table, 'exclude_expired', 'tinyint')->setDefault(1);
            $this->addOrChangeColumn($table, 'phrase_listing_type', 'varchar', 60)->setDefault('z61_classifieds_type');
            $this->addOrChangeColumn($table, 'phrase_listing_condition', 'varchar', 60)->setDefault('z61_classifieds_condition');
            $this->addOrChangeColumn($table, 'phrase_listing_price', 'varchar', 60)->setDefault('price');
        });
    }

    public function upgrade1001030Step1()
    {
        $this->app->jobManager()->enqueueUnique(
            'z61ClassifiedsRebuildCategoryListingCounts',
            'Z61\Classifieds:Category',
            ['type' => 'classifieds_category'],
            false
        );

        $this->createWidget(
            'z61_classifieds_latest_listings_overview',
            'classifieds_new_listings',
            [
                'positions' => [
                    'whats_new_overview' => 150
                ],
                'options' => [
                    'style' => 'full',
                    'limit' => 10
                ]
            ],
            'Latest classifieds listings'
        );
    }

    public function upgrade1001230Step1()
    {
        $this->schemaManager()->alterTable('xf_z61_classifieds_category', function (Alter $table)
        {
            $this->addOrChangeColumn($table, 'listing_template', 'mediumtext');
        });
    }

    public function upgrade1001230Step2()
    {
        $serializedFields = [
			'Z61\Classifieds:Listing' => ['custom_fields', 'tags', 'listing_location_data', 'embed_metadata'],
            'Z61\Classifieds:Category' => ['field_cache', 'prefix_cache', 'breadcrumb_data'],

        ];
        foreach ($serializedFields AS $entityName => $columns)
        {
            $this->entityColumnsToJson($entityName, $columns, 0, [], true);
        }
    }

    public function upgrade1001270Step1()
    {
        $this->schemaManager()->alterTable('xf_z61_classifieds_listing_field', function(Alter $table) {
            $table->dropColumns('user_editable');
        });
    }

    public function postInstall(array &$stateChanges)
    {
        $this->app->jobManager()->enqueueUnique(
            'phraseRebuild',
            'XF:PhraseRebuild',
            [],
            false
        );
    }

    public function postUpgrade($previousVersion, array &$stateChanges)
    {
        /** @var \Z61\Classifieds\Repository\ListingField  $listingFieldRepo */
        $listingFieldRepo = \XF::repository('Z61\Classifieds:ListingField');
        $listingFieldRepo->rebuildFieldCache();
    }

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();

        foreach (array_keys($this->getTables()) AS $tableName)
        {
            $sm->dropTable($tableName);
        }
    }

    public function uninstallStep2()
    {
        $sm = $this->schemaManager();

        $sm->alterTable('xf_user', function(Alter $table)
        {
            $table->dropColumns('z61_classifieds_listing_count');
        });

        $sm->alterTable('xf_forum', function(Alter $table) {
            $table->dropColumns('z61c_replace_action_btn');
        });
    }

    public function uninstallStep3()
    {
        $db = $this->db();

        $contentTypes = ['classifieds_listing', 'classifieds_category'];

        $this->uninstallContentTypeData($contentTypes);

        $db->beginTransaction();
        $db->delete('xf_admin_permission_entry', 'admin_permission_id = ?', 'classifieds');
        $db->delete('xf_permission_cache_content', 'content_type = ?', 'classifieds_category');
        $db->delete('xf_permission_entry', 'permission_group_id = ?', 'classifieds_listing');
        $db->delete('xf_permission_entry_content', 'permission_group_id = ?', 'classifieds_listing');
        $db->delete('xf_purchasable', 'purchasable_type_id = ?', 'z61_classifieds_feature');
        $db->delete('xf_purchasable', 'purchasable_type_id = ?', 'z61_classifieds_listing');


        $db->commit();
    }

    public function uninstallStep4()
    {
        $this->deletePhrases([
            'z61_listing_type_title.*',
            'z61_package_title.*',
            'z61_condition_title.*',
        ]);
    }

    public function insertDefaultData()
    {
        if (!$this->addOn->isInstalled())
        {
            $db = $this->app->db();
            $data = $this->getData();
            foreach ($data AS $dataQuery)
            {
                $db->query($dataQuery);
            }

            $insertCount = $db->insert('xf_purchasable', [
                'purchasable_type_id' => 'z61_classifieds_listing',
                'purchasable_class' => 'Z61\Classifieds:Listing',
                'addon_id' => 'Z61/Classifieds'
            ], true);

            return count($data) + $insertCount;
        }
    }

    protected function getTables()
    {
        $data = new MySql();
        return $data->getTables();
    }

    protected function getData()
    {
        $data = new MySql();
        return $data->getData();
    }

    private function getPhrases()
    {
        $data = new MySql();
        return $data->getPhrases();
    }
}