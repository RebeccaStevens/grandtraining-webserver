<?php

use SilverStripe\Reports\Report;
use SilverStripe\Control\Director;

global $project;
$project = 'mysite';

global $database;
$database = 'ss_grandtraining';

// Use _ss_environment.php file for configuration
require_once('conf/ConfigureFromEnv.php');

if (!defined('SITE_URL')) {
  header("HTTP/1.1 500 Internal Server Error");
  if (Director::isLive()) {
    die('500 Internal Server Error');
  } else {
    die('SITE_URL is not defined');
  }
}

require_once('code/Constants.php');

Report::add_excluded_reports(array(
  'SilverStripe\\CMS\\Reports\\BrokenFilesReport',
  'SilverStripe\\CMS\\Reports\\BrokenLinksReport',
  'SilverStripe\\CMS\\Reports\\BrokenRedirectorPagesReport',
  'SilverStripe\\CMS\\Reports\\BrokenVirtualPagesReport',
  'SilverStripe\\CMS\\Reports\\RecentlyEditedReport',
  'SilverStripe\\CMS\\Reports\\EmptyPagesReport'
));
