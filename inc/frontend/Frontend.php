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
		add_action('admin_post_frontend_add_profile_with_review', [$this, 'handle_frontend_add_profile_form_submission']);
	}

	public function handle_frontend_add_profile_form_submission()
	{
		error_log('frontend-----------------');
		// Define your nonce action dynamically
		$nonce_action = 'frontend_add_profile_with_review_nonce';

		// Check nonce for security
		if (!Helper::verify_nonce($nonce_action)) {
			wp_die('Security check failed');
		}

		// Sanitize and validate input
		$user_data = Helper::sanitize_user_data($_POST);
		$review_data = Helper::sanitize_review_data($_POST);

		// Log sanitized user and review data
		//    error_log('User Data: ' . print_r($user_data, true));
		//   error_log('Review Data: ' . print_r($review_data, true));

		// Calculate rating
		$average_rating = Helper::calculate_rating($review_data);
		if (!$average_rating) {
			error_log('Failed to calculate rating');
		}

		// Process review content
		$review_content = Helper::content_process($review_data, $average_rating);
		if (!$review_content) {
			error_log('Failed to process review content');
		}

		// Generate PDF URL
		$generate_pdf_url = Helper::generate_pdf_url($user_data['first_name'], $review_content);
		if (!$generate_pdf_url) {
			error_log('Failed to generate PDF URL');
		}

		// Create downloadable product
		$product_id = Helper::create_or_update_downloadable_product($user_data['first_name'], $generate_pdf_url);
		if (!$product_id) {
			error_log('Failed to create or update product');
		}

		// Insert person into database
		$profile_id = $this->db->insert_user($user_data, $product_id);
		if (!$profile_id) {
			error_log('Failed to insert user');
		}

		// Insert review into database
		if ($profile_id) {
			$review_id = $this->db->insert_review($profile_id, $average_rating);
			if (!$review_id) {
				error_log('Failed to insert review');
			}
		}

		// Insert review meta
		if ($review_id) {
			foreach ($review_data as $meta_key => $meta_value) {
				$insert_meta = $this->db->insert_review_meta($review_id, $meta_key, $meta_value);
				if (!$insert_meta) {
					error_log("Failed to insert review meta: $meta_key");
				}
			}
		}

		//TODO: Need to email also for author 

		// Send email 
		$email = Email::getInstance();
		$email->setEmailDetails($user_data['email'], 'Hurrah! A Review is live!', 'Hello ' . $user_data['first_name'] . ',<br>One of a review is now live. You can check it.');
		$result = $email->send();
		if (!$result) {
			error_log('Failed to send email');
		}

		// Handle form submission result
		$message = $result ? 'Successfully Added Person as a pending status. You will get email after approve!' : 'Something went wrong!.';
		Helper::handle_form_submission_result($result, home_url('add-profile'), $message);
		exit;
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

		wp_enqueue_style($this->plugin_name . "-add-profile", plugin_dir_url(__FILE__) . 'css/review-store-frontend-add-profile.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/review-store-frontend.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
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

		wp_enqueue_script($this->plugin_name, PLUGIN_NAME_URL . 'inc/Frontend/js/review-store-frontend.js', array('jquery'), $this->version, false);
		// Localize script with AJAX data
		wp_localize_script($this->plugin_name, 'myPluginAjax', [
			'ajax_url' => admin_url('admin-ajax.php'),
		]);
	}

}
