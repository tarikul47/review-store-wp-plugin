<?php
namespace Tarikul\PersonsStore\Inc\AjaxHandler;

use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\BulkUploadHandler\BulkUploadHandler;

class AjaxHandler
{

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
        }


        $this->db = Database::getInstance();
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
        error_log(print_r($_POST, true));
        // Check for nonce security
        check_ajax_referer('approve_reject_review_nonce', 'security');

        $review_id = intval($_POST['review_id']);

        if (!$review_id) {
            $this->log_error('Review ID is missing');
            wp_send_json_error(['message' => 'Review ID is missing.']);
            return;
        }

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
