<?php
namespace Tarikul\PersonsStore\Inc\Admin;

use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Email\Email;
use Tarikul\PersonsStore\Inc\Helper\Helper;
use Tarikul\PersonsStore\Inc\AjaxHandler\AjaxHandler;

//use Tarikul\ReviewStore\Inc\AjaxHandler\AjaxHandler;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */
class Admin
{

    private $plugin_name;
    private $version;
    private $plugin_text_domain;
    private $db;

    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        global $wpdb;
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
        add_action('admin_menu', array($this, 'urs_admin_menu'));
        $this->db = Database::getInstance();

        // Add action for form submission
        add_action('admin_post_add_user_with_review', [$this, 'handle_add_user_form_submission']);
        add_action('admin_post_update_person_profile', [$this, 'handle_update_user_form_submission']);

        // Initialize AJAX handling
        new AjaxHandler();

        // Register form submission handler
        //   add_action('admin_post_handle_add_user_form', array($this, 'handle_add_user_form_submission'));

        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML('<h1>Hello world!</h1>');
        // $mpdf->Output();
    }

    public function urs_admin_menu()
    {
        add_menu_page(
            __('Persons Store', $this->plugin_text_domain),
            __('Persons Store', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name,
            array($this, 'urs_user_list_page'),
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            $this->plugin_name,
            __('Add Person', $this->plugin_text_domain),
            __('Add Person', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-add-person',
            array($this, 'urs_add_user_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('Approve Reviews', $this->plugin_text_domain),
            __('Approve Reviews', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-approve-reviews',
            array($this, 'urs_approve_reviews_page')
        );

        add_submenu_page(
            $this->plugin_name,
            __('Pending Review', $this->plugin_text_domain),
            __('Pending Review', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-pending-review',
            array($this, 'urs_pending_reviews_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('View Reviews', $this->plugin_text_domain),
            __('View Reviews', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-view-reviews',
            array($this, 'urs_view_reviews_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('Bulk Upload', $this->plugin_text_domain),
            __('Bulk Upload', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-bulk-upload',
            array($this, 'urs_bulk_upload')
        );

    }

    public function urs_user_list_page()
    {
        // if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['external_profile_id'])) {
        //     $this->handle_delete_user(intval($_GET['external_profile_id']));
        // }

        // if (isset($_POST['bulk_delete_users']) && !empty($_POST['external_profile_ids'])) {
        //     $this->handle_bulk_delete_users(array_map('intval', $_POST['external_profile_ids']));
        // }

        // Display the message from the transient, if it exists
        if ($message = get_transient('form_submission_message')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            delete_transient('form_submission_message');
        }

        //  var_dump($_GET);

        $users = $this->db->get_users_with_review_data();

        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-persons-list-display.php';
    }

    public function urs_add_user_page()
    {
        // Check if this is an edit form  edit-person&profile_id
        $profile_id = isset($_GET['action']) && $_GET['action'] === 'edit-person' && !empty($_GET['profile_id']) ? $_GET['profile_id'] : false;

        $person_data = $this->db->get_person_by_id($profile_id);

        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-add-person-display.php';
    }

    public function urs_approve_reviews_page()
    {
        $approved_reviews = $this->db->get_reviews_by_status('approved'); // Get approved reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-approve-reviews-display.php';
    }

    public function urs_pending_reviews_page()
    {
        $pending_reviews = $this->db->get_reviews_by_status('pending'); // Get pending reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-pending-reviews-display.php';
    }

    public function urs_view_reviews_page()
    {
        // Get the external_profile_id from the URL parameter
        $profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

        // Fetch reviews for the selected external profile
        $reviews = $this->db->get_reviews_by_external_profile_id($profile_id);

        // Include the view file to display the reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-view-reviews-display.php';
    }


    public function urs_bulk_upload()
    {
        // Include the view file to display the reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-person-bulk-upload.php';
    }

    /**
     * Handles the form submission for adding a new user with an associated review.
     *
     * This method processes the form data for adding a new user and their review,
     * validates and sanitizes the inputs, inserts the user's information and review into the database,
     * and handles the success or failure of the operation with appropriate logging and user feedback.
     *
     * The function:
     * - Verifies the nonce for security.
     * - Sanitizes and validates all input data.
     * - Inserts the new user's data into the appropriate database tables.
     * - Inserts the associated review data, if applicable.
     * - Provides success or failure messages and redirects the user as needed.
     *
     * @return void
     */
    public function handle_add_user_form_submission()
    {
        
        // Define your nonce action dynamically
        $nonce_action = 'add_user_with_review_nonce';

        // Check nonce for security
        if (!Helper::verify_nonce($nonce_action)) {
            wp_die('Security check failed');
        }

        // Sanitize and validate input
        $user_data = Helper::sanitize_user_data($_POST);
        $review_data = Helper::sanitize_review_data($_POST);

        // Log sanitized user and review data
        error_log('User Data: ' . print_r($user_data, true));
        error_log('Review Data: ' . print_r($review_data, true));

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

        // Send email
        $email = Email::getInstance();
        $email->setEmailDetails($user_data['email'], 'Hurrah! A Review is live!', 'Hello ' . $user_data['first_name'] . ',<br>One of a review is now live. You can check it.');
        $result = $email->send();
        if (!$result) {
            error_log('Failed to send email');
        }

        // Handle form submission result
        $message = $result ? 'Successfully Added Person!' : 'Something went wrong!.';
        Helper::handle_form_submission_result($result, admin_url('admin.php?page=persons-store'), $message);

        exit;
    }

    /**
     * Handles the form submission for updating a user's profile.
     *
     * This method processes the form data for updating a user's profile,
     * validates and sanitizes the inputs, updates the user's information in the database,
     * and handles the success or failure of the operation with appropriate logging.
     *
     * @return void
     */
    public function handle_update_user_form_submission()
    {
        // Determine the expected nonce action
        $nonce_action = 'update_user_with_review_nonce';

        // Verify the nonce
        if (!check_admin_referer($nonce_action)) {
            error_log('Nonce verification failed.');
            wp_die(__('Nonce verification failed', 'text-domain'));
        } else {
            error_log('Nonce verification passed.');
        }

        // Sanitize and validate data
        $profile_id = intval($_POST['profile_id']);
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $title = sanitize_text_field($_POST['title']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_text_field($_POST['address']);
        $zip_code = sanitize_text_field($_POST['zip_code']);
        $city = sanitize_text_field($_POST['city']);
        $salary_per_month = floatval($_POST['salary_per_month']);
        $employee_type = sanitize_text_field($_POST['employee_type']);
        $region = sanitize_text_field($_POST['region']);
        $state = sanitize_text_field($_POST['state']);
        $country = sanitize_text_field($_POST['country']);
        $municipality = sanitize_text_field($_POST['municipality']);
        $department = sanitize_text_field($_POST['department']);

        error_log('Data sanitized and validated.');

        // Prepare data array
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'title' => $title,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'zip_code' => $zip_code,
            'city' => $city,
            'salary_per_month' => $salary_per_month,
            'employee_type' => $employee_type,
            'region' => $region,
            'state' => $state,
            'country' => $country,
            'municipality' => $municipality,
            'department' => $department,
        ];

        error_log('Data array prepared: ' . print_r($data, true));

        // Call the database update method
        $result = $this->db->update_person($profile_id, $data);

        if ($result !== false) {
            error_log('Successfully updated person with profile_id: ' . $profile_id);
        } else {
            error_log('Failed to update person with profile_id: ' . $profile_id);
        }

        $message = $result ? 'Successfully Updated Person!' : 'Something went wrong.';

        // Use the static method to handle the redirection with a success or fail message
        Helper::handle_form_submission_result($result, admin_url('admin.php?page=persons-store'), $message);

        error_log('Redirection handled with message: ' . $message);
    }


    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/review-store-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/review-store-admin.js', array('jquery'), $this->version, true);
        // Localize script with AJAX data
        wp_localize_script($this->plugin_name, 'myPluginAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('approve_reject_review_nonce'),
            'import_nonce' => wp_create_nonce('urp_import_nonce') // Add this line for import actions
        ]);
    }
}