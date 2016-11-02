<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
	'type' => 'MySQLPDODatabase',
	'server' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'SS_grandtraining',
	'path' => ''
);

// Set the site locale
i18n::set_locale('en_US');
