<?php

namespace Tarikul\PersonsStore\Inc\Frontend;
use Tarikul\PersonsStore\Inc\AjaxHandler\AjaxHandler;
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Email\Email;
use Tarikul\PersonsStore\Inc\Helper\Helper;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */
class Frontend
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;


	private $db;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @param       string $plugin_name        The name of this plugin.
	 * @param       string $version            The version of this plugin.
	 * @param       string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct($plugin_name, $version, $plugin_text_domain)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

		// Initialize AJAX handling
		new AjaxHandler();

		$this->db = Database::getInstance();

		// Add action for form submission
		add_action('wp_ajax_frontend_add_profile_with_review', [$this, 'handle_frontend_add_profile_form_submission']);
	}

	/**
	 * Summary of handle_frontend_add_profile_form_submission
	 * @return never
	 */
	public function handle_frontend_add_profile_form_submission()
	{
		global $wpdb; // Access the global $wpdb object

		// Define your nonce action dynamically
		$nonce_action = 'frontend_add_profile_with_review_nonce';

		// Check nonce for security
		if (!Helper::verify_nonce($nonce_action)) {
			wp_send_json_error(['message' => 'Security check failed']);
			exit;
		}

		// Sanitize and validate input
		$user_data = Helper::sanitize_user_data($_POST);
		$review_data = Helper::sanitize_review_data($_POST);

		// Initialize an array to collect errors
		$errors = [];
		$successMessages = [];

		// Start transaction
		$wpdb->query('START TRANSACTION');

		try {
			// Calculate rating
			$average_rating = Helper::calculate_rating($review_data);
			if (!$average_rating) {
				throw new \Exception('Failed to calculate rating');
			}

			// Process review content
			$review_content = Helper::content_process($review_data, $average_rating);
			if (!$review_content) {
				throw new \Exception('Failed to process review content');
			}

			// Insert person into the database
			$profile_id = $this->db->insert_user($user_data);
			if (!$profile_id) {
				throw new \Exception('Failed to insert user');
			}

			// Insert review into the database
			$review_id = $this->db->insert_review($profile_id, $average_rating);
			if (!$review_id) {
				throw new \Exception('Failed to insert review');
			}

			// Insert review meta
			foreach ($review_data as $meta_key => $meta_value) {
				$insert_meta = $this->db->insert_review_meta($review_id, $meta_key, $meta_value);
				if (!$insert_meta) {
					throw new \Exception("Failed to insert review meta: $meta_key");
				}
			}

			/* ----------- Stat Sending Email Notification ------*/
			// Instantiate the email class
			$mailer = WC()->mailer();

			/**
			 * User user get an eamail notification 
			 */
			$author_data = [
				'name' => Helper::get_current_user_id_and_roles()['name'],
				'email' => Helper::get_current_user_id_and_roles()['email'],
				'id' => Helper::get_current_user_id_and_roles()['id'],
			];
			$admin_data = [
				'name' => Helper::get_admin_info()['name'],
				'email' => Helper::get_admin_info()['email'],
				'id' => 1, // assuming the admin user has ID 1
			];

			// Send the custom email notification
			// do_action('tjmk_trigger_ajax_email', $recipient, $custom_content);

			do_action('tjmk_trigger_profile_created_pending_by_user_to_user', $author_data['email'], $author_data);
			do_action('tjmk_trigger_profile_created_pending_by_user_to_admin', $admin_data['email'], $admin_data);

			/* ----------- Stat Sending Email Notification ------*/



			// Commit the transaction if everything is successful
			$wpdb->query('COMMIT');

			// Return success message
			$message = 'Successfully added person with a pending status. You will receive an email after approval!';
			wp_send_json_success(['message' => $message]);

		} catch (\Exception $e) {
			// Rollback the transaction on error
			$wpdb->query('ROLLBACK');

			// Log the error (if you have a logging mechanism)
			Helper::log_error('Error during profile submission: ' . $e->getMessage());

			// Return error response
			wp_send_json_error(['message' => 'There were errors in the submission.', 'errors' => [$e->getMessage()]]);
		}

		exit; // Make sure to exit after sending the response
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style("tjmk-frontend-search-css", PLUGIN_FRONTEND_URL . 'css/tjmk-frontend-search.css', array(), time(), 'all');

		wp_enqueue_style("tjmk-frontend-table-css", PLUGIN_FRONTEND_URL . 'css/tjmk-frontend-table.css', array(), time(), 'all');

		wp_enqueue_style("tjmk-frontend-profile-css", PLUGIN_FRONTEND_URL . 'css/tjmk-frontend-profile.css', array(), time(), 'all');

		wp_enqueue_style("tjmk-frontend-css", PLUGIN_FRONTEND_URL . 'css/tjmk-frontend.css', array(), time(), 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		// Enqueue jQuery as a dependency
		wp_enqueue_script('jquery');

		// Enqueue validation.js
		wp_enqueue_script('tjmk-validation-js', PLUGIN_FRONTEND_URL . 'js/partials/validation.js', array('jquery'), null, true);

		// Enqueue ajaxHandler.js
		wp_enqueue_script('tjmk-ajax-handler-js', PLUGIN_FRONTEND_URL . 'js/partials/ajaxHandler.js', array('tjmk-validation-js'), null, true);

		wp_enqueue_script('tjmk-main-js', PLUGIN_FRONTEND_URL . 'js/tjmk-main.js', array('tjmk-ajax-handler-js'), null, true);

		// Localize script to pass AJAX URL
		wp_localize_script('tjmk-ajax-handler-js', 'myPluginAjax', [
			'ajax_url' => admin_url('admin-ajax.php'),
		]);

		wp_enqueue_script('tjmk-frontend-js', PLUGIN_FRONTEND_URL . 'js/tjmk-frontend.js', array('jquery'), $this->version, false);
		// Localize script with AJAX data
		wp_localize_script('tjmk-frontend-js', 'myPluginAjax', [
			'ajax_url' => admin_url('admin-ajax.php'),
		]);
	}

}
