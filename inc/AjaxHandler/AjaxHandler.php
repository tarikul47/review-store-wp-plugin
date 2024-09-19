<?php
namespace Tarikul\PersonsStore\Inc\AjaxHandler;

use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\BulkUploadHandler\BulkUploadHandler;
use Tarikul\PersonsStore\Inc\Helper\Helper;

class AjaxHandler
{

    private $db;
    /**
     * Constructor to add AJAX actions.
     */
    public function __construct()
    {
        if (is_admin()) {
            add_action('wp_ajax_approve_review', [$this, 'approve_review']);
            add_action('wp_ajax_reject_review', [$this, 'reject_review']);

            // add_action('wp_ajax_urp_handle_file_upload', array($this, 'handle_file_upload'));
            // add_action('wp_ajax_urp_process_chunks_async', array($this, 'process_chunks_async'));

            // Ensure this file is only loaded in the admin area if it's an admin-specific action

            add_action('wp_ajax_urp_handle_file_upload_async', [$this, 'ps_handle_file_upload']);
            add_action('wp_ajax_urp_process_chunks_async', [$this, 'ps_process_chunks_async']);


            add_action('wp_ajax_delete_profile', [$this, 'handle_delete_profile']);

            add_action('wp_ajax_urp_bulk_delete_profiles', [$this, 'urp_bulk_delete_profiles']);

        }

        $this->db = Database::getInstance();

        $this->define_frontend_action();
    }

    /**
     * Define frontend-related actions
     */
    public function define_frontend_action()
    {
        // AJAX action for logged-in users
        add_action('wp_ajax_add_review', [$this, 'handle_frontend_add_review']);
    }

    /**
     * Handle frontend review submission
     */
    public function handle_frontend_add_review()
    {
        // Define nonce action
        $nonce_action = 'public_add_review_nonce';

        // Check nonce for security
        if (!Helper::verify_nonce($nonce_action)) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        // Sanitize and validate input
        $review_data = Helper::sanitize_review_data($_POST);

        // Ensure profile_id exists in the review data
        if (empty($review_data)) {
            wp_send_json_error(['message' => 'Profile ID is missing']);
        }

        // Ensure profile_id exists in the review data
        if (empty($review_data['profile_id'])) {
            wp_send_json_error(['message' => 'Profile ID is missing']);
        }

        // Calculate rating
        $average_rating = Helper::calculate_rating($review_data);
        if (!$average_rating) {
            wp_send_json_error(['message' => 'Failed to calculate rating']);
        }

        // Check if the user has already submitted a review for this profile
        $existing_review = $this->db->get_existing_review($review_data['profile_id']);
        if ($existing_review) {
            wp_send_json_error(['message' => 'You have already reviewed this profile']);
        }

        // Insert the review into the database
        $review_id = $this->db->insert_review($review_data['profile_id'], $average_rating);
        if (!$review_id) {
            wp_send_json_error(['message' => 'Failed to insert review']);
        }

        // Insert review meta data
        foreach ($review_data as $meta_key => $meta_value) {
            if ($meta_key !== 'profile_id') {  // Avoid inserting profile_id as meta
                $insert_meta = $this->db->insert_review_meta($review_id, $meta_key, $meta_value);
                if (!$insert_meta) {
                    wp_send_json_error(['message' => "Failed to insert review meta: $meta_key"]);
                }
            }
        }

        // Determine the message based on the review status
        $user_info = Helper::get_current_user_id_and_roles();
        $is_admin = in_array('administrator', $user_info['roles']);
        $message = $is_admin
            ? 'Your review has been published successfully.'
            : 'Your review is pending and will be published after approval. You will receive an email notification.';

        // Send success message
        wp_send_json_success(['message' => $message]);

        wp_die(); // Ensure proper termination of the AJAX request
    }


    /**
     * Handles the file upload request.
     *
     * @return void
     */
    public function ps_handle_file_upload()
    {
        $bulkUploadHandler = BulkUploadHandler::getInstance();
        $bulkUploadHandler->handle_file_upload();
    }

