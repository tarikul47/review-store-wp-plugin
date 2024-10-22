<?php

namespace Tarikul\PersonsStore\Inc\Admin\Class\Profile;
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Email\Email;
use Tarikul\PersonsStore\Inc\Helper\Helper;

class ProfileManagement
{
    private $plugin_name;
    private $version;
    private $plugin_text_domain;
    private $db;

    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
        $this->db = Database::getInstance();
        $this->init();
    }

    public function init()
    {
        // Add action for form submission
        add_action('admin_post_tjmk_add_profile_with_review', [$this, 'tjmk_handle_add_profile_submission']);
        add_action('admin_post_tjmk_update_profile', [$this, 'tjmk_handle_update_profile_submission']);

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

    public function tjmk_handle_add_profile_submission()
    {
        global $wpdb; // Access the global $wpdb object

        // Define your nonce action dynamically
        $nonce_action = 'tjmk_add_profile_with_review_nonce';

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
    public function tjmk_handle_update_profile_submission()
    {
        // Determine the expected nonce action
        $nonce_action = 'tjmk_update_profile_with_review_nonce';

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



}