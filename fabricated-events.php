<?php
/*
Plugin Name: Fabricated Events
Plugin URI: https://github.com/rgriffith/Fabricated-Events
Description: An easy to use event management plugin that utilizes new features added in Wordpress v3.0.
Author: Ryan Griffith
Version: 1.0
Author URI: http://www.griffworks.com/
*/

define('FABRICATEDEVENTS_VERSION', '2.5.0');
define('FABRICATEDEVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ));

require_once('inc/core.php');
require_once('inc/class.options.php');

$events = new FabricatedEvents();

require_once('inc/core.php');



?>