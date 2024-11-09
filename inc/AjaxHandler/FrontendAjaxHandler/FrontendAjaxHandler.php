<?php
namespace Tarikul\TJMK\Inc\AjaxHandler\FrontendAjaxHandler;

use Tarikul\TJMK\Inc\Database\Database;
use Tarikul\TJMK\Inc\Helper\Helper;


class FrontendAjaxHandler
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance();

        add_action('wp_ajax_search_profiles', [$this, 'search_profiles_callback']);
        add_action('wp_ajax_nopriv_search_profiles', [$this, 'search_profiles_callback']);

        add_action('wp_ajax_add_review', [$this, 'handle_frontend_add_review']);
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
}
