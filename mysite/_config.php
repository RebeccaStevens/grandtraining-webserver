<?php

use SilverStripe\Reports\Report;

global $project;
$project = 'mysite';

global $database;
$database = 'ss_grandtraining';

// Use _ss_environment.php file for configuration
require_once('conf/ConfigureFromEnv.php');

require_once('code/Constants.php');

Report::add_excluded_reports(array(
  'SilverStripe\\CMS\\Reports\\BrokenFilesReport',
  'SilverStripe\\CMS\\Reports\\BrokenLinksReport',
  'SilverStripe\\CMS\\Reports\\BrokenRedirectorPagesReport',
  'SilverStripe\\CMS\\Reports\\BrokenVirtualPagesReport',
  'SilverStripe\\CMS\\Reports\\RecentlyEditedReport',
  'SilverStripe\\CMS\\Reports\\EmptyPagesReport'
));
