<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://onlytarikul.com
 * @since             1.0.0
 * @package           Persons_Store
 *
 * @wordpress-plugin
 * Plugin Name:       Persons Store
 * Plugin URI:        http://onlytarikul.com/wp-plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Tarikul Islam
 * Author URI:        http://onlytarikul.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tjmk
 * Domain Path:       /languages
 */

//namespace Persons_Store;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use Tarikul\PersonsStore\Inc\Core\Activate;
use Tarikul\PersonsStore\Inc\Core\Deactivate;
use Tarikul\PersonsStore\Inc\Core\Init;

/**
 * Define Constants
 */

define('PLUGIN_NAME', 'tjmk');
define('PLUGIN_VERSION', '1.0.0');
define('PLUGIN_NAME_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_NAME_URL', plugin_dir_url(__FILE__));
define('PLUGIN_NAME_ASSETS_URI', PLUGIN_NAME_URL . 'assets');
define('PLUGIN_BASENAME', plugin_basename(__FILE__));
define('PLUGIN_TEXT_DOMAIN', 'tjmk');


// New constants for admin directories
define('PLUGIN_ADMIN_DIR', PLUGIN_NAME_DIR . 'inc/Admin/');
define('PLUGIN_ADMIN_URL', PLUGIN_NAME_URL . 'inc/Admin/');
define('PLUGIN_ADMIN_VIEWS_DIR', PLUGIN_ADMIN_DIR . 'views/');
define('PLUGIN_ADMIN_EMAIL_DIR', PLUGIN_NAME_DIR . 'inc/Email/');
define('PLUGIN_ADMIN_EMAIL_URL', PLUGIN_NAME_URL . 'inc/Email/');

define('PLUGIN_FRONTEND_DIR', PLUGIN_NAME_DIR . 'inc/Frontend/');
define('PLUGIN_FRONTEND_URL', PLUGIN_NAME_URL . 'inc/Frontend/');
define('PLUGIN_FRONTEND_VIEWS_DIR', PLUGIN_FRONTEND_DIR . 'views/');

define('PRODUCT_ID', 509);


/**
 * Register Activation and Deactivation Hooks
 * This action is documented in includes/Core/class-activator.php
 */
register_activation_hook(__FILE__, array(Activate::class, 'activate'));

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/Core/class-deactivator.php
 */
register_deactivation_hook(__FILE__, array(Deactivate::class, 'deactivate'));


/**
 * Add custom cron schedule
 */
add_filter('cron_schedules', [Activate::class, 'ps_add_cron_schedule']);


/**
 * Plugin Singleton Container
 *
 * Maintains a single copy of the plugin app object
 *
 * @since    1.0.0
 */
class Persons_Store
{
    /**
     * The instance of the plugin.
     *
     * @since    1.0.0
     * @var      Init $init Instance of the plugin.
     */
    private static $init;

    /**
     * Loads the plugin
     *
     * @access    public
     */
    public static function init()
    {
        if (null === self::$init) {
            self::$init = new Init();
            self::$init->run();
        }
        return self::$init;
    }

}

/**
 * Begins execution of the plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * Also returns copy of the app object so 3rd party developers
 * can interact with the plugin's hooks contained within.
//  **/
function persons_store_init()
{
    return Persons_Store::init();
}

$min_php = '5.6.0';

// Check the minimum required PHP version and run the plugin.
if (version_compare(PHP_VERSION, $min_php, '>=')) {
    persons_store_init();
}