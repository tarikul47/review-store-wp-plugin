<?php
namespace Tarikul\TJMK\Inc\AjaxHandler;

use Tarikul\TJMK\Inc\Database\Database;
use Tarikul\TJMK\Inc\BulkUploadHandler\BulkUploadHandler;
use Tarikul\TJMK\Inc\Helper\Helper;
use Tarikul\TJMK\Inc\Email\Email;

class AjaxHandler
{

    private $db;
    private $BulkUploadHandler;
    /**
     * Constructor to add AJAX actions.
     */
    public function __construct()
    {
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
        add_action('wp_ajax_search_profiles', [$this, 'search_profiles_callback']);
        add_action('wp_ajax_nopriv_search_profiles', [$this, 'search_profiles_callback']);
    }

    function search_profiles_callback()
    {
        global $wpdb;
        $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $profiles_per_page = 5; // Set the number of profiles per page

        $offset = ($page - 1) * $profiles_per_page;

        $profiles = $this->db->get_profiles_with_review_data('approved', $search_term, $profiles_per_page, $offset);

        // Get total profiles count for pagination
        $total_profiles = $this->db->get_total_profiles_count($search_term);
        $total_pages = ceil($total_profiles / $profiles_per_page);

        $product_id = 509;

        // Generate HTML for profiles
        ob_start();
        if (!empty($profiles)) {
            foreach ($profiles as $profile) {
                echo '<tr class="clickable-row" onclick="window.location.href=\'' . esc_url(get_permalink() . '?profile_id=' . $profile->profile_id) . '\'">';
                echo "<td>" . esc_html($profile->first_name) . "</td>";
                echo "<td>" . esc_html($profile->last_name) . "</td>";
                echo "<td>" . esc_html($profile->title) . "</td>";
                echo "<td>" . esc_html($profile->employee_type) . "</td>";
                echo "<td>" . esc_html($profile->department) . "</td>";
                echo "<td>" . esc_html($profile->municipality) . "</td>";

                $review_score_image_path = TJMK_PLUGIN_ASSETS_URL . '/images/icons/review-icon' . '-' . $profile->average_rating . '.png';

                echo "<td><img class='review-score-icon' src='" . $review_score_image_path . "' alt=''></td>";

                $review_icon_image_path = TJMK_PLUGIN_ASSETS_URL . '/images/icons/card-icon' . '.svg';

                echo '<td><a href="' . wc_get_cart_url() . "?add-to-cart=" . $product_id . "&p_id=" . $profile->profile_id . '"><img src="' . $review_icon_image_path . '" alt="card-icon"></a></td>';


                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>" . esc_html__('No profiles found.', 'tjmk') . " <a href='" . esc_url('/add-profile') . "'>" . esc_html__('Please add a profile', 'tjmk') . "</a></td></tr>";
        }
        $profiles_html = ob_get_clean();

        // Generate HTML for pagination
        ob_start();

        if ($total_pages > 1) {
            echo '<ul>';

            // "Previous" button (disabled if on the first page)
            if ($page > 1) {
                echo '<li><a href="#" data-page="' . ($page - 1) . '">Previous</a></li>';
            } else {
                echo '<li><a href="" class="disabled">Previous</a></li>';
            }

            // Page number links
            for ($i = 1; $i <= $total_pages; $i++) {
                $active_class = ($i == $page) ? 'id="active"' : '';
                echo '<li><a ' . $active_class . ' href="#" data-page="' . $i . '">' . $i . '</a></li>';
            }

            // "Next" button (disabled if on the last page)
            if ($page < $total_pages) {
                echo '<li><a href="#" data-page="' . ($page + 1) . '">Next</a></li>';
            } else {
                echo '<li><a href="" class="disabled">Next</a></li>';
            }

            echo '</ul>';
        }
        $pagination_html = ob_get_clean();

        // Return profiles and pagination
        wp_send_json_success([
            'profiles' => $profiles_html,
            'pagination' => $pagination_html,
        ]);

        wp_die(); // WordPress requires this to properly end AJAX requests
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

        $profile_id = isset($_POST['profile_id']) ? intval($_POST['profile_id']) : 0;

        if (!$profile_id) {
            wp_send_json_error(['message' => 'Profile ID is missing or invalid']);
            exit;
        }

        // Ensure profile_id exists in the review data
        if (empty($review_data)) {
            wp_send_json_error(['message' => 'Profile ID is missing']);
        }

        // Ensure profile_id exists in the review data
        if (empty($profile_id)) {
            wp_send_json_error(['message' => 'Profile ID is missing']);
        }

        // Calculate rating
        $average_rating = Helper::calculate_rating($review_data);
        if (!$average_rating) {
            wp_send_json_error(['message' => 'Failed to calculate rating']);
        }

        // Check if the user has already submitted a review for this profile
        $existing_review = $this->db->get_existing_review($profile_id);
        if ($existing_review) {
            wp_send_json_error(['message' => 'You have already reviewed this profile']);
        }

        // Insert the review into the database
        $review_id = $this->db->insert_review($profile_id, $average_rating);
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
            : 'Your review is pending and will be published after approval. You will receive an email notification after approval.';

        //TODO: Email Need to send Author and Admin 

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
        do_action('tjmk_trigger_review_created_pending_by_user_to_user', $author_data['email'], $author_data);
        do_action('tjmk_trigger_review_created_pending_by_user_to_admin', $admin_data['email'], $admin_data);

        /* ----------- Stat Sending Email Notification ------*/

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



    /**
     * Log errors for debugging purposes.
     *
     * @param string $message The error message to log.
     * @return void
     */
    // private function log_error($message)
    // {
    //     error_log('[AjaxHandler] ' . $message);
    // }
}
