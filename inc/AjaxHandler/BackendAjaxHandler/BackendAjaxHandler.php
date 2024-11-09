<?php
namespace Tarikul\TJMK\Inc\AjaxHandler\BackendAjaxHandler;

use Tarikul\TJMK\Inc\BulkUploadHandler\BulkUploadHandler;
use Tarikul\TJMK\Inc\Database\Database;
use Tarikul\TJMK\Inc\Helper\Helper;


class BackendAjaxHandler
{
    private $db;
    private $BulkUploadHandler;
    public function __construct()
    {
        $this->db = Database::getInstance();
        if (is_admin()) {

            add_action('wp_ajax_tjmk_approve_review', [$this, 'tjmk_approve_review']);
            add_action('wp_ajax_tjmk_reject_review', [$this, 'tjmk_reject_review']);

            // add_action('wp_ajax_urp_handle_file_upload', array($this, 'handle_file_upload'));
            // add_action('wp_ajax_urp_process_chunks_async', array($this, 'process_chunks_async'));

            // Ensure this file is only loaded in the admin area if it's an admin-specific action

            add_action('wp_ajax_urp_handle_file_upload_async', [$this, 'ps_handle_file_upload']);
            add_action('wp_ajax_urp_process_chunks_async', [$this, 'ps_process_chunks_async']);


            add_action('wp_ajax_tjmk_approve_profile', [$this, 'tjmk_approve_profile']);

            add_action('wp_ajax_tjmk_delete_profile', [$this, 'tjmk_delete_profile']);

            add_action('wp_ajax_urp_bulk_delete_profiles', [$this, 'urp_bulk_delete_profiles']);

            $this->BulkUploadHandler = BulkUploadHandler::getInstance();

        }

    }

    /**
     * Handles the file upload request.
     *
     * @return void
     */
    public function ps_handle_file_upload()
    {
        $this->BulkUploadHandler->handle_file_upload();
    }

    /**
     * Handles the chunk processing request.
     *
     * @return void
     */
    public function ps_process_chunks_async()
    {
        $this->BulkUploadHandler->process_chunks_async();
    }

    /**
     * A profile approve function handle the ajax request
     */

    function tjmk_approve_profile()
    {
        // Check for nonce security
        //    check_ajax_referer('tjmk_approve_profile_nonce', 'security');

        if (isset($_POST['action']) && $_POST['action'] == 'tjmk_approve_profile' && isset($_POST['profile_id'])) {
            $profile_id = intval($_POST['profile_id']);

            // approve operation.
            $approve = $this->db->approve_profile($profile_id, 'approved');


            /* ----------- Stat Sending Email Notification ------*/
            // Author + Profile user get notification 

            // Instantiate the email class
            $mailer = WC()->mailer();

            $profile_data = $this->db->get_profile_by_id($profile_id);

            $author_id = $profile_data->author_id;
            $author = Helper::get_user_info_by_id($author_id);

            // Author data 
            $author_data = [
                'name' => $author['full_name'],
                'email' => $author['email'],
                'id' => $author_id,
            ];
            // Profile data 
            $profile_data = [
                'name' => Helper::get_person_name_process($profile_data),
                'email' => $profile_data->email,
                'id' => $profile_id,
            ];

            // Send the custom email notification
            // do_action('tjmk_trigger_ajax_email', $recipient, $custom_content);

            do_action('tjmk_trigger_profile_published_by_admin_to_author', $author_data['email'], $author_data);
            do_action('tjmk_trigger_profile_published_by_admin_to_profile', $profile_data['email'], $profile_data);

            /* ----------- Stat Sending Email Notification ------*/


            // If successful, return a JSON response
            if ($approve) {
                wp_send_json_success(array('message' => 'Profile approved successfully.', 'profile_id' => $profile_id));
            } else {
                wp_send_json_error(array('message' => 'Profile approve failed.'));
            }
        }
    }

