<?php

namespace Tarikul\TJMK\Inc\Core;

use Tarikul\TJMK\Inc\Database as Database;
use Tarikul\TJMK\Inc\Email\Email;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 **/
class Activate
{

    /**
     * Short Description.
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $min_php = '5.6.0';

        // Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
        if (version_compare(PHP_VERSION, $min_php, '<')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die('This plugin requires a minimum PHP Version of ' . $min_php);
        }

        // Create required tables for the plugin.
        Database\Database::create_tables();

        /**
         * Schedule email queue processing to run every 5 minutes.
         * If no scheduled event exists, create one.
         */

        if (!wp_next_scheduled('tjmk_process_email_queue_event')) {
            wp_schedule_event(time(), 'tjmk_five_minutes', 'tjmk_process_email_queue_event');
            error_log(print_r('Activate class - wp_schedule_event', true)); // Log the schedules to ensure yours is added
        }

        // Hook the function to the scheduled event
        add_action('tjmk_process_email_queue_event', [Email::class, 'processQueue']);

    }
    /**
     * Define a custom interval for the cron schedule.
     * Adds a 'five_minutes' interval to the existing cron schedules.
     *
     * @param array $schedules Existing cron schedules.
     * @return array Modified cron schedules with the custom 'five_minutes' interval.
     */

    public static function ps_add_cron_schedule($schedules)
    {
        $schedules['tjmk_five_minutes'] = array(
            'interval' => 300, // 5 minutes in seconds
            'display' => __('Every Five Minutes')
        );
        return $schedules;
    }
}
