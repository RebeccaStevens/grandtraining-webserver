<?php

global $project;
$project = 'mysite';

global $database;
$database = 'SS_grandtraining';

// Set the site locale
i18n::set_locale('en_US');

require_once('conf/ConfigureFromEnv.php');
