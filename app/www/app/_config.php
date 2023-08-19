<?php
// Set the site locale
use SilverStripe\i18n\i18n;

i18n::set_locale('en_US');
ini_set('date.timezone', 'America/New_York');

use SilverStripe\Reports\ReportAdmin;
use SilverStripe\VersionedAdmin\ArchiveAdmin;
use SilverStripe\CampaignAdmin\CampaignAdmin;
use SilverStripe\RedirectedURLs\Admin\RedirectedURLAdmin;
use Symbiote\QueuedJobs\Controllers\QueuedJobsAdmin;
use SilverStripe\CMS\Controllers\CMSPagesController;
use SilverStripe\Admin\CMSMenu;

CMSMenu::remove_menu_class(ReportAdmin::class);
CMSMenu::remove_menu_class(ArchiveAdmin::class);
CMSMenu::remove_menu_class(CampaignAdmin::class);
CMSMenu::remove_menu_class(RedirectedURLAdmin::class);
CMSMenu::remove_menu_class(QueuedJobsAdmin::class);
CMSMenu::remove_menu_class(CMSPagesController::class);
CMSMenu::remove_menu_class(\TND\ElasticSearch\Admin\ElasticSearchAdmin::class);