    /**
     * Handles the chunk processing request.
     *
     * @return void
     */
    public function ps_process_chunks_async()
    {
        $bulkUploadHandler = BulkUploadHandler::getInstance();
        $bulkUploadHandler->process_chunks_async();
    }

    /**
     * Handle the approval of a review.
     *
     * @return void
     */
    public function approve_review()
    {
        // Check for nonce security
        check_ajax_referer('approve_reject_review_nonce', 'security');

        // Sanitize and validate input
        $review_data = Helper::sanitize_review_data($_POST);

        if (!$review_data['review_id']) {
            $this->log_error('Review ID is missing');
            wp_send_json_error(['message' => 'Review ID is missing.']);
            return;
        }
        if (!$review_data['profile_Id']) {
            $this->log_error('Profile ID is missing');
            wp_send_json_error(['message' => 'Profile ID is missing.']);
            return;
        }

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

        error_log(print_r($_POST, true));

        die();

        /**-------- 

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

    //    exit;

      

        //TODO: 1 = 

        /**
         *  * Review data need to process 
         * We need profile id = 
         *  - We need product id = by profle id 
         * 
         */



        // Process the approval
        $result = $this->db->update_review_status($review_id, 'approved');

        error_log(print_r($result, true));

        if ($result) {
            wp_send_json_success(['message' => 'Review approved successfully.']);
        } else {
            $this->log_error('Failed to approve review with ID: ' . $review_id);
            wp_send_json_error(['message' => 'Failed to approve review.']);
        }
    }

    /**
     * Handle the rejection of a review.
     *
     * @return void
     */
    public function reject_review()
    {
        // Check for nonce security
        check_ajax_referer('approve_reject_review_nonce', 'security');

        $review_id = intval($_POST['review_id']);

        if (!$review_id) {
            $this->log_error('Review ID is missing');
            wp_send_json_error(['message' => 'Review ID is missing.']);
            return;
        }

        // Process the rejection
        $result = $this->db->update_review_status($review_id, 'rejected');

        if ($result) {
            wp_send_json_success(['message' => 'Review rejected successfully.']);
        } else {
            $this->log_error('Failed to reject review with ID: ' . $review_id);
            wp_send_json_error(['message' => 'Failed to reject review.']);
        }
    }

    function handle_delete_profile()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'delete_profile' && isset($_POST['profile_id'])) {
            $profile_id = intval($_POST['profile_id']);

            // Assuming `delete_profile_and_related_data()` is a method within a class.
            $deleted = $this->db->delete_profile_and_related_data($profile_id);

            // If successful, return a JSON response
            if ($deleted) {
                wp_send_json_success(array('message' => 'Profile deleted successfully.', 'profile_id' => $profile_id));
            } else {
                wp_send_json_error(array('message' => 'Profile deletion failed.'));
            }
        }
    }

    function urp_bulk_delete_profiles()
    {
        check_ajax_referer('bulk_delete_nonce', 'security');

        if (!isset($_POST['profile_ids']) || !is_array($_POST['profile_ids'])) {
            wp_send_json_error(['message' => 'Invalid request.']);
        }

        $profile_ids = array_map('intval', $_POST['profile_ids']);

        $deleted_profiles = 0;
        $failed_profiles = [];

        foreach ($profile_ids as $profile_id) {
            $deleted = $this->db->delete_profile_and_related_data($profile_id);
            if ($deleted) {
                $deleted_profiles++;
            } else {
                $failed_profiles[] = $profile_id;
            }
        }

        if ($deleted_profiles > 0) {
            wp_send_json_success([
                'deleted' => $deleted_profiles,
                'failed' => $failed_profiles
            ]);
        } else {
            wp_send_json_error(['message' => 'No profiles were deleted.']);
        }
    }



    /**
     * Log errors for debugging purposes.
     *
     * @param string $message The error message to log.
     * @return void
     */
    private function log_error($message)
    {
        error_log('[AjaxHandler] ' . $message);
    }
}
