<?php

namespace Tarikul\PersonsStore\Inc\Admin\Class\Review;
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Email\Email;
use Tarikul\PersonsStore\Inc\Helper\Helper;

class ReviewManagement
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
        // Add action for form Review
        add_action('admin_post_tjmk_review_update', [$this, 'tjmk_handle_review_update']);
    }

    public function tjmk_handle_review_update()
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
}