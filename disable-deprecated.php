<?php
/*
	Plugin Name: Disable Deprecated Warnings
	Version: 1.0
	Description: Prevents plugins from showing deprecated errors in the WordPress admin when WP_DEBUG is on.
	Author: tamlyn
	Author URI: http://outlandishideas.co.uk
*/

function dd_load_plugin_first() {
	// ensure path to this file is via main wp plugin path
	$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
	$this_plugin = plugin_basename(trim($wp_path_to_this_file));
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
	if ($this_plugin_key) { // if it's 0 it's the first plugin already, no need to continue
		array_splice($active_plugins, $this_plugin_key, 1);
		array_unshift($active_plugins, $this_plugin);
		update_option('active_plugins', $active_plugins);
	}
}
add_action("activated_plugin", "dd_load_plugin_first");

add_filter('deprecated_function_trigger_error', 'disable_all_deprecated_warnings');
add_filter('deprecated_argument_trigger_error', 'disable_all_deprecated_warnings');
add_filter('deprecated_file_trigger_error',     'disable_all_deprecated_warnings');
function disable_all_deprecated_warnings($bool) {
	return false;
}