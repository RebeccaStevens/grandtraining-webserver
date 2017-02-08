<?php

use SilverStripe\Control\Director;

if (Director::isDev()) {
  header('Access-Control-Allow-Origin: *');
} else {
  header('Access-Control-Allow-Origin: ' . SITE_APP_URL);
}
