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
        add_action('admin_post_update_person_profile', [$this, 'handle_update_profile_submission']);

        add_action('admin_post_tjmk_review_update', [$this, 'handle_tjmk_review_update']);

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
            __('TJMK', $this->plugin_text_domain),
            __('TJMK', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name,
            array($this, 'urs_user_list_page'),
            //   PLUGIN_NAME_ASSETS_URI . '/images/tjmk-logo.png', // Path to custom image,
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            $this->plugin_name,
            __('Pending Profile', $this->plugin_text_domain),
            __('Pending Profile', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-pending-profiles',
            array($this, 'urs_pending_profile_list_page'),
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

        $users = $this->db->get_profiles_with_review_data('approved');

        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-persons-list-display.php';
    }

    public function urs_pending_profile_list_page()
    {
        // Display the message from the transient, if it exists
        if ($message = get_transient('form_submission_message')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            delete_transient('form_submission_message');
        }

        $users = $this->db->get_profiles_with_review_data('pending');
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-pending-profile-list-display.php';
    }

    public function urs_add_user_page()
    {
        // Check if this is an edit form  edit-person&profile_id
        $profile_id = isset($_GET['action']) && $_GET['action'] === 'edit-person' && !empty($_GET['profile_id']) ? $_GET['profile_id'] : false;

        $person_data = $this->db->get_profile_by_id($profile_id);
        $review_data = $this->db->get_review_meta_by_review_id($profile_id);


        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-add-person-display.php';
    }

    public function urs_approve_reviews_page()
    {
        $approved_reviews = $this->db->get_reviews('approved'); // Get approved reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-approve-reviews-display.php';
    }

    public function urs_pending_reviews_page()
    {
        $pending_reviews = $this->db->get_reviews('pending'); // Get pending reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-pending-reviews-display.php';
    }

    public function urs_view_reviews_page()
    {
        // Get the external_profile_id from the URL parameter
        $profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : false;

        // Fetch reviews for the selected external profile
        $reviews = $this->db->get_reviews_by_external_profile_id($profile_id);

        $profile_data = $this->db->get_profile_by_id($profile_id);


        // Display the message from the transient, if it exists
        if ($message = get_transient('form_submission_message')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            delete_transient('form_submission_message');
        }

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
        global $wpdb; // Access the global $wpdb object

        // Define your nonce action dynamically
        $nonce_action = 'add_profile_with_review_nonce';

        // Check nonce for security
        if (!Helper::verify_nonce($nonce_action)) {
            wp_send_json_error(['message' => 'Security check failed']);
            exit;
        }

        // Sanitize and validate input
        $user_data = Helper::sanitize_user_data($_POST);
        $review_data = Helper::sanitize_review_data($_POST);

        // Initialize success tracking
        $success = true;

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

            // Generate PDF URL
            $generate_pdf_url = Helper::generate_pdf_url($user_data['first_name'], $review_content);
            if (!$generate_pdf_url) {
                throw new \Exception('Failed to generate PDF URL');
            }

            // Create downloadable product
            $product_id = Helper::create_or_update_downloadable_product($user_data['first_name'], $generate_pdf_url);
            if (!$product_id) {
                throw new \Exception('Failed to create or update product');
            }

            // Insert person into database
            $profile_id = $this->db->insert_user($user_data, $product_id);
            if (!$profile_id) {
                throw new \Exception('Failed to insert user');
            }

            // Insert review into database
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

            // Send email
            $email = Email::getInstance();
            $email->setEmailDetails($user_data['email'], 'Hurrah! A Review is live!', 'Hello ' . $user_data['first_name'] . ',<br>One of a review is now live. You can check it.');
            $email_sent = $email->send();
            if (!$email_sent) {
                throw new \Exception('Failed to send email');
            }

            // Commit the transaction if everything is successful
            $wpdb->query('COMMIT');

        } catch (\Exception $e) {
            // Rollback the transaction on error
            $wpdb->query('ROLLBACK');
            $success = false; // Set success to false if any exception occurs

            // Log the error
            Helper::log_error('Error during user addition: ' . $e->getMessage());
        }

        // Handle form submission result
        $message = $success ? 'Successfully Added Person!' : 'There were errors in the addition process.';
        Helper::handle_form_submission_result($success, admin_url('admin.php?page=persons-store'), $message);

        exit; // Make sure to exit after sending the response
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
    public function handle_update_profile_submission()
    {
        // Determine the expected nonce action
        $nonce_action = 'update_profile_with_review_nonce';

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

        //  error_log('Data array prepared: ' . print_r($data, true));

        // Call the database update method
        $result = $this->db->update_person($profile_id, $data);

        if ($result !== false) {
            error_log('Successfully updated person with profile_id: ' . $profile_id);
        } else {
            error_log('Failed to update person with profile_id: ' . $profile_id);
        }

        // get profile status 
        $profile = $this->db->get_profile_by_id($profile_id);
        $url = $profile->status === 'pending' ? 'admin.php?page=persons-store-pending-profiles' : 'admin.php?page=persons-store';

        // Use the static method to handle the redirection with a success or fail message
        $message = $result ? 'Profile updated successfully!' : 'There were errors in the update process.';
        Helper::handle_form_submission_result($result, admin_url($url), $message);

        exit; // Make sure to exit after sending the response
    }


    public function handle_tjmk_review_update()
    {
        // Determine the expected nonce action
        $nonce_action = 'tjmk_review_update';

        // Verify the nonce
        if (!check_admin_referer($nonce_action)) {
            error_log('Nonce verification failed.');
            wp_die(__('Nonce verification failed', 'text-domain'));
        } else {
            error_log('Nonce verification passed.');
        }

        $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

        if (!$review_id) {
            wp_send_json_error(['message' => 'Review ID is missing or invalid']);
            exit;
        }

        // Sanitize the review data
        $review_data = Helper::sanitize_review_data($_POST);

        error_log(print_r($review_data, true));

        // Initialize success flag
        $update_result = true;

        // Loop through the review data and update each field
        foreach ($review_data as $meta_key => $meta_value) {
            if ($meta_key !== 'review_id') {
                // Update each meta field, skipping 'review_id'
                $meta_update = $this->db->update_review_meta($review_id, $meta_key, $meta_value);

                // If any update fails, mark the update as failed
                if (!$meta_update) {
                    $update_result = false;
                    error_log('Failed to update review meta for key: ' . $meta_key);
                    wp_send_json_error(['message' => 'Failed to update review meta']);
                    exit;
                }
            }
        }

        // Log success or failure
        if ($update_result) {
            error_log('Successfully updated review with review_id: ' . $review_id);
        } else {
            error_log('Failed to update review with review_id: ' . $review_id);
        }

        // Retrieve and validate profile_id from $_GET
        $profile_id = isset($_POST['profile_id']) ? intval($_POST['profile_id']) : 0;

        if (!$profile_id) {
            wp_send_json_error(['message' => 'Profile ID is missing or invalid']);
            exit;
        }

        // Construct the URL for redirection
        $url = "admin.php?page=persons-store-view-reviews&profile_id=" . $profile_id;

        // Use the static method to handle the redirection with a success or failure message
        $message = $update_result ? 'Review updated successfully!' : 'There were errors in the update process.';
        Helper::handle_form_submission_result($update_result, admin_url($url), $message);

        exit; // Make sure to exit after sending the response
    }



    // public function handle_update_profile_submission()
    // {
    //     global $wpdb; // Access the global $wpdb object

    //     // Define your nonce action dynamically
    //     $nonce_action = 'update_profile_with_review_nonce';

    //     // Check nonce for security
    //     if (!Helper::verify_nonce($nonce_action)) {
    //         wp_send_json_error(['message' => 'Security check failed']);
    //         exit;
    //     }

    //     //   echo "<pre>";
    //     //   error_log(print_r($_POST, true));

    //     // Sanitize and validate input
    //     $user_data = Helper::sanitize_user_data($_POST);
    //     $review_data = Helper::sanitize_review_data($_POST);
    //     $profile_id = isset($_POST['profile_id']) ? intval($_POST['profile_id']) : 0;

    //     if (!$profile_id) {
    //         wp_send_json_error(['message' => 'Profile ID is missing or invalid']);
    //         exit;
    //     }

    //     // Initialize success tracking
    //     $success = true;

    //     // Start transaction
    //     $wpdb->query('START TRANSACTION');

    //     try {
    //         // Calculate rating
    //         $average_rating = Helper::calculate_rating($review_data);
    //         if (!$average_rating) {
    //             throw new \Exception('Failed to calculate rating');
    //         }

    //         // Process review content
    //         $review_content = Helper::content_process($review_data, $average_rating);
    //         if (!$review_content) {
    //             throw new \Exception('Failed to process review content');
    //         }

    //         // Generate PDF URL
    //         $generate_pdf_url = Helper::generate_pdf_url($user_data['first_name'], $review_content);
    //         if (!$generate_pdf_url) {
    //             throw new \Exception('Failed to generate PDF URL');
    //         }

    //         // Update downloadable product
    //         $product_id = Helper::create_or_update_downloadable_product($user_data['first_name'], $generate_pdf_url);
    //         if (!$product_id) {
    //             throw new \Exception('Failed to create or update product');
    //         }

    //         // Update profile in the database
    //         $updated_profile = $this->db->update_user($user_data, $product_id, $profile_id);
    //         if (!$updated_profile) {
    //             throw new \Exception('Failed to update user');
    //         }

    //         // Update review in the database
    //         $review_id = $this->db->update_review($profile_id, $average_rating);
    //         if (!$review_id) {
    //             throw new \Exception('Failed to update review');
    //         }

    //         //   Helper::log_error_data($updated_profile);
    //         //     Helper::log_error_data($review_id);

    //         // Update or insert review meta
    //         foreach ($review_data as $meta_key => $meta_value) {
    //             $update_meta = $this->db->update_review_meta($review_id, $meta_key, $meta_value);
    //             if (!$update_meta) {
    //                 throw new \Exception("Failed to update review meta: $meta_key");
    //             }
    //         }

    //         // Send email (if needed, or only for admins)
    //         $email = Email::getInstance();
    //         $email->setEmailDetails($user_data['email'], 'Profile Updated!', 'Hello ' . $user_data['first_name'] . ',<br>Your profile has been updated. You can check it.');
    //         $email_sent = $email->send();
    //         if (!$email_sent) {
    //             throw new \Exception('Failed to send email');
    //         }

    //         // Commit the transaction if everything is successful
    //         $wpdb->query('COMMIT');

    //     } catch (\Exception $e) {
    //         // Rollback the transaction on error
    //         $wpdb->query('ROLLBACK');
    //         $success = false; // Set success to false if any exception occurs

    //         // Log the error (if you have a logging mechanism)
    //         Helper::log_error('Error during profile update: ' . $e->getMessage());
    //     }

    //     // get profile status 
    //     $profile = $this->db->get_profile_by_id($profile_id);
    //     $url = $profile->status === 'pending' ? 'admin.php?page=persons-store-pending-profiles' : 'admin.php?page=persons-store';

    //     // Use the static method to handle the redirection with a success or fail message
    //     $message = $success ? 'Profile updated successfully!' : 'There were errors in the update process.';
    //     Helper::handle_form_submission_result($success, admin_url($url), $message);

    //     exit; // Make sure to exit after sending the response
    // }


    public function enqueue_styles()
    {
        // Get the current screen object
        $screen = get_current_screen();

        error_log('Current screen ID: ' . $screen->id);
        // Define the allowed page slugs
        $allowed_pages = [
            'toplevel_page_persons-store',
            'tjmk_page_persons-store-pending-profiles',
            'tjmk_page_persons-store-add-person',
            'tjmk_page_persons-store-approve-reviews',
            'tjmk_page_persons-store-pending-review',
            'tjmk_page_persons-store-view-reviews',
            'tjmk_page_persons-store-bulk-upload',
        ];



        // Check if the current page is in the allowed pages
        if (isset($screen->id) && in_array($screen->id, $allowed_pages)) {
            // Enqueue the stylesheet only for the allowed pages
            wp_enqueue_style('tjmk-admin-css', PLUGIN_ADMIN_URL . 'css/tjmk-admin.css', array(), $this->version, 'all');
        }

        // Check if the current page is in the allowed pages
        if (isset($screen->id) && $screen->id === 'tjmk_page_persons-store-add-person') {
            // Enqueue the stylesheet only for the allowed pages
            wp_enqueue_style('tjmk-admin-form-css', PLUGIN_ADMIN_URL . 'css/tjmk-admin-form.css', array(), $this->version, 'all');
        }


    }


    public function enqueue_scripts()
    {
        // Get the current screen object
        $screen = get_current_screen();

        error_log('Current screen ID: ' . $screen->id);
        // Define the allowed page slugs
        $allowed_pages = [
            'toplevel_page_persons-store',
            'tjmk_page_persons-store-pending-profiles',
            'tjmk_page_persons-store-add-person',
            'tjmk_page_persons-store-approve-reviews',
            'tjmk_page_persons-store-pending-review',
            'tjmk_page_persons-store-view-reviews',
            'tjmk_page_persons-store-bulk-upload',
        ];

        // Check if the current page is in the allowed pages
        if (isset($screen->id) && in_array($screen->id, $allowed_pages)) {

            wp_enqueue_script('tjmk-admin-js', plugin_dir_url(__FILE__) . 'js/tjmk-admin.js', array('jquery'), $this->version, true);
            // Localize script with AJAX data
            wp_localize_script('tjmk-admin-js', 'myPluginAjax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('approve_reject_review_nonce'),
                'import_nonce' => wp_create_nonce('urp_import_nonce'), // Add this line for import actions
                'bulk_delete_nonce' => wp_create_nonce('bulk_delete_nonce') // Add this line for import actions
            ]);
        }
    }
}