<?php
/**
* Plugin Name:  Protect Content (FT)
* Description:  Protect posts and pages and assign them to specific users.
* Version:      20180428
* Author:       Filippo Toso
* Author URI:   https://www.filippotoso.com/
* License:      MIT License
*/

if (!defined('ABSPATH')) {
	exit;
}

require_once(__DIR__ . '/includes/includes.php');

// Start the plugin
FTProtectContentPlugin::start();

// Register the activation / deactivation hooks
register_activation_hook(__FILE__, [FTProtectContentPlugin::instance(), 'activate']);
register_deactivation_hook(__FILE__, [FTProtectContentPlugin::instance(), 'deactivate']);
