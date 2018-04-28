<?php
/**
* Plugin Name:  Protect Content (FT)
* Description:  Create, test and deploy custom shortcodes form WordPress control panel
* Version:      20180427
* Author:       Filippo Toso
* Author URI:   https://www.filippotoso.com/
* License:      MIT License
*/

if (!defined('ABSPATH')) {
	exit;
}

require_once(__DIR__ . '/includes/includes.php');

// Start the plugin
FTProtectContent::start();

// Register the activation / deactivation hooks
register_activation_hook(__FILE__, [FTProtectContent::instance(), 'activate']);
register_deactivation_hook(__FILE__, [FTProtectContent::instance(), 'deactivate']);
