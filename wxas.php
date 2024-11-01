<?php
/**
 * Plugin Name:     Slider by webxapp
 * Description:     Slider WXA is best responsive WordPress slider plugin.
 * Version:         1.2.0
 * Author:          WebXApp
 * Author URI:      https://webxapp.com/
 * Text Domain:     slider-wxa
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('WXAS_MAIN_FILE')) {
    define('WXAS_MAIN_FILE', plugin_basename(__FILE__));
}
if (!defined('WXAS_DIR')) {
    define('WXAS_DIR', dirname(__FILE__));
}
if (!defined('WXAS_URL')) {
    define('WXAS_URL', plugins_url(plugin_basename(dirname(__FILE__))));
}


if (!defined('WXAS_VERSION')) {
    define('WXAS_VERSION', "1.2.0");
}

if (!defined('WXAS_PLUGIN_MAIN_FILE')) {
    define('WXAS_PLUGIN_MAIN_FILE', __FILE__);
}
if (!defined('WXAS_PLUGIN_PREFIX')) {
    define('WXAS_PLUGIN_PREFIX', "wxas");
}
if(!is_admin()){
    require_once ("wxas_class.php");
    add_action('plugins_loaded', array('WXAS', 'get_instance'));
}

if (is_admin()) {
    require_once('wxas_admin_class.php');
    add_action('plugins_loaded', array('WXAS_Admin', 'get_instance'));
}else{
    require_once ('includes/wxas_register_posts.php');
    wxas_register_posts::get_instance();
}
