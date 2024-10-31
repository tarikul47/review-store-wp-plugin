<?php

namespace Tarikul\PersonsStore\Inc\Admin\Class\Menu;
use Tarikul\PersonsStore\Inc\Database\Database;

class MenuCallbacks
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
    }

    public function tjmk_profile_list_page()
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

        include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-profile-list-page.php';
    }

    public function tjmk_pending_profile_list_page()
    {
        // Display the message from the transient, if it exists
        if ($message = get_transient('form_submission_message')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            delete_transient('form_submission_message');
        }

        $users = $this->db->get_profiles_with_review_data('pending');
        include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-pending-profile-list-page.php';
    }

    public function tjmk_add_profile_page()
    {
        include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-add-profile-page.php';
    }

    public function tjmk_approve_reviews_page()
    {
        $approved_reviews = $this->db->get_reviews('approved'); // Get approved reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-approve-reviews-page.php';
    }

    public function tjmk_pending_reviews_page()
    {
        // Display the message from the transient, if it exists
        if ($message = get_transient('form_submission_message')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            delete_transient('form_submission_message');
        }

        $pending_reviews = $this->db->get_reviews('pending'); // Get pending reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-pending-reviews-page.php';
    }

    public function tjmk_disply_reviews_page()
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
        include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-display-reviews-page.php';
    }

    public function tjmk_bulk_profiles_upload()
    {
        // Include the view file to display the reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-bulk-profiles-upload.php';
    }
    // public function tjmk_settings_page()
    // {
    //     // Include the view file to display the reviews
    //     include_once PLUGIN_ADMIN_VIEWS_DIR . 'tjmk-settings-page.php';
    // }
}
