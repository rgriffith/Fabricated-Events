<?php
/*
Plugin Name: Fabricated Events
Plugin URI: #
Description: An easy to use event management plugin that utilizes new features added in Wordpress v3.0.
Author: Ryan Griffith
Version: 1.0
Author URI: http://www.griffworks.com/
*/

define('FEM_PLUGIN_URL', plugin_dir_url( __FILE__ ));

require_once('inc/core.php');
$events = new FabricatedEvents();

add_action('init', array(&$events, 'createEventPostType'));
add_action('admin_init', array(&$events, 'createEventMetaboxes'));





?>