    function tjmk_delete_profile()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'tjmk_delete_profile' && isset($_POST['profile_id'])) {
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

    /**
     * Handle the approval of a review.
     *
     * @return void
     */
    public function tjmk_approve_review()
    {
        global $wpdb;

        // Check for nonce security
        check_ajax_referer('tjmk_approve_reject_review_nonce', 'security');

        // Sanitize and validate input
        $data = Helper::sanitize_review_data($_POST);
        $profile_id = isset($_POST['profile_id']) ? intval($_POST['profile_id']) : 0;

        if (!$data['review_id']) {
            wp_send_json_error(['message' => 'Review ID is missing.']);
            return;
        }

        if (!$profile_id) {
            wp_send_json_error(['message' => 'Profile ID is missing or invalid']);
            exit;
        }

        // Start transaction
        $wpdb->query('START TRANSACTION');

        try {
            // Review fetched by review ID 
            $review_data = $this->db->get_review_by_review_id($data['review_id']);
            if (!$review_data) {
                throw new \Exception('Failed to fetch review.');
            }

            // Reviewer user data 
            $reviewer_user_data = Helper::get_user_info_by_id($review_data->reviewer_user_id);

            // Process the approval
            $result = $this->db->update_review_status($data['review_id'], 'approved');
            if (!$result) {
                throw new \Exception('Failed to approve review.');
            }

            // Fetch Person data
            $profile_data = $this->db->get_profile_by_id($profile_id);
            if (!$profile_data) {
                throw new \Exception('Failed to fetch profile data.');
            }

            // Fetch Person Full Name
            $person_name = Helper::get_person_name_process($profile_data);
            if (!$person_name) {
                throw new \Exception('Failed to fetch profile name.');
            }

            // Fetch all approved reviews
            $approved_reviews = $this->db->get_reviews('approved', $profile_id);
            if (!$approved_reviews) {
                throw new \Exception('Failed to fetch approved reviews.');
            }

            // Calculate rating
            $average_rating = 0; // Implement your logic here

            // Process review content
            $review_content = Helper::content_process($approved_reviews, $average_rating);
            if (!$review_content) {
                throw new \Exception('Failed to process review content.');
            }

            // Commit the transaction if all operations are successful
            $wpdb->query('COMMIT');

            // Only send emails if the transaction was successful

            /* ----------- Stat Sending Email Notification ------*/
            // Admin approve and Author and Profile user gets email 
            // Instantiate the email class
            $mailer = WC()->mailer();

            /**
             * User user get an eamail notification 
             */
            $author_id = $profile_data->author_id;
            $author = Helper::get_user_info_by_id($author_id);

            // Author data 
            $author_data = [
                'name' => $author['full_name'],
                'email' => $author['email'],
                'id' => $author_id,
            ];
            // Profile data 
            $profile_data = [
                'name' => Helper::get_person_name_process($profile_data),
                'email' => $profile_data->email,
                'id' => $profile_id,
            ];
            // Send the custom email notification
            do_action('tjmk_trigger_review_published_to_author', $author_data['email'], $author_data);
            do_action('tjmk_trigger_review_published_to_profile', $profile_data['email'], $profile_data);


            wp_send_json_success(['message' => 'Review approved successfully.']);

        } catch (\Exception $e) {
            // If any step fails, roll back the transaction
            $wpdb->query('ROLLBACK');

            // Log the error
            $this->log_error('Error during review approval: ' . $e->getMessage());

            // Send an error response
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }

    /**
     * Handle the rejection of a review.
     *
     * @return void
     */
    public function tjmk_reject_review()
    {
        // Check for nonce security
        check_ajax_referer('tjmk_approve_reject_review_nonce', 'security');

        $review_id = intval($_POST['review_id']);

        if (!$review_id) {
            $this->log_error('Review ID is missing');
            wp_send_json_error(['message' => 'Review ID is missing.']);
            return;
        }

        // Process the rejection
        $result = $this->db->update_review_status($review_id, 'rejected');

        // Review fetched by review ID 
        $review_data = $this->db->get_review_by_review_id($review_id);


        if (!$review_data) {
            throw new \Exception('Failed to fetch review.');
        }

        // Reviewer user data 
        $author = Helper::get_user_info_by_id($review_data->reviewer_user_id);


        /* ----------- Stat Sending Email Notification ------*/
        // Admin approve and Author and Profile user gets email 
        // Instantiate the email class
        $mailer = WC()->mailer();

        /**
         * User user get an eamail notification 
         */
        // Author data 
        $author_data = [
            'name' => $author['full_name'],
            'email' => $author['email'],
            'id' => $review_data->reviewer_user_id,
        ];
        // Send the custom email notification
        do_action('tjmk_trigger_review_reject_to_author', $author_data['email'], $author_data);

        /* ----------- Stat Sending Email Notification ------*/



        if ($result) {
            wp_send_json_success(['message' => 'Review rejected successfully.']);
        } else {
            $this->log_error('Failed to reject review with ID: ' . $review_id);
            wp_send_json_error(['message' => 'Failed to reject review.']);
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
}