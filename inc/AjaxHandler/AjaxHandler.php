<?php
namespace Tarikul\PersonsStore\Inc\AjaxHandler;

use Tarikul\PersonsStore\Inc\Database\Database;

class AjaxHandler
{

    /**
     * Constructor to add AJAX actions.
     */
    public function __construct()
    {
        add_action('wp_ajax_approve_review', [$this, 'approve_review']);
        add_action('wp_ajax_reject_review', [$this, 'reject_review']);

        $this->db = Database::getInstance();
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
