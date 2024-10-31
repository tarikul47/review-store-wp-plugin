<?php

namespace Tarikul\PersonsStore\Inc\Core;

/**
 * Fired during plugin deactivation
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 **/
class Deactivate
{

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		$timestamp = wp_next_scheduled('tjmk_process_email_queue_event');
		if ($timestamp !== false) {
			wp_unschedule_event($timestamp, 'tjmk_process_email_queue_event');
		}
	}

}
